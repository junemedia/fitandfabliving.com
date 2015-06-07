<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
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
class YooRecipeModelUnits extends JModelList
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
				'id', 'u.id',
				'lang', 'u.lang',
				'code', 'u.code',
				'label', 'u.label',
				'ordering', 'u.ordering',
				'published', 'u.published',
				'creation_date', 'u.creation_date'
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
		
		$code = $this->getUserStateFromRequest($this->context.'.filter.code', 'filter_code', '');
		$this->setState('filter.code', $code);
		
		$lang = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $lang);
		
		// List state information.
		parent::populateState('u.label', 'asc');
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
				'u.id, u.lang, u.code, u.label, u.ordering, u.published, u.creation_date'
			)
		);

		$query->from('#__yoorecipe_units as u');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('u.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('u.label LIKE '.$search.' OR u.code LIKE '.$search);
			}
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('u.published = ' . (int) $published);
		}
		
		$code = $this->getState('filter.code');
		if ($code != '') {
			$query->where('u.code = ' . $db->quote($code));
		}
		
		$lang = $this->getState('filter.language');
		if ($lang != '') {
			$query->where('u.lang = ' . $db->quote($lang));
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'code');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
	
		return $query;
	}
	
	public function getAllPublishedUnitsByLocale($locale) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('u.id, u.lang, u.code, u.label, u.ordering, u.published, u.creation_date');
		$query->from('#__yoorecipe_units as u');
		$query->where('u.published = 1 AND u.lang = ' . $db->quote($locale));
		$query->order('u.label asc');
		
		$db->setQuery((string)$query);
		return $db->loadObjectList();
	}
}