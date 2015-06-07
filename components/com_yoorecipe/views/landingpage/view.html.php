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
class YooRecipeViewLandingPage extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app				= JFactory::getApplication();
		$menu				= $app->getMenu();
		$active 			= $menu->getActive();
		$lang 				= JFactory::getLanguage();
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		
		// Set Params defined in menu (if applicable)
		$this->menuParams = (isset($active)) ? $active->params : null;
		
		// Get models
		$model 				= $this->getModel('landingpage');
		$mainModel 			= JModelLegacy::getInstance('yoorecipe','YooRecipeModel');
		$categoriesModel 	= JModelLegacy::getInstance('categories','YooRecipeModel');
		$tagsModel			= JModelLegacy::getInstance('tags','YooRecipeModel');
		$seasonsModel		= JModelLegacy::getInstance('seasons','YooRecipeModel');

		// Get A to Z recipes by letter
		$this->recipeStartLetters = $model->getAtoZRecipes('');

		// Get All sub Categories of level 1
		$this->subcategories = $categoriesModel->getCategoriesByParentId(1);
		
		// Get number of published recipes for each subcategory
		foreach ($this->subcategories as $category) :
			$category->nb_recipes = $categoriesModel->getNbRecipesByCategoryId($category->id, $recursive = true);
		endforeach;
		
		// Get user
		$this->user			= JFactory::getUser();
		
		// Assign elements to the view
		if ($this->getLayout() == 'letters') {
			
			$input 	= JFactory::getApplication()->input;
			$letter = $input->get('l', '', 'STRING');
			$this->items 		= $mainModel->getRecipesByLetter($letter);
			$this->crtLetter	= $letter == 'dash' ? JText::_('COM_YOORECIPE_A_NUMBER') : strtoupper($letter);
			
		} else {
			
			$modelName 			= $this->menuParams->get('recipe_types', 'mostpopular');
			$recipeModel 		= JModelLegacy::getInstance($modelName,'YooRecipeModel');
			
			$this->items 		= $recipeModel->getItems();
			$this->pagination 	= $recipeModel->getPagination();
			
			if ($modelName == 'featured') {
				$this->sectionLabel = 'COM_YOORECIPE_LANDINGPAGE_FEATURED';
			} else if ($modelName == 'mostpopular') {
				$this->sectionLabel = 'COM_YOORECIPE_LANDINGPAGE_MOSTPOPULAR';
			} else if ($modelName == 'mostread') {
				$this->sectionLabel = 'COM_YOORECIPE_LANDINGPAGE_MOSTREAD';
			} else if ($modelName == 'mostrecents') {
				$this->sectionLabel = 'COM_YOORECIPE_LANDINGPAGE_MOSTRECENTS';
			} else {
				$this->sectionLabel = '';
			}
		}
		
		// Get recipe's ingredients
		if ($this->items) {
		
			foreach ($this->items as $recipe)
			{
				$recipe->ingredients 	= $mainModel->getIngredientsByRecipeId($recipe->id, $lang->getTag());
				$recipe->ratings 		= $mainModel->getRatingsByRecipeIdOrderedByDateDesc($recipe->id);
				$recipe->categories		= $categoriesModel->getRecipeCategories($recipe->id);
				$recipe->tags		 	= $tagsModel->getTagsByRecipeId($recipe->id);
				$recipe->season_id	 	= $seasonsModel->getRecipeSeasonsIds($recipe->id);
				
				// Calculate authorisations
				$recipe->canEdit		= $this->user->authorise('core.admin', 'com_yoorecipe') || ($this->user->guest != 1 && ($this->user->authorise('core.edit', 'com_yoorecipe') || ($this->user->authorise('core.edit.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id))) ;
				$recipe->canDelete	 	= $this->user->authorise('core.admin', 'com_yoorecipe') || ($this->user->guest != 1 && ($this->user->authorise('core.delete', 'com_yoorecipe') || ($this->user->authorise('core.delete.own', 'com_yoorecipe') && $recipe->created_by == $this->user->id)));
			}
		}
		
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
		
		// Add breadcrumbs
		JHTML::_('behavior.modal');
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		
		if ($this->getLayout() == 'letters') {
			$pathway->addItem(JText::sprintf('COM_YOORECIPE_YOORECIPE_START_WITH', $this->crtLetter), JUri::current());
		}
		
	}
}