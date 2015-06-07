<?php
/**
 * @version		$Id: view.html.php 21023 2011-03-28 10:55:01Z infograf768 $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML Article View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class YooRecipeViewForm extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null) 
	{
		// Initialise variables.
		$input 		= JFactory::getApplication()->input;
		$user		= JFactory::getUser();
		$lang 		= JFactory::getLanguage();
		$itemId 	= $input->get('id');
		
		// Get models
		$model 				= JModelLegacy::getInstance('form','YooRecipeModel');
		$unitsModel			= JModelLegacy::getInstance('units','YooRecipeModel');
		$tagsModel			= JModelLegacy::getInstance('tags','YooRecipeModel');
		$ingredientsModel	= JModelLegacy::getInstance('ingredients','YooRecipeModel');
		$seasonsModel		= JModelLegacy::getInstance('seasons','YooRecipeModel');
		
		// get Data
		$form 				= $this->get('Form');
		$item 				= $model->getItem($itemId);
		$ingredients 		= $model->getRecipeIngredients($item->id);
		$item->category_id 	= $model->getRecipeCategoriesIds($item->id);	
		$item->season_id 	= $seasonsModel->getRecipeSeasonsIds($item->id);
		$groups				= $ingredientsModel->getListOfIngredientsGroups();
		$units 				= $unitsModel->getAllPublishedUnitsByLocale($lang->getTag());
		$tags				= $tagsModel->getTagsByRecipeId($item->id);
		
		// Calculate authorisations
		if (empty($item->id)) {
			$authorised = $user->authorise('core.create', 'com_yoorecipe');
		}
		else {
			$authorised = $user->authorise('core.admin', 'com_yoorecipe') || $user->authorise('core.edit', 'com_yoorecipe') || ($user->authorise('core.edit.own', 'com_yoorecipe') && $item->created_by == $user->id);
		}

		if ($authorised !== true) {
		
			if ($user->guest == 1) {
				$app = JFactory::getApplication();
				$returnUrl		= base64_encode(JUri::getInstance()); 
				$redirectUrl 	= JRoute::_('index.php?option=com_users&view=login&return='.$returnUrl);
				$app->redirect($redirectUrl);
				
				return;
			}
			
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
 
		// Authorizations ok, let's retrieve ingredients
		$ingredients = null;
		if (!empty($item)) {
			$ingredients = $model->getRecipeIngredients($item->id);
			$form->bind($item);
		}
		
		$tags = null;
		if (!empty($item)) {
			$tags = $tagsModel->getTagsByRecipeId($item->id);
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		$this->form 		= $form;
		$this->item 		= $item;
		$this->ingredients 	= $ingredients;
		$this->units 		= $units;
		$this->tags		 	= $tags;
		$this->groups		= $groups;
		
		// Prepare document
		$this->_prepareDocument();
		
		// Get Joomla version
		$version = new JVersion;
		$joomla = $version->getShortVersion(); 
		
		$is_joomla3 = version_compare($joomla, '3.0', '>=') ? true:false;
		
		if ($is_joomla3) {
			$tpl = "j3";
		}
		
		// Display the template
		parent::display($tpl); 
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu 		= $menus->getActive();
		if ($this->item) {
			// User comes from recipe page: he is editing
			$this->document->setTitle($this->item->title . ' - ' . JText::_('COM_YOORECIPE_EDITION'));
			$this->document->setDescription(strip_tags($this->item->description));
		} 
		else if ($menu)
		{
			// User comes from menu
			$menuParams = $menu->params;
			$menuParams->def('page_heading', $menuParams->get('page_title', $menu->title));
			$title = $menuParams->get('page_title', '');
			if (!empty($title)) {
				$this->document->setTitle($title);
			}
			
			if ($menuParams->get('menu-meta_description'))
			{
				$this->document->setDescription($menuParams->get('menu-meta_description'));
			}

			if ($menuParams->get('menu-meta_keywords')) 
			{
				$this->document->setMetadata('keywords', $menuParams->get('menu-meta_keywords'));
			}
			
			if ($menuParams->get('robots')) 
			{
				$this->document->setMetadata('robots', $menuParams->get('robots'));
			}
		}
	}
}
