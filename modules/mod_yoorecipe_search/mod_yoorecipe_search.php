<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Search Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');

$input = JFactory::getApplication()->input;

// perform operations
 if ($params->get('show_search_categories')) : 
	$categories = ModYooRecipeSearchHelper::getCategories();
	$searchCategories = $input->get('searchCategories', array(), '', 'ARRAY');
endif;

// Get recipe authors list
$authors = ModYooRecipeSearchHelper::getAuthors();

// form values for field init
$searchword 		= $input->get('searchword','', 'TEXT');
$withIngredients 	= $input->get('withIngredients', array(), '', 'ARRAY');
$searchCategories 	= $input->get('searchCategories', array(), '', 'ARRAY');
$searchPrice		= $input->get('search_price','', 'TEXT');

// include the template for display
require(JModuleHelper::getLayoutPath('mod_yoorecipe_search', $params->get('layout', 'default')));
