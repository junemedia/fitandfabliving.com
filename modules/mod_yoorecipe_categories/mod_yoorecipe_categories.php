<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Categories Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');
require_once(JPATH_ROOT.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php');
require_once JPATH_SITE.'/components/com_yoorecipe/models/categories.php';

// get the items to display from the helper
$max_level		= $params->get('max_level', 3);
$root_category 	= $params->get('root_category');
$categories 	= ModYooRecipeCategoriesHelper::getAllPublishedCategories($root_category, $max_level);

// get number of active recipes for each category
foreach ($categories as $category) :
	$category->nb_recipes = ModYooRecipeCategoriesHelper::getNbRecipesByCategoryId($category->id, true);
endforeach;

// include the template for display
require(JModuleHelper::getLayoutPath('mod_yoorecipe_categories'));