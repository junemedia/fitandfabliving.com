<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Categories Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModYooRecipeCategoriesHelper
{

	var $_parent = null;
	
    /**
	 * Get all categories
	 */
	public static function getAllPublishedCategories($root_category, $max_level) {
	
		// Create a new query object.		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// From the recipe category table
		$query->from('#__categories');
		
		// Select some fields
		$query->select('id, title, alias, parent_id, level, lft, rgt, language');
		$query->select('CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug');
		$query->where('extension = ' . $db->quote('com_yoorecipe') . ' and published = 1');
		
		// Filter by language
		if ($app->getLanguageFilter()) {
			$query->where('language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		// Filter by categories
		if ($root_category != '0') {
		
			$idsToInclude = array();
			$subCats = array($root_category);
			for ($i = 0 ; $i < $max_level; $i++) {
		
				$subCats = ModYooRecipeCategoriesHelper::getSubCategory($subCats);
				$idsToInclude = array_merge($idsToInclude, $subCats);
			}
			$query->where('id IN (' .  implode(",", $idsToInclude) .')');
		}
		else {
			$query->where('level <= '. $db->quote($max_level));
		}
		
		$query->order('lft asc');
		$db->setQuery($query);
		
		// Get recipes
		return $db->loadObjectList();
	}
	
	/**
	 * Return the number of published, validated recipes from a given category id
	 * @param category: The c ategory id to look for
	 * @param recursive: Whether or not children categories should be included in count
	 */
	public static function getNbRecipesByCategoryId($categoryId, $recursive = true) {
		
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Get nb recipes
		$query->select('count(jr.id)');
			
		// From the recipe category table
		$query->from('#__yoorecipe jr');
		
		// Join over cross categories
		$query->join('INNER', '#__yoorecipe_categories as cc ON cc.recipe_id = jr.id');
		
		// Join over categories
		$query->join('LEFT', '#__categories jc ON jc.id = cc.recipe_id');
		
		// Prepare where clause
		$query->where('jr.published = 1 AND jr.validated = 1');
		
		$subCategoryIds = self::getAllSubCategories($categoryId, $recursive = true);
		
		if (count($subCategoryIds) > 0) {
			$query->where('cc.cat_id IN (' . $categoryId . ',' . implode(',', $subCategoryIds) . ')');
		} else {
			$query->where('cc.cat_id = ' . $categoryId);
		}
		
		$db->setQuery($query);
		
		// Get recipes
		return $db->loadResult();

	}
	
	/**
	 * Returns all sub categories of category passed in parameter
	 */
	public static function getAllSubCategories($categoryId, $recursive = false) {
	
		$ids = array();
		
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$active = $menu->getActive();
		$params = new JRegistry();

		if ($active) {
			$params->loadString($active->params);
		}

		$options = array();
		$options['module'] = 'mod_yoorecipe_categories';
		$categories = JCategories::getInstance('YooRecipe', $options);
		$_parent = $categories->get($categoryId);

		if (is_object($_parent)) {
			$_items = $_parent->getChildren($recursive);
			
			foreach ($_items as $item) :
				$ids[] = $item->id;
			endforeach;
		}
		else {
			$_items = false;
		}
		
		return $ids;
	}
	
	private static function getSubCategory($arrayId) {
	
		// Create a new query object.		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// From the recipe category table
		$query->select('id');
		$query->from('#__categories');
		$query->where('extension = ' . $db->quote('com_yoorecipe') . ' and published = 1');
		$query->where('(id in (' . implode(",", $arrayId) . ') or  parent_id in (' . implode(",", $arrayId) . '))');
		
		// Filter by language
		if ($app->getLanguageFilter()) {
			$query->where('language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		// Get sub cats
		$db->setQuery($query);
		return $db->loadResultArray();
	}
}