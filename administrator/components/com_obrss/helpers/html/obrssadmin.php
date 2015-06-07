<?php
/**
 * $Id: obrssadmin.php 732 2013-07-22 08:53:07Z tsvn $
 * @package	foobla RSS Feed Creator for Joomla.
 * @created: December 2012.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author	foobla
 * @license	GNU/GPL, see LICENSE
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 */
abstract class JHtmlOBRssAdmin
{
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	public static function displaymodule($value = 0, $i, $canChange = true)
	{
		//JHtml::_('bootstrap.tooltip');
		// Array of image, task, title, action
		$states	= array(
			0	=> array('eye-close',	'display_feed_module',		'OBRSS_UNDISPLAYED',	'OBRSS_TOGGLE_TO_MODDISPLAY'),
			1	=> array('eye-open',	'undisplay_feed_module',	'OBRSS_DISPLAYED',		'OBRSS_TOGGLE_TO_MODUNDISPLAY'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip hasTip' . ($value == 1 ? ' active' : '') . '" data-original-title="'.JText::_($state[3]).'" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}

	public static function feeded($value = 0, $i, $canChange = true)
	{
		//JHtml::_('bootstrap.tooltip');
		// Array of image, task, title, action
		$states	= array(
			0	=> array('star-empty',	'feeded',	'OBRSS_UNFEEDED',	'OBRSS_TOGGLE_TO_FEED'),
			1	=> array('star',	'unfeeded',	'OBRSS_FEEDED',		'OBRSS_TOGGLE_TO_UNFEED'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip hasTip' . ($value == 1 ? ' active' : '') . '" data-original-title="'.JText::_($state[3]).'" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}
	
	public static function published($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
				0	=> array('remove',	'publish',	'OBRSS_UNPUBLISH',		'OBRSS_TOGGLE_TO_PUBLISH'),
				1	=> array('ok',	'unpublish',	'OBRSS_PUBLISH',		'OBRSS_TOGGLE_TO_UNPUBLISH'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" data-original-title="'.JText::_($state[3]).'" title="'.JText::_($state[3]).'" data-toggle="tooltip"><i class="icon-'
					. $icon.'"></i></a>';
		}
		
		return $html;
	}
}
