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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 /**
 * YooRecipes View
 */
class YooRecipeViewUnits extends JViewLegacy
{
	/**
	 * YooRecipes view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
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
		
		// Get Joomla version
		$version = new JVersion;
		$joomla = $version->getShortVersion(); 
		
		// Set the toolbar
		$this->addToolBar($joomla);
		
		// Add sidebar
		if (version_compare($joomla, '3.0', '>=')) {
			$this->sidebar = JHtmlSidebar::render();
			$tpl = "j3";
		}
		
		// Load the category helper.
		YooRecipeHelper::addSubmenu('units');
		
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar($joomla) 
	{
		JToolBarHelper::title(JText::_('COM_YOORECIPE_MANAGER_YOORECIPE_UNITS'), 'yoorecipe');
		
		JToolBarHelper::addNew('unit.add');
		if (count($this->items) > 0) {
			
			JToolBarHelper::editList('unit.edit');
			JToolBarHelper::divider();
			JToolBarHelper::publish('units.publish');
			JToolBarHelper::unpublish('units.unpublish');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'units.delete');
		}
		JToolBarHelper::preferences('com_yoorecipe');
		
		if (version_compare($joomla, '3.0', '>=')) {
		
			JHtmlSidebar::setAction('index.php?option=com_yoorecipe&view=units');
			
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_LANGUAGE'),
				'filter_language',
				JHtml::_('select.options', JHtml::_('yoorecipeadminutils.unitsLanguages'), 'value', 'text', $this->state->get('filter.language'))
			);
			
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_UNIT_CODE'),
				'filter_code',
				JHtml::_('select.options', JHtml::_('yoorecipeadminutils.unitsOptions'), 'value', 'text', $this->state->get('filter.code'))
			);
			
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('yoorecipeadminutils.j3_publishedOptions'), 'value', 'text', $this->state->get('filter.published'))
			);
		}
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
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'u.id' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_ID'),
			'u.lang' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_LANG'),
			'u.code' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_CODE'),
			'u.label' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_LABEL'),
			'u.published' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_PUBLISHED'),
			'u.creation_date' => JText::_('COM_YOORECIPE_YOORECIPE_HEADING_CREATION_DATE'),
		);
	}
}