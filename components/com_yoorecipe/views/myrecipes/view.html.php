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
 * HTML View class for the YooRecipe Component
 */
class YooRecipeViewMyRecipes extends JViewLegacy
{

	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app				= JFactory::getApplication();
		$menu				= $app->getMenu();
		$active 			= $menu->getActive();
		$user				= JFactory::getUser();
		$lang 				= JFactory::getLanguage();
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		
		// Get the yoorecipe model
		$mainModel			= JModelLegacy::getInstance('yoorecipe','YooRecipeModel');
		$categoriesModel	= JModelLegacy::getInstance('categories','YooRecipeModel');
		$tagsModel			= JModelLegacy::getInstance('tags','YooRecipeModel');
		$seasonsModel		= JModelLegacy::getInstance('seasons','YooRecipeModel');
		
		// Check user logged in
		if ($user->guest == 1) {
			$app 			= JFactory::getApplication();
			$returnUrl		= base64_encode(JUri::current());  
			$redirectUrl 	= JRoute::_('index.php?option=com_users&view=login&return='.$returnUrl);
			$app->redirect($redirectUrl);
		}
		
		// Assign all recipes to the view
		$this->user			= $user;
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		
		// Get recipe's ingredients
		foreach ($this->items as $recipe)
		{
			$recipe->ingredients 	= $mainModel->getIngredientsByRecipeId($recipe->id, $lang->getTag());
			$recipe->ratings 		= $mainModel->getRatingsByRecipeIdOrderedByDateDesc($recipe->id);
			$recipe->categories		= $categoriesModel->getRecipeCategories($recipe->id);
			$recipe->tags		 	= $tagsModel->getTagsByRecipeId($recipe->id);
			$recipe->season_id	 	= $seasonsModel->getRecipeSeasonsIds($recipe->id);
			
			// Calculate authorisations
			$recipe->canEdit		= $user->authorise('core.admin', 'com_yoorecipe') || ($this->user->guest != 1 && ($this->user->authorise('core.edit', 'com_yoorecipe') || ($this->user->authorise('core.edit.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id)));
			$recipe->canDelete	 	= $user->authorise('core.admin', 'com_yoorecipe') || ($this->user->guest != 1 && ($this->user->authorise('core.delete', 'com_yoorecipe') || ($this->user->authorise('core.delete.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id)));
		}
		
		// Set Params defined in menu (if applicable)
		$this->menuParams = (isset($active)) ? $active->params : null;
		
		// In case recipe not found, get categories instead
		$this->categories	= $categoriesModel->getAllPublishedCategories();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Prepare document
		$this->_prepareDocument();
		
		// Display the view
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
		if ($menu)
		{
			$menuParams = $menu->params;
			$menuParams->def('page_heading', $menuParams->get('page_title', $menu->title));
			$title = $menuParams->get('page_title', '');
			if (!empty($title)) {
				$this->document->setTitle($title);
			}
			
			if ($menuParams->get('menu-meta_description')) {
				$this->document->setDescription($menuParams->get('menu-meta_description'));
			}

			if ($menuParams->get('menu-meta_keywords')) {
				$this->document->setMetadata('keywords', $menuParams->get('menu-meta_keywords'));
			}
			
			if ($menuParams->get('robots')) {
				$this->document->setMetadata('robots', $menuParams->get('robots'));
			}
		}
	}
}