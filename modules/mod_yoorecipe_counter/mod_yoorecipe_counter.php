<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Counter Module
# ----------------------------------------------------------------------
# Copyright (C) 2012 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');
 
// get the items to display from the helper
$nb_recipes = ModYooRecipeCounterHelper::getNbPublishedRecipes();

// include the template for display
require(JModuleHelper::getLayoutPath('mod_yoorecipe_counter'));
