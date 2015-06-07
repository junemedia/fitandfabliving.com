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
class YooRecipeModelSearch extends JModelList
{

	function __construct()
	{
		parent::__construct();
	}
	
	/** Remove pagination from search results */
	protected function populateState($ordering = null, $direction = null)
	{
		// set to 0 to show all
		$this->setState('list.limit', 0);

		$input 	= JFactory::getApplication()->input;
		$app 	= JFactory::getApplication();
		$value 	= $input->get('limitstart', '0', 'INT');
		$menu 	= $app->getMenu();
		$active = $menu->getActive();
		
		if ($active) {
			$params = new JRegistry();
			$params->loadString($active->params);
			$this->setState('orderCol',$params->get('recipe_sort', 'title'));
		} else {
			$this->setState('orderCol', 'title');
		}
				
		$this->setState('list.start', $value);
		$this->setState('filter.access', true);
		$this->setState('filter.language', $app->getLanguageFilter());
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.		
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Retrieve component parameters
		$params 		= JComponentHelper::getParams('com_yoorecipe');

		// Get request variables
		$input 	= JFactory::getApplication()->input;
		$searchword 		= $input->get('searchword', '');
		$withIngredients 	= array_filter($input->get('withIngredients', array(), 'ARRAY'));
		$searchCategories 	= array_filter($input->get('searchCategories', array(), 'ARRAY'));
		$withoutIngredient 	= $input->get('withoutIngredient', '');
		
		$search_author				= $input->get('search_author');
		$search_max_prep_hours		= $input->get('search_max_prep_hours');
		$search_max_prep_minutes	= $input->get('search_max_prep_minutes');
		$search_max_cook_hours		= $input->get('search_max_cook_hours');
		$search_max_cook_minutes	= $input->get('search_max_cook_minutes');
		$search_min_rate			= $input->get('search_min_rate');
		$search_max_cost			= $input->get('search_max_cost');
		
		$search_operator_price		= $input->get('search_operator_price');
		$search_price				= $input->get('search_price');
		$search_type_diet			= $input->get('search_type_diet');
		$search_type_veggie			= $input->get('search_type_veggie');
		$search_type_glutenfree		= $input->get('search_type_glutenfree');
		$search_type_lactosefree	= $input->get('search_type_lactosefree');
		
		// Get recipe fields
		$query->select(' distinct r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note'.
				', r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		$query->from('#__yoorecipe as r');
		
		// Join over ingredients
		$query->join('INNER', '#__yoorecipe_ingredients as i on i.recipe_id = r.id');
		
		// Join over cross categories
		$query->join('INNER', '#__yoorecipe_categories as cc on cc.recipe_id = r.id');
		
		// Join over categories
		$query->join('INNER', '#__categories as c on c.id = cc.cat_id');
		
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites AS fr ON fr.recipe_id = r.id AND fr.user_id = ' . $db->quote($user->id));
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		
		// Prepare where clause
		$whereClause = 'r.published = 1 AND r.validated = 1';
		
		// Filter by title
		if (!empty($searchword)) {

			$whereClause .= ' AND ( r.title like ' . $db->quote('%' . $searchword . '%');
			
			if ($params->get('include_search_on_description', 1)) {
				$whereClause .= ' OR r.description like ' . $db->quote('%' . $searchword . '%');
			}
			
			if ($params->get('include_search_on_preparation', 1)) {
				$whereClause .= ' OR r.preparation like ' . $db->quote('%' . $searchword . '%');
			}
			
			if ($params->get('include_search_on_tags', 1)) {
				
				$query->join('LEFT', '#__yoorecipe_tags AS t ON t.recipe_id = r.id');
				$whereClause .= ' OR t.tag_value like ' . $db->quote('%' . $searchword . '%');
			}
			$whereClause .= ')';
		}
		
		// Filter by recipe type
		if (isset($search_type_diet) && $search_type_diet == 'on') {
			$query->where('r.diet = 1');
		}
		if (isset($search_type_veggie) && $search_type_veggie == 'on') {
			$query->where('r.veggie = 1');
		}
		if (isset($search_type_glutenfree) && $search_type_glutenfree == 'on') {
			$query->where('r.gluten_free = 1');
		}
		if (isset($search_type_lactosefree) && $search_type_lactosefree == 'on') {
			$query->where('r.lactose_free = 1');
		}
		
		// Filter by ingredients
		if (is_array($withIngredients) && !empty($withIngredients)) {
			JArrayHelper::toString($withIngredients);
			foreach ($withIngredients as $key => $alias)
				{
					// TODO Chopping off s will be possible in a near future
					$withIngredients[$key] = $db->quote('%' . $alias . '%');
				}
			$withIngredients = implode(" OR i.description LIKE ", $withIngredients);
			$whereClause .= ' AND (i.description like '. $withIngredients . ')';
		}
		
		// Exclude some ingredients
		if (isset($withoutIngredient) && !empty($withoutIngredient)) {
				
			$queryRecipesToExclude = 'select distinct r2.id as id from #__yoorecipe r2 inner join #__yoorecipe_ingredients i2 '.
				' on i2.recipe_id = r2.id where i2.description like '. $db->quote('%'. $withoutIngredient .'%');
			
			$db->setQuery($queryRecipesToExclude);
			$recipesToExclude = $db->loadObjectList();
			
			if (count($recipesToExclude) > 0) {
				foreach ($recipesToExclude as $recipeToExclude ) {
					$recipeIdsToExclude[] = $recipeToExclude->id;
				}
				JArrayHelper::toInteger($recipeIdsToExclude);
				$recipeIdsToExclude = implode(', ', $recipeIdsToExclude);
				$whereClause .= ' AND r.id not in (' . $recipeIdsToExclude . ')';
			}
		}
		
		// Filter by category
		if (is_numeric($searchCategories) && !empty($searchCategories)) {
			$whereClause .= ' AND cc.cat_id = '.(int) $searchCategories;
		}
		else if (is_array($searchCategories) && !empty($searchCategories)) {
			
			JArrayHelper::toInteger($searchCategories);
			$searchCategories = implode(',', $searchCategories);
			$whereClause .= ' AND cc.cat_id IN (' . $searchCategories . ')';
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
		
		// Filter by author
		if (isset($search_author) && !empty($search_author)) {
			$query->where('r.created_by = ' . $search_author);
		}
		
		// Filter by preparation time
		if ( (isset($search_max_prep_hours) && !empty($search_max_prep_hours)) || (isset($search_max_prep_minutes) && !empty($search_max_prep_minutes)) ) {
			
			$maxPreparationTime = $search_max_prep_hours * 60 + $search_max_prep_minutes;
			if ($maxPreparationTime > 0) {
				$query->where('r.preparation_time < ' . $maxPreparationTime);
			}
		}
		
		// Filter by cook time
		if ( (isset($search_max_cook_hours) && !empty($search_max_cook_hours)) || (isset($search_max_cook_minutes) && !empty($search_max_cook_minutes)) ) {
			
			$maxCookTime = $search_max_cook_hours * 60 + $search_max_cook_minutes;
			if ($maxCookTime > 0) {
				$query->where('r.cook_time < ' . $maxCookTime);
			}
		}
		
		// Filter by rating
		if (isset($search_min_rate) && !empty($search_min_rate)) {
			if ($search_min_rate > 0) {
				$query->where('r.note >= ' . $search_min_rate);
			}
		}
		
		// Filter by cost
		if (isset($search_max_cost) && !empty($search_max_cost)) {
			if ($search_max_cost > 0) {
				$query->where('r.cost <= ' . $search_max_cost);
			}
		}
		
		// Filter by price
		if (isset($search_operator_price) && $search_operator_price != 999) { // if different from Any
			$priceVal = str_replace(",",".", $search_price);
			if (isset($search_price) && !empty($search_price) && is_numeric($priceVal)) {
				$operator = ($search_operator_price == 'lt') ? '<=' : '>';
				$query->where('r.price ' . $operator . $priceVal);
			}
		}
		
		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(JFactory::getDate()->toSQL());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		// Prepare order by clause
		$query->order('r.' . $this->getState('orderCol') . ' ' . 'asc');
		$query->group('r.id');
		
		return $query;
	}
	
	/**
	 * Get recipe authors
	 */
	public function getAuthors()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Get categories fields
		$query->select('distinct created_by, u.id');
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('u.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('u.name AS author_name');
		}
		
		$query->from('#__yoorecipe as r');
		$query->join('LEFT', '#__users as u on u.id = r.created_by');
	
		// Set where clause
		$query->where('r.published = 1 and r.validated = 1');
		if ($showAuthorName == 'username') {
			$query->order('u.username asc');
		} else if ($showAuthorName == 'name') {
			$query->order('u.name asc');
		}
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}