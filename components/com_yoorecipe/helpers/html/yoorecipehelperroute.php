<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_yoorecipe
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * YooRecipe Component Route Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_yoorecipe
 */
abstract class JHtmlYooRecipeHelperRoute
{
	/**
	 * @param	int	The route of the recipe item
	 * @param	int The route of the category item
	 */
	public static function getRecipeRoute($id, $catid = 0)
	{
		$link	= 'index.php?option=com_yoorecipe&task=viewRecipe&id=' . $id;
		return $link;
	}

	/**
	 * @param	int The route of the category item
	 */
	public static function getCategoryRoute($catid)
	{
		$link	= 'index.php?option=com_yoorecipe&controller=yoorecipe&task=viewCategory&id=' . $catid;
		return $link;
	}
	
	/**
	 * @param	int The route of the tag item
	 */
	public static function getTagRoute($tagvalue)
	{
		$link	= 'index.php?option=com_yoorecipe&task=tags&value=' . $tagvalue;
		return $link;
	}
	
	/**
	 * @param	int The route of the category item
	 */
	public static function getUserRoute($userId)
	{
		$link = 'index.php?option=com_yoorecipe&task=viewByUser&id=' . $userId;
		return $link;
	}
	
	/**
	 * @param	int The route of the season item
	 */
	public static function getSeasonRoute($seasonId)
	{
		$link = 'index.php?option=com_yoorecipe&view=seasons&layout=seasons&month_id=' . $seasonId;
		return $link;
	}
}
