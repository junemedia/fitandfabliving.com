<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Favourites Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModYooRecipeFavouritesHelper
{
    /**
     * Returns a list of yoorecipe items
     */
    public static function getRecipes($params)
    {
        // get a reference to the database
        $db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		$max_nb_recipes = $params->get('max_nb_recipes', 5);
		$layout 		= $params->get('layout', 'random');
		
		$user	= JFactory::getUser();
		
		// get a list of $max_nb_recipes recipes 
		$query->select( 'r.id, r.access, c.cat_id as cat_id, r.title, r.alias, r.preparation, r.nb_persons, r.difficulty, r.cost, ' .
						'r.creation_date, r.publish_up, r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.featured, ' . 
						'r.picture, r.published, r.nb_views, r.note, r.created_by');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE id END as slug');
		//$query->select('CASE WHEN fr.user_id THEN 1 ELSE 0 END as favourite');

		// From the recipe table
		$query->from('#__yoorecipe r');
	
		// Join over cross categories
		$query->join('LEFT', '#__yoorecipe_categories c on c.recipe_id = r.id');
		
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites fr on fr.recipe_id = r.id');
		
		// Prepare where clause
		$query->where('r.published = 1');
		$query->where('r.validated = 1');
		$query->where('fr.user_id = ' . $db->quote($user->id));
		
		// Filter by access level.
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$query->where('r.access IN ('.$groups.')');
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSql());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		$query->order('r.title limit ' . $max_nb_recipes);
		$query->group('r.id');
		
		$db->setQuery($query);
        $items = ($items = $db->loadObjectList())?$items:array();
 
        return $items;
    }
	
	/**
     * Returns a list of yoorecipe ingredients
	 */
	public static function getIngredientsByRecipeId($id)
	{
		// get a reference to the database
        $db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// from the recipe ingredients table
		$query->select('id, recipe_id, quantity, unit, description');
		$query->from('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($id));

		$db->setQuery($query);
        $ingredients = ($ingredients = $db->loadObjectList())?$ingredients:array();
 
        return $ingredients;
    }
}