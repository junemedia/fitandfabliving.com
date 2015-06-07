<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
jimport( 'joomla.filter.output' );

function YooRecipeBuildRoute( &$query )
{
	$segments = array();
	$params 	= JComponentHelper::getParams('com_yoorecipe');
	
	if(isset($query['controller']))
	{
		unset( $query['controller'] );
	}

	if(isset($query['task']))
	{
		if ($query['task'] == 'viewRecipe') {
			$segments[] = $params->get('seo_recipe', 'recipe');
			unset( $query['task'] );
		}
		else if ($query['task'] == 'viewCategory') {
			$segments[] = $params->get('seo_categories', 'category');
			unset( $query['task'] );
		}
		else if ($query['task'] == 'editRecipe') {
			$segments[] = $params->get('seo_edit', 'edit');
			unset( $query['task'] );
		}
		else if ($query['task'] == 'viewByUser') {
			$segments[] = $params->get('seo_user', 'user');
			unset( $query['task'] );
		}
		else if ($query['task'] == 'viewMyRecipes') {
			$segments[] = $params->get('seo_my_cookbook', 'my-cookbook');
			unset( $query['task'] );
		}
		else if ($query['task'] == 'deleteRecipe') {
			$segments[] = 'delete-recipe';
			unset( $query['task'] );
		}
		else if ($query['task'] == 'getRecipesByLetter') {
			$segments[] = $params->get('seo_letter', 'letter');
			$segments[] = $query['l'];
			unset( $query['l'] );
			unset( $query['task'] );
		}
		else if ($query['task'] == 'tags') {
			$segments[] = 'tags';
			$segments[] = $query['value'];
			unset( $query['value'] );
			unset( $query['task'] );
		}
		/*else if ($query['task'] == 'addToFavourites') {
			$segments[] = 'addToFavourites';
			unset($query['format']);
		}
		else if ($query['task'] == 'removeFromFavourites') {
			$segments[] = 'removeFromFavourites';
			unset($query['format']);
		}
		else if ($query['task'] == 'reportComment') {
			$segments[] = 'reportComment';
			unset($query['format']);
		}
		else if ($query['task'] == 'deleteComment') {
			$segments[] = 'deleteComment';
			unset($query['format']);
		}
		else if ($query['task'] == 'getMoreComments') {
			$segments[] = 'getMoreComments';
			unset($query['format']);
		}
		else if ($query['task'] == 'addRecipeRating') {
			$segments[] = 'addRecipeRating';
			unset($query['format']);
		}*/
		/*else {
			$segments[] = $query['task'];
		}*/
		// unset( $query['task'] );
	}
	
	if(isset($query['view']))
	{
		$segments[] = $query['view'];
		
		if ($query['view'] == 'seasons') {
			unset( $query['layout'] );
		}
		unset( $query['view'] );
	}
	
	if(isset($query['layout']))
	{
		$segments[] = $query['layout'];
		unset( $query['layout'] );
	}
	
	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset( $query['id'] );
	}
	
	if(isset($query['month_id']))
	{
		$segments[] = $query['month_id'].':'.JFilterOutput::stringURLSafe(JText::_('COM_YOORECIPE_'.$query['month_id']));
		unset( $query['month_id'] );
	}
	
	if(isset($query['catid']))
	{
		$segments[] = $query['catid'];
		unset( $query['catid'] );
	}
	
	/*if(isset($query['recipeId']))
	{
		$segments[] = $query['recipeId'];
		unset( $query['recipeId'] );
	}
	
	if(isset($query['commentId']))
	{
		$segments[] = $query['commentId'];
		unset( $query['commentId'] );
	}*/
	
	return $segments;
}

function YooRecipeParseRoute( $segments )
{
	$vars = array();
	// print_r($segments);
	
	$params 	= JComponentHelper::getParams('com_yoorecipe');
	if ($segments[0] == $params->get('seo_categories', 'categories') )
	{
		$vars['controller'] = 'yoorecipe';
		$vars['task'] = 'viewCategory';
		$id = explode( ':', $segments[1] );
		$vars['id'] = (int) $id[0];
	}
	
	else if ($segments[0] == $params->get('seo_recipe', 'recipe') )
	{
		$vars['task'] = 'viewRecipe';
		if (isset($segments[1])) {
			$id = explode( ':', $segments[1] );
			$vars['id'] = (int) $id[0];
		}
		
		if (isset($segments[2])) {
			$catid = explode( ':', $segments[2] );
			$vars['catid'] = (int) $catid[0];
		}
	}
	
	else if ($segments[0] == $params->get('seo_edit', 'edit') )
	{	
		$vars['task'] = 'editRecipe';
	}
	
	else if ($segments[0] == 'form') 
	{		
		$vars['view'] = 'form';
		$vars['layout'] = 'edit';
		if (isset($segments[2])) {
			$id = explode( ':', $segments[2] );
			$vars['id'] = (int) $id[0];
		}
	}
		
	else if ($segments[0] == $params->get('seo_user', 'user') ) 
	{
		$vars['task'] = 'viewByUser';
		$id = explode( ':', $segments[1] );
		$vars['id'] = (int) $id[0];
	}
	
	else if ($segments[0] == 'seasons')
	{
		$vars['view'] = 'seasons';
		$vars['layout'] = 'seasons';
		$month_id = explode( ':', $segments[1] );
		$vars['month_id'] = $month_id[0];
	}
		
	else if ($segments[0] == 'delete-recipe')
	{
		$vars['task'] = 'deleteRecipe';
		$id = explode( ':', $segments[1] );
		$vars['id'] = (int) $id[0];
	}
	
	else if ($segments[0] == $params->get('seo_my_cookbook', 'my-cookbook') )
	{
		$vars['task'] = 'viewMyRecipes';
	}
	
	else if ($segments[0] == $params->get('seo_letter', 'letter') )
	{
		$vars['task'] = 'getRecipesByLetter';
		$vars['l'] = $segments[1];
	}
	
	else if ($segments[0] == 'initSearch')
	{
		$vars['task'] = 'initSearch';
	}
	
	else if ($segments[0] == 'favourites') {
		$vars['view'] = 'favourites';
		$vars['layout'] = 'favourites';
	}
	
	else if ($segments[0] == 'tags') {
		$vars['view'] = 'tags';
		$vars['layout'] = 'tags';
		$vars['value'] = $segments[1];
	}

	//index.php?option=com_yoorecipe&task=addToFavourites&format=raw&recipeId=@@recipe@@
	//index.php?option=com_yoorecipe&task=removeFromFavourites&format=raw&recipeId=@@recipe@@
	/*else if ($segments[0] == 'addToFavourites' || $segments[0] == 'removeFromFavourites') {
		$vars['task'] = $segments[0];
		$vars['format'] = 'raw';
		$vars['recipeId'] = $segments[1];
	}
	
	//index.php?option=com_yoorecipe&task=addRecipeRating&format=raw
	else if ($segments[0] == 'addRecipeRating') {
		$vars['task'] = $segments[0];
		$vars['format'] = 'raw';
	}
	
	//index.php?option=com_yoorecipe&task=reportComment&format=raw&recipeId=@@recipe@@&commentId=@@comment@@
	//index.php?option=com_yoorecipe&task=deleteComment&format=raw&recipeId=@@recipe@@&commentId=@@comment@@
	else if ($segments[0] == 'reportComment' || $segments[0] == 'deleteComment') {
		$vars['task'] = $segments[0];
		$vars['format'] = 'raw';
		$vars['recipeId'] = $segments[1];
		$vars['commentId'] = $segments[2];
	}
   
	//index.php?option=com_yoorecipe&task=getMoreComments&format=raw&recipeId=...
	else if ($segments[0] == 'getMoreComments') {
		$vars['task'] = $segments[0];
		$vars['format'] = 'raw';
		$vars['recipeId'] = $segments[1];
	}
	*/
	return $vars;
}