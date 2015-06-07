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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * YooRecipes Controller
 */
class YooRecipeControllerUnits extends JControllerAdmin
{

	protected $_task;
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	ContentControllerArticles
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('publish',	'unpublish');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Units', $prefix = 'YooRecipeModel', $config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	/**
	 * Method to publish a list of recipes.
	 *
	 * @return	void
	 * @since	1.0
	 */
	function publish()
	{
		$publish;
		if ($this->getTask () == 'publish') {
			$publish = 1;
		}
		else {
			$publish = 0;
		}
		
		$input = JFactory::getApplication()->input;
		$cid = $input->get( 'cid', '', 'ARRAY' );
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_yoorecipe/tables');
		$reviewTable =JTable::getInstance('Unit', 'YooRecipeTable');
		$reviewTable->publish($cid, $publish);

		$this->setRedirect('index.php?option=com_yoorecipe&view=units');
	}
	
	
	function delete() {
	
		$input = JFactory::getApplication()->input;
		$pks	= $input->get('cid', '', 'ARRAY');

		// Get the YooRecipe model.
		$model = $this->getModel('Unit');
		
		// Iterate the items to delete each one.
		$atLeastOneUnitStillUsed = false;
		foreach ($pks as $i => $pk) {
		
			if ($model->isUnitStillInUse($pk)) {
				$atLeastOneUnitStillUsed = true;
			} else {
				$model->deleteUnit($pk);
			}
		}

		if ($atLeastOneUnitStillUsed) {
			JError::raiseWarning(500, JText::_('JERROR_UNIT_STILL_IN_USE'));
		}
		
		// Clear the component's cache
		//$this->cleanCache();

		$this->setRedirect('index.php?option=com_yoorecipe&view=units');
	}
}