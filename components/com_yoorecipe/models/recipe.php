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
 
// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * YooRecipe Model
 */
class YooRecipeModelRecipe extends JModelList
{

	function __construct()
	{
		parent::__construct();

		$app 	= JFactory::getApplication();
		$menu 	= $app->getMenu();
		$active = $menu->getActive();
		$params = new JRegistry();
		
		if ($active) {
			$params->loadString($active->params);
		}
		
		// List state information
		$input 		= JFactory::getApplication()->input;
		$recipeId 	= $input->get('id');
		$this->setState('recipeId', $recipeId);
	}

}