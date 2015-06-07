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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * YooRecipes Controller
 */
class YooRecipeControllerImpex extends JControllerAdmin
{

	protected $_task;
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	YooRecipeControllerImpex
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Impex', $prefix = 'YooRecipeModel', $config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	/**
	 * Method to export recipes
	 *
	 * @return	void
	 * @since	1.6
	 */
	function export()
	{
		// Check for request forgeries and load helper
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$input	= JFactory::getApplication()->input;
		$ids	= $input->get('cid', '', 'ARRAY');
		$value	= 1;

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel('YooRecipes');
			JHtml::_('impexutils.recipes2XML', $ids);
		}

		//$this->setRedirect('index.php?option=com_yoorecipe&view=impex');
	}
	
	/**
	 * Method to import recipes
	 *
	 * @return	void
	 * @since	1.6
	 */
	function import()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$recipes = JHtml::_('impexutils.XML2recipes');
		
		//$this->setRedirect('index.php?option=com_yoorecipe&view=impex');
	}
}