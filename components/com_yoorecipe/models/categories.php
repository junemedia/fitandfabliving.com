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
jimport('joomla.application.categories');

/**
 * YooRecipe Model
 */
class YooRecipeModelCategories extends JModelList
{
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
	
	/**
	* Items list
	* @ var object
	*/
	var $_items = null;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
 
		$input 		= JFactory::getApplication()->input;
		$categoryId = $input->get('id');
		$categoryIdSlugs = preg_split('/:/',$categoryId);
		$this->setState('categoryId', $categoryIdSlugs[0]);
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
	 * Get all categories
	 */
	function getAllPublishedCategories() {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->from('#__categories');
		
		// Select some fields
		$query->select('id, title, alias, parent_id, level, lft, rgt, language');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug');
		
		if ($this->getState('filter.language')) {
			$query->where('language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$query->where('extension = ' . $db->quote('com_yoorecipe') . ' and published = 1'); 
		$query->order('lft asc');
		
		$db->setQuery((string) $query);
		
		// Get recipes
		return $db->loadObjectList();
	}
	
	/**
	 * Get a category of recipe
	 */
	public function getCategoryById($categoryId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->from('#__categories');
		
		// Select some fields
		$query->select('id, title, alias, description, metadesc, metakey, metadata, published');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug');
		
		$query->where('id = ' . $db->quote($categoryId));
		
		$db->setQuery($query);
		
		// Get recipes
		return $db->loadObject();
	}
	
	/**
	 * Get all sub-categories of the category specified by parentId 
	 */
	public function getCategoriesByParentId($parentSlug)
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe category table
		$query->from('#__categories');
		
		// Select some fields
		$query->select('id, title, alias, params');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug');
		
		$query->where('language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')' );
		$query->where('extension = ' . $db->quote('com_yoorecipe'));
		
		$parentId = preg_split('/:/',$parentSlug);
		$query->where('parent_id = ' . $db->quote($parentId[0]));
		$query->where('published = 1'); 
		
		$query->order('lft' . ' ' . 'asc');
		
		$db->setQuery($query);
		
		// Get recipes
		return $db->loadObjectList();
	}
	
	/**
	 * Return the number of published, validated recipes from a given category id
	 * @param category: The c ategory id to look for
	 * @param recursive: Whether or not children categories should be included in count
	 */
	public function getNbRecipesByCategoryId($categorySlug, $recursive = true) {
		
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Get nb recipes
		$query->select('count(jr.id)');
			
		// From the recipe category table
		$query->from('#__yoorecipe jr');
		
		// Join over cross categories
		$query->join('INNER', '#__yoorecipe_categories as cc ON cc.recipe_id = jr.id');
		
		// Join over categories
		$query->join('LEFT', '#__categories jc ON jc.id = cc.recipe_id');
		
		// Prepare where clause
		$query->where('jr.published = 1 AND jr.validated = 1');
		
		$categoryIdArray = preg_split('/:/',$categorySlug);
		$subCategoryIds = $this->getAllSubCategories($categoryIdArray[0], $recursive = true, $force = true);
		if (count($subCategoryIds) > 0) {
			$query->where('cc.cat_id IN (' . $categoryIdArray[0] . ',' . implode(',', $subCategoryIds) . ')');
		} else {
			$query->where('cc.cat_id = ' . $categoryIdArray[0]);
		}
		
		$db->setQuery($query);
		
		// Get recipes
		return $db->loadResult();
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Select some fields
		$query->select('SQL_CALC_FOUND_ROWS r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		// From the recipe table
		$query->from('#__yoorecipe as r');
		
		// Join over cross categories
		$query->join('INNER', '#__yoorecipe_categories as cc on cc.recipe_id = r.id');
		
		// Join over categories
		$query->join('LEFT', '#__categories c on c.id = cc.cat_id');
		
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
		$query->where('r.published = 1');
		$query->where('r.validated = 1');
		
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

		// Include sub categories
		$input = JFactory::getApplication()->input;
		$categoryId = $input->get('id', 0, 'INT');
		$allSubCategories = $this->getAllSubCategories($categoryId, $recursive = true);
		
		if ($allSubCategories){
			$query->where('cc.cat_id in (' . $categoryId . ',' . implode(',', $allSubCategories) . ')');
		}
		else {
			$query->where('cc.cat_id = ' . $db->quote($categoryId));
		}

		// Guarantee unicity of recipes
		$query->group('r.id');
		
		// Sort by parameted sort
		$query->order('r.' . $this->getState('orderCol') . ' ' . 'asc');
		return $query;
	}
	
	/**
	 * Returns all sub categories of category passed in parameter
	 * @force: force reloading
	 */
	public function getAllSubCategories($parentCatId, $recursive = false, $force = false) {
	
		$ids = array();
		if ($force || !count($this->_items)) {
		
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry();

			if ($active) {
				$params->loadString($active->params);
			}

			$options = array();
			$categories = JCategories::getInstance('YooRecipe', $options);
			$this->_parent = $categories->get($parentCatId);

			if (is_object($this->_parent)) {
				$this->_items = $this->_parent->getChildren($recursive);
				
				foreach ($this->_items as $item) :
					$ids[] = $item->id;
				endforeach;
			}
			else {
				$this->_items = false;
			}
		}
		return $ids;
	}
	
	/**
	 * Get all categories
	 */
	function getAllPublishedCategoriesWithCount() {
	
		// Create a new query object.		
		$db = JFactory::getDBO();

		$db->setQuery('SELECT count(jc.id) AS nb_recipes, jc.id, jc.title, jc.alias, jc.parent_id, jc.level, jc.lft, jc.rgt, jc.language, ' .
						' CASE WHEN CHARACTER_LENGTH(jc.alias) THEN CONCAT_WS(\':\', jc.id, jc.alias) ELSE jc.id END as slug' .
						' FROM #__categories jc ' . 
						' INNER JOIN #__yoorecipe_categories cc ON cc.cat_id = jc.id ' . 
						' INNER JOIN #__yoorecipe jr ON jr.id = cc.recipe_id ' . 
						' WHERE jc.extension = ' . $db->quote('com_yoorecipe') . ' AND jc.parent_id = 1 AND jr.validated = 1 AND jr.published = 1' . 
						' AND jc.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')' .					
						' AND jr.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')' .	
						' GROUP BY jc.id, jc.title, jc.alias, jc.parent_id, jc.level, jc.lft, jc.rgt, jc.language ' .
						' ');
		
		// Get categories
		return $db->loadObjectList();
	}
	
	/**
	 * Method to get cross categories for a given recipe
	 * @return The object list of categories
	 */
	public function getRecipeCategories($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe categories table
		$query->select(	'c.id, c.parent_id, c.lft, c.rgt, c.level, c.path, c.extension, c.title, c.alias, c.note, ' . 
						'c.description, c.published, c.checked_out, c.checked_out_time, c.access, c.params, c.metadesc, ' .
						'c.metakey, c.metadata , c.created_user_id, c.created_time, c.modified_user_id, c.modified_time, c.hits, c.language');
		$query->from('#__categories c');
		
		// Join over categories
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.cat_id = c.id');
		
		// Where clause
		$query->where('cc.recipe_id = ' . $db->quote($pRecipeId));
		$query->where('c.published = 1');
		
		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('c.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}