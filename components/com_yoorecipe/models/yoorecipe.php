<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * YooRecipe Model
 */
class YooRecipeModelYooRecipe extends JModelList
{
	/**
	 * @var string msg
	 */
	protected $msg;
	protected $recipes;
	protected $recipe;
	protected $ratings;
	
	/**
	 * Items total
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 * @var object
	 */
	var $_pagination = null;
 
 	
	function __construct()
	{
		parent::__construct();
	}
  
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		// List state information
		$app	= JFactory::getApplication();
		$menu 	= $app->getMenu();
		$active = $menu->getActive();
		
		if ($active) {
			$params = new JRegistry();
			$params->loadString($active->params);
			$this->setState('orderCol',$params->get('recipe_sort', 'title'));
		} else {
			$this->setState('orderCol', 'title');
		}
		
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$limit 				= $app->getUserStateFromRequest('global.list.limit', 'limit', $yooRecipeparams->get('list_length', 10), 'int');

		$input 	= JFactory::getApplication()->input;
		$this->setState('list.limit', $limit);
		$this->setState('list.start', $input->get('limitstart', '0', 'INT'));
		$this->setState('filter.access', true);
		$this->setState('filter.language', $app->getLanguageFilter());
	}
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'YooRecipe', $prefix = 'YooRecipeTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get published recipe by identifier
	 */
	public function getRecipeById($recipeId)
	{
		// Create a new query object.		
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$lang 	= JFactory::getLanguage();

		// From the recipe table
		$query->from('#__yoorecipe as r');
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.recipe_id = r.id');
		$query->join('LEFT', '#__categories c on cc.cat_id  = c.id');
		
		// Select some fields
		$query->select( 'r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.metadata, r.metakey'.
				', r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
	
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites AS fr ON fr.recipe_id = r.id AND fr.user_id = ' . $db->quote($user->id));
		
		// Before frontend submission, unpublished and unvalidated recipes were filtered, not the case anymore to show awaiting approval message
		$query->where('r.id = ' . $db->quote($recipeId) . ' and c.published = 1');
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('r.access IN ('.$groups.')');
		}
		
		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(JFactory::getDate()->toSQL());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		$query->group('r.id');
		
		// Get recipe
		$db->setQuery($query);
		$recipe = $db->loadObject();
		
		if (isset($recipe->published) && isset($recipe->validated)) {

			// Get recipe's ingredients
			$recipe->ingredients 	= $this->getIngredientsByRecipeId($recipeId, $lang->getTag());
			
			// Get recipe's ratings
			$nbCommentsToFetch 		= $yooRecipeparams->get('nb_comments_to_fetch', 0);
			$recipe->ratings 		= $this->getRatingsByRecipeIdOrderedByDateDesc($recipeId, 0, $nbCommentsToFetch);
			$recipe->total_ratings	= $this->getTotalNbOfRatingsByRecipe($recipeId);
			$recipe->season_id		= $this->getRecipeSeasonsIds($recipeId);
		}
		
		return $recipe;
	}
	
		/**
	 * Method to cross categories for a given recipe
	 * @return The object list of categories
	 *
	 */
	public function getRecipeSeasonsIds($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe table
		$query->from('#__yoorecipe_seasons');
		$query->select('month_id');
		$query->where('recipe_id = ' . $db->quote($pRecipeId));
		
		$db->setQuery($query);
		return $db->loadColumn();
	}
	
	/**
	 * Get ingregients of a given recipe
	 */
	public function getIngredientsByRecipeId($recipeId, $tagLang)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('i.id, i.recipe_id, i.ordering,  i.quantity, u.label as unit, i.description, i.price, i.group_id, g.text as ingr_group');
		$query->from('#__yoorecipe_ingredients i');
		
		$query->join('LEFT', '#__yoorecipe_units u on u.code = i.unit');
		$query->join('LEFT', '#__yoorecipe_ingredients_groups g on g.id = i.group_id');
		
		$query->where('i.recipe_id = ' . $db->quote($recipeId) . ' AND u.lang = ' . $db->quote($tagLang));
		$query->order('g.id, g.text, i.ordering asc');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function getNbRatingsByRecipeId($recipeId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->select('count(*)');
		$query->from('#__yoorecipe_rating r');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		$query->where('published = 1');
		$query->where('abuse = 0');
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * Increment the view counter of a given recipe
	 */
	public function incrementViewCountOfRecipe($recipeId, $oldNbViews)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->update('#__yoorecipe');
		$query->set('nb_views = '. $db->quote($oldNbViews+1));
		$query->where('id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	/**
	 * Get ratings for a given recipe
	 */
	public function getRatingsByRecipeIdOrderedByDateDesc($recipeId, $offset = 0, $limit = 0)
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Select some fields
		$query->select('r.id, r.recipe_id, r.note, r.author, r.user_id, r.comment, r.creation_date, r.published, r.abuse');

		// From the recipe rating table
		$query->from('#__yoorecipe_rating r');
		$query->where('r.published = 1');
		$query->where('r.abuse = 0');
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.user_id');
		
		// Where
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		// Order
		$query->order('creation_date desc');
		
		// Rownum
		$db->setQuery($query, $offset, $limit);
		
		$this->ratings =  $db->loadObjectList();
		
		return $this->ratings;
	}
	
	/**
	* getTotalNbOfRatingsByRecipe
	*/
	public function getTotalNbOfRatingsByRecipe($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('count(*)');
		$query->from('#__yoorecipe_rating r');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		$query->where('published = 1');
		$query->where('abuse = 0');
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * Insert a rating for a given recipe
	 */
	public function insertRating($recipeId, $author, $email = null, $comment, $note, $userId = null)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->insert('#__yoorecipe_rating');
		$query->set('recipe_id = '. $db->quote($recipeId));
		$query->set('note = '. $db->quote($note));
		$query->set('user_id= '. $db->quote($userId));
		$query->set('author = '. $db->quote($author));
		$query->set('email = '. $db->quote($email));
		$query->set('comment = '. $db->quote($comment));
		$query->set('creation_date= '. $db->quote(JFactory::getDate()->toSQL()));
		$query->set('abuse = 0');

		$yooRecipeparams = JComponentHelper::getParams('com_yoorecipe');
		if ($yooRecipeparams->get('auto_publish_comment', 1) == 1) {
			$query->set('published = 1');
		} else {
			$query->set('published = 0');
		}
		
		$db->setQuery($query);
		$db->execute();

	}

	/**
	 * Update the global note of a given recipe
	 */
	public function updateRecipeGlobalNote($recipeId) {
	
		// Calculate ponderated note
		$recipe = $this->getRecipeById($recipeId);
		$nbRatings = count($recipe->ratings);
		
		$sum = 0;
		$globalNote = null;
		if ($nbRatings > 0) {
		
			foreach ($recipe->ratings as $rating) {
				$sum += $rating->note;
			}
			$globalNote = round( (float) $sum / $nbRatings, 2); 
		}	
		
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__yoorecipe');
		$query->set('note = '. $db->quote($globalNote));
		$query->where('id = '. $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery($categoryId = null, $orderBy = null)
	{
		// Create a new query object.		
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Select some fields
		$query->select( 'SQL_CALC_FOUND_ROWS r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.metadata, r.metakey' .
				', r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		// From the recipe table
		$query->from('#__yoorecipe as r');
		
		// Join over Cross Categories
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.recipe_id = r.id');

		// Join over Categories
		$query->join('LEFT', '#__categories c on c.id = cc.cat_id');
		
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites AS fr ON fr.recipe_id = r.id AND fr.user_id = ' . $db->quote($user->id));
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		
		// Prepare where clause
		$whereClause = 'r.published = 1 and r.validated = 1';
		if ($categoryId != null) {
			$whereClause .= ' and cc.cat_id = ' . $db->quote($categoryId);
		}
		$query->where($whereClause);
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('r.access IN ('.$groups.')');
		}
		
		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(JFactory::getDate()->toSQL());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		// Prepare order by clause
		if ($orderBy != null) {
			$orderByClause = 'r.' . $orderBy . ' ' . $asc . ' ';
			$query->order($orderByClause);
		} else {
			$query->order('r.' . $this->getState('orderCol') . ' ' . 'asc');
		}
		$query->group('r.id');
		
		return $query;
	}
	
	/**
	 * Method to add a recipe
	 */
	public function insertRecipe($recipeObject) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->insertObject('#__yoorecipe', $recipeObject, 'id');
		
		// Get last inserted recipe identifier
		$recipeId = $db->insertid();
		$order = 0;
		foreach ($recipeObject->ingredients as $ingredient) :
			$ingredient->recipe_id = $recipeId;
			$ingredient->ordering = $order;
			$db->insertObject('#__yoorecipe_ingredients', $ingredient);
			$order++;
		endforeach;
		
		return $recipeId;
	}
	
	/**
	 * Method to add a recipe
	 */
	public function updateRecipe($recipeObject) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->updateObject('#__yoorecipe', $recipeObject, 'id');
		
		// Delete old ingredients
		$query->clear();
		$query->delete('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($recipeObject->id));
		$db->setQuery($query);
		$db->execute();
		
		// Get last inserted recipe identifier
		$recipeId = $recipeObject->id;
		$order = 0;
		foreach ($recipeObject->ingredients as $ingredient) :
			
			$ingredient->id = null;
			$ingredient->recipe_id = $recipeId;
			$ingredient->ordering = $order;
			$db->insertObject('#__yoorecipe_ingredients', $ingredient, $ingredient->id);
			$order++;
		endforeach;
	}
	
	/**
	 * Returns the user id who created a given recipe
	 */
	function getAuthorByRecipeId($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('created_by');
		$query->from('#__yoorecipe');
		$query->where('id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	* Delete recipe by identifier. Also deletes all information related to recipes
	*/
	function deleteRecipeById($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete dependencies
		$this->deleteCommentByIdRecipeAndIdComment($recipeId);
		$this->deleteCrossCategoriesByRecipeId($recipeId);
		$this->deleteFromFavourites($recipeId);
		$this->deleteIngredientsByRecipeId($recipeId);
		$this->deleteAssetsOfRecipeId($recipeId);
		$this->deleteTagsByRecipeId($recipeId);
		
		// Delete recipe
		$query->delete('#__yoorecipe');
		$query->where('id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	private function deleteAssetsOfRecipeId($recipeId) {
		
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete cross categories
		$query->delete('#__assets');
		$query->where('name = ' . $db->quote('#__yoorecipe.' . $recipeId));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	private function deleteIngredientsByRecipeId($recipeId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	private function deleteCrossCategoriesByRecipeId($recipeId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_categories');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	function deleteCommentByIdRecipeAndIdComment($recipeId, $commentId = null) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_rating');
		if ($commentId != null) {
			$query->where('id = ' . $db->quote($commentId));
		}
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		return $db->execute();
	}
	
	function deleteTagsByRecipeId($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_tags');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		return $db->execute();
	}
	
	function reportCommentAsOffensive($recipeId, $commentId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->update('#__yoorecipe_rating');
		$query->set('abuse = 1');
		$query->where('id = ' . $db->quote($commentId));
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		return $db->execute();
	}
	
	function getRecipesByLetter($letter) {
	
		// Create a new query object.		
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		$query->select('SQL_CALC_FOUND_ROWS distinct r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.metadata, r.metakey' .
				', r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		
		$query->from('#__yoorecipe r');
	
		// Join over Users
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		
		// Join over Cross Categories
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.recipe_id = r.id');

		// Join over Categories
		$query->join('LEFT', '#__categories c on c.id = cc.cat_id');
		
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites AS fr ON fr.recipe_id = r.id AND fr.user_id = ' . $db->quote($user->id));
		
		// Prepare where clause
		if ($letter == 'dash') {
			$query->where("r.title regexp '^[0-9]'");
		} else {
			$query->where('(r.title like ' . $db->quote(strtolower($letter).'%') . ' OR r.title like ' . $db->quote(strtoupper($letter).'%') . ')');
		}
		
		$query->where('r.published = 1 and r.validated = 1');
		$query->where('c.published = 1');
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('r.access IN ('.$groups.')');
		}
		
		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$query->group('r.id');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function addToFavourites($recipeId, $user) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->insert('#__yoorecipe_favourites');
		$query->set('recipe_id = ' . $db->quote($recipeId));
		$query->set('user_id = ' . $db->quote($user->id));
		
		$db->setQuery($query);
		$db->execute();
	}
	
	function deleteFromFavourites($recipeId, $user = null) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_favourites');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		if ($user != null) {
			$query->where('user_id = ' . $db->quote($user->id));
		}
		$db->setQuery($query);
		$db->execute();
	}
}