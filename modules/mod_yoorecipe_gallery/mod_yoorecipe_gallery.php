<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Gallery Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');
 
// get the items to display from the helper
$items = ModYooRecipeGalleryHelper::getRecipes($params);

// Loop over items
foreach ($items as $item) :

	// get ingredients if needed
	if ($params->get('show_ingredients', 1)) :
		$item->ingredients = ModYooRecipeGalleryHelper::getIngredientsByRecipeId($item->id);
	endif;
	
	// get short title 
	if (strlen($item->title) > $params->get('recipe_title_max_length', 20)) {
		$item->shortTitle = substr (htmlspecialchars($item->title), 0, $params->get('recipe_title_max_length', 20)) . '...';
	}
	else {
		$item->shortTitle = htmlspecialchars($item->title);
	}
	
	// get recipe's picture path
	if ($item->picture != '') {
		$item->picturePath = $item->picture;
	} else {
		$item->picturePath = 'media/com_yoorecipe/images/no-image.jpg';
	}
endforeach;


 
// include the template for display
require(JModuleHelper::getLayoutPath('mod_yoorecipe_gallery'));
