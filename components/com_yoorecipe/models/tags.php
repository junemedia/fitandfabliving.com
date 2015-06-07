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
class YooRecipeModelTags extends JModelList
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
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
 
		$input 		= JFactory::getApplication()->input;
		$tagValue 	= $input->get('value');
		$this->setState('tagValue', $tagValue);
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
		$app				= JFactory::getApplication();
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
				', r.validated, r.featured, r.nb_views, r.note' .
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
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		
		// Join over tags
		$query->join('LEFT', '#__yoorecipe_tags AS t ON t.recipe_id = r.id');
		
		// Prepare where clause
		$query->where('r.published = 1');
		$query->where('r.validated = 1');
		$query->where('t.tag_value = ' . $db->quote($this->getState('tagValue')));
		
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
		
		$query->order('r.title asc');
		$query->group('r.id');
		
		return $query;
	}
	
	/**
	* Method to get tags by his recipe_id
	*/
	public function getTagsByRecipeId($recipeId)
	{
		// Prepare query
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		
		// From the recipe table
		$query->select('tag_id, recipe_id, tag_value');
		$query->from('#__yoorecipe_tags');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery((string)$query);
		return $db->loadObjectList();
	}
	
	/**
	* Method to get tags by his recipe_id
	*/
	public function getTagByTagId($tagId)
	{
		// Prepare query
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		
		// From the recipe table
		$query->select('tag_id, recipe_id, tag_value');
		$query->from('#__yoorecipe_tags');
		$query->where('tag_id = ' . $db->quote($tagId));
		
		$db->setQuery((string)$query);
		return $db->loadObject();
	}
	
	/**
	* Method to get tags by his tag_value
	*/
	public function getTagByTagValue($tagValue)
	{
		// Prepare query
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		
		// From the recipe table
		$query->select('tag_id, recipe_id, tag_value');
		$query->from('#__yoorecipe_tags');
		$query->where('tag_value = ' . $db->quote($tagValue));
		
		$db->setQuery((string)$query);
		return $db->loadObject();
	}
	
	/**
	* Method to insert a new tag given a recipe_id and a tag_value
	*/
	public function insertTag($recipeId, $value)
	{
		// Prepare query
		$db = JFactory::getDBO();	
		$query = $db->getQuery(true);
		
		// Insert tag
		$query->clear();
		$query->insert('#__yoorecipe_tags');
		$query->set('recipe_id = '.$db->quote($recipeId));
		$query->set('tag_value = '.$db->quote($value));
		
		$db->setQuery((string)$query);
		if (!$db->execute()) {
			$this->setError($db->getErrorMsg());
			echo $db->getErrorMsg();
			return false;
		}
	}
	
	/**
	* Method to delete all tags of a given recipe
	*/
	public function deleteTagsByRecipeId($recipeId)
	{
		// Prepare query
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete tag
		$query->clear();
		$query->delete('#__yoorecipe_tags');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery((string)$query);
		$db->execute();
	}
}