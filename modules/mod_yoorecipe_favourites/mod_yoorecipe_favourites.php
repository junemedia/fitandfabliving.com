<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Favourites Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2012 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');

require_once(JPATH_ROOT.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php');
require_once(JPATH_ROOT.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php');

// Check if user is logged in
$user	= JFactory::getUser();
if (!$user->guest)
{
	// get the items to display from the helper
	$items = ModYooRecipeFavouritesHelper::getRecipes($params);

	// get ingredients if needed
	if ($params->get('show_ingredients', 1)) :
		foreach ($items as $item) :
			$item->ingredients = ModYooRecipeFavouritesHelper::getIngredientsByRecipeId($item->id);
		endforeach;
	endif;

	// include the template for display
	require(JModuleHelper::getLayoutPath('mod_yoorecipe_favourites'));
}
else 
{
	echo JText::_('MOD_YOORECIPE_FAVOURITES_LOGIN');
}
 
