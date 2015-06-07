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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * YooRecipe Controller
 */
class YooRecipeControllerUnit extends JControllerForm
{
	/**
	* Delete potentially added ingredients
	*/
	function cancel ($cachable = false)
	{	
		// set default view if not set
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'units'));
 
		// call parent behavior
		parent::display($cachable);
	}
	
	function save($key = null, $urlVar = null) {
		
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
						
			// copy ingredients
			// get cross cats
		}
 
		parent::save();
	}
}