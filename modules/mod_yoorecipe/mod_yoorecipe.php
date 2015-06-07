<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');
require_once(JPATH_ROOT.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php');
require_once(JPATH_ROOT.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php');
 
// get the items to display from the helper
$items = ModYooRecipeHelper::getRecipes($params);

// get ingredients if needed
if ($params->get('show_ingredients', 1)) :
	foreach ($items as $item) :
		$item->ingredients = ModYooRecipeHelper::getIngredientsByRecipeId($item->id);
	endforeach;
endif;
 
// include the template for display
require(JModuleHelper::getLayoutPath('mod_yoorecipe'));