<?php
/*------------------------------------------------------------------------
# plg_search_yoorecipe
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2013 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
	
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php';

/**
 * YooRecipe Search plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.yoorecipe
 * @since       1.6
 */
class plgSearchYooRecipe extends JPlugin
{

	/**
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'yoorecipe' => 'PLG_SEARCH_RECIPES'
		);
		return $areas;
	}
	
	/**
	 * Content Search method
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	public function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDBO();
		$user   = JFactory::getUser(); 
		
		// Load yoorecipe language files
		$lang 		= JFactory::getLanguage();
		$lang->load('plg_search_yoorecipe', JPATH_ADMINISTRATOR, $lang->getTag(), $reload = true);
		
		$searchText = $text;
		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		// Now define the parameters like this:
		$limit     = $this->params->def('search_limit', 50);
		
		// Use the function trim to delete spaces in front of or at the back of the searching terms
		$text = trim( $text );
		
		// Return Array when nothing was filled in.
		if ($text == '') {
			return array();
		}

		// Build query
		$query = $this->buildQuery($text, $phrase, $ordering);
		
		// Set query
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();
		
		// The 'output' of the displayed link
		foreach($rows as $key => $row) {
			$rows[$key]->href = JHtmlYooRecipeHelperRoute::getRecipeRoute($row->id);
		}
		
		// Return the search results in an array
		return $rows;
	}

	/**
	* buildQuery: Build SQL Search query
	*/
	function buildQuery($text, $phrase='', $ordering='') {

		$user 	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		
		$query 	= $db->getQuery(true);
		$searchYooRecipe = JText::_('YooRecipe');
		
		// Get recipe fields (href, title, section, created, text, browsernav)
		$query->select(' distinct r.id, r.title AS title, r.description AS text, r.creation_date AS created, c.title AS section, "1" AS browsernav');
				
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		
		$query->from('#__yoorecipe as r');
		
		// Join over ingredients
		$query->join('INNER', '#__yoorecipe_ingredients as i on i.recipe_id = r.id');
		
		// Join over cross categories
		$query->join('INNER', '#__yoorecipe_categories as cc on cc.recipe_id = r.id');
		
		// Join over categories
		$query->join('INNER', '#__categories as c on c.id = cc.cat_id');
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
		
		// Prepare where clause
		$whereClause = 'r.published = 1 AND r.validated = 1 AND ';

		// Filter by title
		switch ($phrase) {
			
			// search exact
			case 'exact':
				$text           = $db->Quote( '%'.$db->escape( $text, true ).'%', false );
				$wheres2        = array();
				$wheres2[]      = 'LOWER(r.title) LIKE '.$text;
				
				if ($yooRecipeparams->get('include_search_on_description', 1)) {
					$wheres2[] = 'LOWER(r.description) like '.$text;
				}
				
				if ($yooRecipeparams->get('include_search_on_preparation', 1)) {
					$wheres2[] = 'LOWER(r.preparation) like '.$text;
				}
				
				if ($yooRecipeparams->get('include_search_on_tags', 1)) {
					$wheres2[] = 'LOWER(t.tag_value) like '.$text;
				}
				$whereClause .= ' (' . implode( ') OR (', $wheres2 ) . ') ';
			break;
			
			// search all or any
			case 'all':
			case 'any':
			default:
				$words  = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word)
				{
					$word           = $db->Quote( '%'.$db->escape( $word, true ).'%', false );
					$wheres2        = array();
					$wheres2[]      = 'LOWER(r.title) LIKE '.$word;
					
					if ($yooRecipeparams->get('include_search_on_description', 1)) {
						$wheres2[] = 'LOWER(r.description) like '.$word;
					}
					
					if ($yooRecipeparams->get('include_search_on_preparation', 1)) {
						$wheres2[] = 'LOWER(r.preparation) like '.$word;
					}
					
					if ($yooRecipeparams->get('include_search_on_tags', 1)) {
						$wheres2[] = 'LOWER(t.tag_value) like '.$word;
					}
					$wheres[]       = implode( ' OR ', $wheres2 );
				}
				$whereClause .= ' (' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ') ';
			break;
		}
		
		// Set where clause
		$query->where($whereClause);
		
		// Join over tags if search includes tags
		if ($yooRecipeparams->get('include_search_on_tags', 1)) {
			$query->join('LEFT', '#__yoorecipe_tags AS t ON t.recipe_id = r.id');
		}
		
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$query->where('r.access IN ('.$groups.')');
		
		// Filter by language
		if ($app->isSite() && JLanguageMultilang::isEnabled())
		{
			$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(JFactory::getDate()->toSQL());

		$query->where('(r.publish_up = '.$nullDate.' OR r.publish_up <= '.$nowDate.')');
		$query->where('(r.publish_down = '.$nullDate.' OR r.publish_down >= '.$nowDate.')');
		
		// Prepare order by clause
		switch ( $ordering ) {
			
			// alphabetic, ascending
			case 'alpha':
				$query->order('r.title asc');
			break;
			
			// oldest first
			case 'oldest':
				$query->order('r.creation_date asc');
			break;
			
			// popular first
			case 'popular':
				$query->order('r.note desc');
			break;
			
			// newest first
			case 'newest':
				$query->order('r.creation_date desc');
			break;
			
			// default setting: alphabetic, ascending
			default:
				$query->order('r.title asc');
			break;
		}
		
		$query->group('r.id');
		
		return $query;
	}
}