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
	/*public function __construct($config = array())
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
	}*/
	
	
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