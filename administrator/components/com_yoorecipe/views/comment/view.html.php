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
 * YooRecipe View
 */
class YooRecipeViewComment extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;
	
	/**
	 * display method of YooRecipe view
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// Get the Data
		$form 	= $this->get('Form');
		$item 	= $this->get('Item');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Get Joomla version
		$version = new JVersion;
		$joomla = $version->getShortVersion(); 
		
		// Add sidebar
		if (version_compare($joomla, '3.0', '>=')) {
			$tpl = "j3";
		}
		
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		
		// Set the toolbar
		$this->addToolBar();
 
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
		$input 	= JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('COM_YOORECIPE_MANAGER_COMMENT_NEW') : JText::_('COM_YOORECIPE_MANAGER_COMMENT_EDIT'), 'yoorecipe');
		JToolBarHelper::apply('comment.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('comment.save');
		JToolBarHelper::cancel('comment.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_YOORECIPE_MANAGER_COMMENT_NEW') : JText::_('COM_YOORECIPE_MANAGER_COMMENT_EDIT'));
	}
}