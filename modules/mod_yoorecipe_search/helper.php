<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Search Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModYooRecipeSearchHelper
{
	/**
	 * getCategories
	 */
	public static function getCategories()
	{
		// Create a new query object.		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Get categories fields
		$query->select('c.id, c.title, c.level');
		$query->from('#__categories as c');
		
		// Set where clause
		$query->where('c.extension = ' . $db->quote('com_yoorecipe'));
		$query->where('c.published = 1');
		
		// Filter by language
		if ($app->getLanguageFilter()) {
			$query->where('c.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$query->order('lft asc');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * Get recipe authors
	 */
	public static function getAuthors()
	{
		// Create a new query object.		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		$yooRecipeparams 	= JComponentHelper::getParams( 'com_yoorecipe' );
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		// Get categories fields
		$query->select('distinct created_by, u.id');
		if ($showAuthorName == 'username') {
			$query->select('u.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('u.name AS author_name');
		}
		
		$query->from('#__yoorecipe as r');
		
		// Join over users
		$query->join('LEFT', '#__users as u on u.id = r.created_by');
	
		// Set where clause
		$query->where('r.published = 1 and r.validated = 1');
		if ($showAuthorName == 'username') {
			$query->order('u.username asc');
		} else if ($showAuthorName == 'name') {
			$query->order('u.name asc');
		}
		
		// Filter by language
		if ($app->getLanguageFilter()) {
			$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}