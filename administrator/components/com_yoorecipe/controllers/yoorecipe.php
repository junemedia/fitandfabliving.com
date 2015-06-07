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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * YooRecipe Controller
 */
class YooRecipeControllerYooRecipe extends JControllerForm
{
	/**
	* Cancel recipe edition
	*/
	function cancel ($cachable = false)
	{
		// set default view if not set
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'YooRecipes'));
 
		// call parent behavior
		parent::display($cachable);
	}
	
	/**
	* Save recipe
	*/
	public function save() {
		
		// Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JInvalid_Token'));

        // Initialise variables.
        $task           = $this->getTask();
 
        // The save2copy task needs to be handled slightly differently.
        if ($task == 'save2copy')
        {
			$input = JFactory::getApplication()->input;
			$data = $input->get('jform', '', 'ARRAY');
			$post = $input->get('post', '', 'ARRAY');
		}
 
		parent::save();
	}
}