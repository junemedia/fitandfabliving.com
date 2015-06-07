<?php
/**
 * @version             $Id$
 * @copyright           Copyright (C) 2009 - 2012 YooRock! All rights reserved.
 * @license             GNU General Public License version 2 or later; see LICENSE.txt
 * @author              YooRock (support@yoorock.fr)
 */
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php';

/**
 * Handles YooRecipe Categories and recipes
 *
 * This plugin is able to list the categories and recipes according 
 * to the menu settings and the user session data (user state).
 *
 * This is quite a complex plugin, if you are trying to build your own plugin
 * for other component, I suggest you to take a look to another plugis as
 * they are usually most simple. ;)
 */
class xmap_com_yoorecipe
{
    /**
     * This function is called before a menu item is printed. We use it to set the
     * proper uniqueid for the item
     *
     * @param object  Menu item to be "prepared"
     * @param array   The extension params
     *
     * @return void
     * @since  1.2
     */
    static function prepareMenuItem($node, &$params)
    {
    }

    /**
     * Expands a com_yoorecipe category item
     *
     * @return void
     * @since  1.0
     */
    static function getTree($xmap, $parent, &$params)
    {
        if ($xmap->isNews) // This component does not provide news content. don't waste time/resources
            return false;

        // Create a new query object.		
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// From the recipe category table
		$query->from('#__categories');
		
		// Select some fields
		$query->select('id, title, alias, parent_id, level, lft, rgt, language');
		$query->select('CASE WHEN CHARACTER_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug');
		
		$query->where('extension = ' . $db->quote('com_yoorecipe') . ' and published = 1'); 
		$query->order('lft asc');
		
		$db->setQuery($query);
		
		// Get categories
        $rows = $db->loadObjectList();

		// Take care of special values for frequency and priority
		$priority 		= $params['cat_priority'];
		$changefreq 	= $params['cat_changefreq'];
		if ($priority  == '-1')
			$priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;
			
        $modified = time();
        $xmap->changeLevel(1);
		
        foreach($rows as $row) {
            $node 				= new stdclass;
            $node->id 			= $parent->id;
            $node->uid 			= 'com_yoorecipec'.$row->id; // Unique ID
            $node->browserNav 	= $parent->browserNav;
            $node->name 		= html_entity_decode($row->title);
            $node->modified 	= $modified;
			$node->link 		= JHtmlYooRecipeHelperRoute::getCategoryRoute($row->slug).'&Itemid='.$parent->id;
            $node->priority 	= $priority;
            $node->changefreq 	= $changefreq;
            $node->expandible 	= false;
            $node->secure 		= $parent->secure;
			$xmap->printNode($node);
        }
		
		// Generate map for recipes
		xmap_com_yoorecipe::getRecipesTree($xmap, $parent, null, $params, null);
    }

    /**
     * Get all recipe items
     * Returns an array of all contained content items.
     *
     * @param object  $xmap
     * @param object  $parent   the menu item
     * @param int     $catid    the id of the category to be expanded -- not used
     * @param array   $params   an assoc array with the params for this plugin on Xmap
     * @param int     $itemid   the itemid to use for this category's children -- not used
     */
    static function getRecipesTree($xmap, $parent, $catid, &$params, $itemid)
    {
		$link_query = parse_url($parent->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);

		// Get recipes
        $db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select( 'r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.metadata, r.metakey');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->from('#__yoorecipe as r');
		$query->where('published = 1 and validated = 1');
		
		$db->setQuery($query);
        $rows = $db->loadObjectList();

		// Take care of special values for frequency and priority
		$priority 		= $params['recipe_priority'];
		$changefreq 	= $params['recipe_changefreq'];
		if ($priority  == '-1')
			$priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;
			
        $modified = time();
        $xmap->changeLevel(1);
		
        foreach($rows as $row) {
            $node 				= new stdclass;
            $node->id 			= $parent->id;
            $node->uid 			= 'com_yooreciper'.$row->id; // Unique ID
            $node->browserNav 	= $parent->browserNav;
            $node->name 		= html_entity_decode($row->title);
            $node->modified		= $modified;
			$node->link 		= JHtmlYooRecipeHelperRoute::getRecipeRoute($row->slug).'&Itemid='.$parent->id;
            $node->priority 	= $priority;
            $node->changefreq 	= $changefreq;
            $node->expandible 	= false;
            $node->secure 		= $parent->secure;
            $xmap->printNode($node);
        }
		
        $xmap->changeLevel(-1);
    }
}