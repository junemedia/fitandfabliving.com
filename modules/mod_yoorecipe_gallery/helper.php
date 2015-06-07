<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Gallery Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModYooRecipeGalleryHelper
{
    /**
     * Returns a list of yoorecipe items
     */
    public static function getRecipes($params)
    {
        // get a reference to the database
        $db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$app 	= JFactory::getApplication();

		// get a list of $max_nb_recipes recipes 
		$query->select( 'r.id, r.access, c.cat_id, r.title, r.alias, r.preparation, r.nb_persons, r.difficulty, r.cost, ' .
						'r.creation_date, r.publish_up, r.publish_down, r.preparation_time, r.cook_time, r.wait_time, ' .
						'r.featured, r.picture, r.published, r.nb_views, r.note');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE id END as slug');
		
		// From the recipe table
		$query->from('#__yoorecipe r');
		
		// Join over cross categories
		$query->join('LEFT', '#__yoorecipe_categories c on c.recipe_id = r.id');
		
		// Prepare where clause
		$query->where('r.published = 1');
		$query->where('r.validated = 1');
		
		// Filter by language
		if ($app->getLanguageFilter()) {
			$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		// Filter by access level.
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$query->where('r.access IN ('.$groups.')');
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSql());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		// Filter by category if needed
		if ($params->get('category') != 0) {
			$query->where('c.cat_id = ' . $db->quote($params->get('category')));
		}
		
		// Filter pictureless recipes if needed
		if ($params->get('show_recipes_without_pictures', 0) != 1) {
			$query->where('r.picture != ' . $db->quote(''));
		}
		
		// Filter pictureless recipes if needed
		if ($params->get('only_featured_recipes', 0)) {
			$query->where('r.featured = ' . $db->quote('1'));
		}
		
		// Get random elements
		$order_by = $params->get('order_by', 'none');
		if ($order_by == 'none') {
			$query->order('rand() limit ' . $params->get('max_nb_recipes', 5));
		} else if ($order_by == 'most_viewed') {
			$query->order('r.nb_views desc limit ' . $params->get('max_nb_recipes', 5));
		} else if ($order_by == 'best_rated') {
			$query->order('r.note desc limit ' . $params->get('max_nb_recipes', 5));
		} else if ($order_by == 'most_recent') {
			$query->order('r.creation_date desc limit ' . $params->get('max_nb_recipes', 5));
		}

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
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// from the recipe ingredients table
		$query->select('id, recipe_id, quantity, unit, description, price, ordering');
		$query->from('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($id));
		$query->order('ordering asc');
		
		$db->setQuery($query);
        $ingredients = ($ingredients = $db->loadObjectList())?$ingredients:array();
 
        return $ingredients;
    }
}