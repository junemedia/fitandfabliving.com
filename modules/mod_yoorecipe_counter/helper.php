<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Counter Module
# ----------------------------------------------------------------------
# Copyright (C) 2012 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModYooRecipeCounterHelper
{
    /**
	 * Get all categories
	 */
	public static function getNbPublishedRecipes() {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Select some fields
		$query->select('count(*)');
		
		// From the recipe table
		$query->from('#__yoorecipe r');
		
		// Filter published and validated recipes
		$query->where('r.published = 1 and r.validated = 1');
		
		$db->setQuery($query);
		
		// Get number of active recipes
		return $db->loadResult();
	}
}