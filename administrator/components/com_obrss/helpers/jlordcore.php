<?php
/**
 * @version		$Id: jlordcore.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class ConfigHelper
{
	function isShow($entity)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT `value`
			FROM `#__obrss_config`
			WHERE `name`='$entity'
		";
		$db->setQuery($query);
		if($db->loadResult())
			return true;
		else
			return false;
	}
	function setState($entity, $value)
	{
		$db =& JFactory::getDBO();
		$query = "
			UPDATE `#__obrss_config`
			SET `value`='$value'
			WHERE `name`='$entity'
		";
		$db->setQuery($query);
		$db->query();
	}
	function entityRemove($entity)
	{
		global $option;
		/**
		 * TODO
		 * 1, remove menu
		 * 2, remove icon on cPanel
		 */
		// 1, remove menu
		$cName = substr($entity, 5);
		$query = "
			DELETE FROM `#__components`
			WHERE `admin_menu_link`='option=$option&controller=$cName'
		";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		echo $db->getQuery();
		$db->query();
	}
	function entityRescue($entity)
	{
		global $option;
		/**
		 * TODO:
		 * 1, rescue menu item
		 * 2, rescue icon on cPanel
		 */
		//id, name, link, menuid, parent, admin_menu_link, admin_menu_alt, option, ordering, admin_menu_img, iscore, params, enabled
		// 0, get parent id - TODO
		// 1, rescure menu item
		$cName = substr($entity, 5);
		if($cName=='config'){
			$name = 'Configuration';
		}
		if($cName=='items'){
			$name = 'Items';
		}
		if($cName=='langs'){
			$name = 'Languages';
		}
		if($cName=='tools'){
			$name = 'Tools';
		}
		if($cName=='upgrade'){
			$name = 'Upgrade';
		}
		#var_dump($cName);
		$query = "
			INSERT INTO `#__components` (`name`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`)
			VALUES ('$name','34', 'option=$option&controller=$cName','$name','$option')
		";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		#echo $db->getQuery();
		$db->query();
	}
}