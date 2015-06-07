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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * YooRecipes View
 */
class YooRecipeViewImpex extends JViewLegacy
{
	/**
	 * YooRecipes view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get recipe model
		$modelYooRecipe	= JModelLegacy::getInstance('yoorecipe','YooRecipeModel');
		
		// Get data from the model
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state		= $this->get('State');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Get cross categories
		foreach ($this->items as $recipe ) :
			$recipe->categories = $modelYooRecipe->getRecipeCategories($recipe->id);
		endforeach;
 
		// Set the toolbar
		$this->addToolBar();
		// Load the category helper.
		YooRecipeHelper::addSubmenu('impex');
		
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JToolBarHelper::title(JText::_('COM_YOORECIPE_IMPEX_MANAGER_YOORECIPES'), 'yoorecipe');
	
		JToolBarHelper::custom('impex.import', 'publish.png', 'publish.png', 'COM_YOORECIPE_IMPORT', true);
		if (count($this->items) > 0) {
			
			JToolBarHelper::custom('impex.export', 'publish.png', 'publish.png', 'COM_YOORECIPE_EXPORT', true);
			JToolBarHelper::divider();
		}
		JToolBarHelper::preferences('com_yoorecipe');
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_YOORECIPE_ADMINISTRATION'));
	}
}