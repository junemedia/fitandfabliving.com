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

// No direct access
defined('_JEXEC') or die;

/**
 * Contact component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @since		1.6
 */
class YooRecipeHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_YOORECIPE_SUBMENU_RECIPES'),
			'index.php?option=com_yoorecipe',
			$vName == 'recipes'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_YOORECIPE_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_yoorecipe',
			$vName == 'categories'
		);
		/*JSubMenuHelper::addEntry(
			JText::_('COM_YOORECIPE_SUBMENU_IMPEX'),
			'index.php?option=com_yoorecipe&view=impex',
			$vName == 'impex'
		);*/
		JSubMenuHelper::addEntry(
			JText::_('COM_YOORECIPE_SUBMENU_UNITS'),
			'index.php?option=com_yoorecipe&view=units',
			$vName == 'units'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_YOORECIPE_SUBMENU_COMMENTS'),
			'index.php?option=com_yoorecipe&view=comments',
			$vName == 'comments'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @param	int		The recipe ID.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($categoryId = 0, $recipeId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($recipeId) && empty($categoryId)) {
			$assetName = 'com_yoorecipe';
		}
		else if (empty($recipeId)) {
			$assetName = 'com_yoorecipe.category.'.(int) $categoryId;
		}
		else {
			$assetName = 'com_yoorecipe.recipes.'.(int) $recipeId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
