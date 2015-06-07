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
class YooRecipeViewCategories extends JViewLegacy
{
	
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app				= JFactory::getApplication();
		$menu				= $app->getMenu();
		$active 			= $menu->getActive();
		$lang 				= JFactory::getLanguage();
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		
		// Get the yoorecipe model
		$categoriesModel 	= JModelLegacy::getInstance('categories','YooRecipeModel');
		$mainModel 			= JModelLegacy::getInstance('yoorecipe','YooRecipeModel');
		$tagsModel			= JModelLegacy::getInstance('tags','YooRecipeModel');
		$seasonsModel		= JModelLegacy::getInstance('seasons','YooRecipeModel');
		
		// get recipe identifier to view
		$input 		= JFactory::getApplication()->input;
		$categoryId = $input->get('id', '', 'STRING');

		// Assign all recipes to the view
		$this->user				= JFactory::getUser();
		$this->items 			= $this->get('Items');
		$this->pagination 		= $this->get('Pagination');
		$this->category 		= $categoriesModel->getCategoryById($categoryId);
		
		if ($yooRecipeparams->get('show_subcategories', 1)) {
		
			$this->subcategories	= $categoriesModel->getCategoriesByParentId($categoryId);
			
			// Get number of published recipes for each subcategory
			foreach ($this->subcategories as $category) :
				$category->nb_recipes = $categoriesModel->getNbRecipesByCategoryId($category->id, $recursive = true);
			endforeach;
		}
		
		// Set Params defined in menu (if applicable)
		if(isset($active)) {
			$this->menuParams 	= $active->params;
		}
		else {
			$this->menuParams = null;
			
			// Add breadcrumbs
			JHTML::_('behavior.modal');
			$app = JFactory::getApplication();
			$pathway = $app->getPathway();
			$pathway->addItem($this->category->title, JUri::current());
		}
				
		// Get recipe's ingredients and ratings
		foreach ($this->items as $recipe)
		{
			$recipe->ingredients = $mainModel->getIngredientsByRecipeId($recipe->id, $lang->getTag());
			$recipe->ratings	 = $mainModel->getRatingsByRecipeIdOrderedByDateDesc($recipe->id);
			$recipe->categories	 = $categoriesModel->getRecipeCategories($recipe->id);
			$recipe->tags		 = $tagsModel->getTagsByRecipeId($recipe->id);
			$recipe->season_id	 = $seasonsModel->getRecipeSeasonsIds($recipe->id);

			// Calculate authorisations
			$recipe->canEdit		= $this->user->guest != 1 && ($this->user->authorise('core.edit', 'com_yoorecipe') || ($this->user->authorise('core.edit.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id)) ;
			$recipe->canDelete	 	= $this->user->guest != 1 && ($this->user->authorise('core.delete', 'com_yoorecipe') || ($this->user->authorise('core.delete.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id));
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// In case no recipes found, get categories instead
		if (count($this->items) == 0) {
			$this->categories 	= $this->get('AllPublishedCategories');
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
		$params	= JFactory::getApplication()->getParams();
		$menu	= $menus->getActive();
		
		$title = $params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		$this->document->setTitle($title);

		if ($this->category->metadesc) {
			$this->document->setDescription($this->category->metadesc);
		}
		elseif (!$this->category->metadesc && $params->get('menu-meta_description')) {
			$this->document->setDescription($params->get('menu-meta_description'));
		}

		if ($this->category->metakey) {
			$this->document->setMetadata('keywords', $this->category->metakey);
		}
		elseif (!$this->category->metakey && $params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}
		
		if ($params->get('robots')) {
			$this->document->setMetadata('robots', $params->get('robots'));
		}
	}
}