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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.application.component.modeladmin');
/**
 * YooRecipeList Model
 */
class YooRecipeModelYooRecipes extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
	
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'r.id',
				'asset_id', 'r.asset_id',
				'access', 'r.access', 'access_level',
				'title', 'r.title',
				'alias', 'r.alias',
				'description', 'r.description',
				'category', 'c.title',
				'created_by', 'r.created_by',
				'author_name', 'r.author_name',
				'preparation', 'r.preparation',
				'servings_type', 'r.servings_type',
				'nb_persons', 'r.nb_persons',
				'difficulty', 'r.difficulty',
				'cost', 'r.cost',
				'creation_date', 'r.creation_date',
				'publish_up', 'r.publish_up',
				'publish_down', 'r.publish_down',
				'preparation_time', 'r.preparation_time',
				'cook_time', 'r.cook_time',
				'wait_time', 'r.wait_time',
				'picture', 'r.picture',
				'featured', 'r.featured',
				'published', 'r.published',
				'validated', 'r.validated',
				'nb_views', 'r.nb_views',
				'note', 'r.note',
				'language','r.language'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		$input 	= JFactory::getApplication()->input;
		if ($layout = $input->get('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$validated = $this->getUserStateFromRequest($this->context.'.filter.validated', 'filter_validated', '');
		$this->setState('filter.validated', $validated);
		
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
		
		$created_by = $this->getUserStateFromRequest($this->context.'.filter.created_by', 'filter_created_by');
		$this->setState('filter.created_by', $created_by);
		
		$access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
		
		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language');
		$this->setState('filter.language', $language);
		
		// List state information.
		parent::populateState('r.title', 'asc');
	}
	
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.language, l.title as language_title'
			)
		);

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->select('ua.email AS author_email');
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		$query->from('#__yoorecipe as r');
		
		// Join over languages
		$query->join('LEFT', '#__languages AS l ON l.lang_code = r.language');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = r.access');
		
		// Join over CROSS categories
		$query->join('LEFT', '#__yoorecipe_categories cc on r.id = cc.recipe_id');
		
		// Join over categories
		$query->join('LEFT', '#__categories c on c.id = cc.cat_id');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('r.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('r.title LIKE '.$search);
			}
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('r.published = ' . (int) $published);
		}
		
		// Filter by validated state
		$validated = $this->getState('filter.validated');
		if (is_numeric($validated)) {
			$query->where('r.validated = ' . (int) $validated);
		}
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('r.access = ' . (int) $access);
		}
		
		// Filter by language.
		if ($language = $this->getState('filter.language')) {
			$query->where('r.language = ' . $db->quote($language));
		}
		
		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('cc.cat_id = '.(int) $categoryId);
		}
		else if (is_array($categoryId)) {
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('r.category_id IN ('.$categoryId.')');
		}
		
		// Filter by author id
		$created_by = $this->getState('filter.created_by');
		if (is_numeric($created_by)) {
			$query->where('r.created_by = '.(int) $created_by);
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'title');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
	
		$query->group('r.id');
		
		return $query;
	}
	
	/**
	 * Method to toggle the featured setting of articles.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_YOORECIPE_NO_ITEM_SELECTED'));
			return false;
		}

		try {
		
			$db = JFactory::getDBO();

			$db->setQuery(
				'UPDATE #__yoorecipe AS r' .
				' SET r.featured = '.(int) $value.
				' WHERE r.id IN ('.implode(',', $pks).')'
			);
			if (!$db->execute()) {
				throw new Exception($db->getErrorMsg());
			}

		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}
}