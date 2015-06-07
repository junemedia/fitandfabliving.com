<?php
/**
 * @version		$Id: cpanel.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class obRSSModelCpanel extends obModel
{
	function getLatestItem()
	{
		$db 	= JFactory::getDBO();
		$query 	= '
			SELECT f.`id` AS `id`, f.`alias` AS `alias`, f.`name` AS `name`, f.`created` AS `created`, a.`name` AS `addon`
			FROM
				#__obrss AS f,
				#__extensions AS a
			WHERE
				(f.`components` = a.`element` OR
				f.`components` = CONCAT(a.`element`, ".xml"))
				and `a`.`type`="plugin" 
				and `a`.`folder`="obrss" 
				and `a`.`enabled`=1
			ORDER BY `created` DESC
			LIMIT 0,10
		';
		$db->setQuery($query);
		if(!$rows 	= $db->loadObjectList()) {
			echo $db->loadErrorMsg();
			echo $db->getQuery();
		}
		return $rows; 
	}
	
	function getAddonList()
	{
		$db 	= JFactory::getDBO();
		$qry = "SELECT `extension_id` as `id`, `element` as `file`, `name`, `enabled`as `published` FROM #__extensions as `a` where `a`.`type`='plugin' and `a`.`folder`='obrss' and `a`.`enabled`=1";
		$db->setQuery($qry);
		$exAddons = $db->loadObjectList();
		return $exAddons;
	}
}