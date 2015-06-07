<?php
/**
 * $Id: com_obrss.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		foobla RSS Feed Creator for Joomla.
 * @subpackage	Artio JoomSEF extension for obRSS.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla
 * @license		GNU/GPL, see LICENSE
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_obrss extends SefExt
{
	function _getMenuTitle($option, $task, $id = null, $string = null) {
		$db =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();

		// JF translate extension.
		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

		if( $title = JoomSEF::_getCustomMenuTitle($option) ) {
			return $title;
		}
		$title = str_replace('com_foobla_', '', $option);
		return $title;
	}
	
	function GetJlord_rssName($id) {
		$database  =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();
		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';
		$query = "SELECT `name`$jfTranslate FROM `#__obrss` WHERE `id` = '$id'";
		$database->setQuery($query);
		$row = $database->loadObject();
		
		return isset($row->name) ? $row->name : '';
	}
	
	function create(&$uri) {
		$db  =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();
		$vars = $uri->getQuery(true);
		extract($vars);
//		 $query = "SELECT * FROM `#__obrss_config` WHERE `name` = 'component'";
//		 $db->setQuery($query);
//		 $row1 = $db->loadObject();
		
		$params = JComponentHelper::getParams('com_obrss');
		$row1 = $params->get('component');
	  
	 	if($row1->value == ''){
			$title[] = SefExt_com_obrss::_getMenuTitle(@$option, @$task, @$Itemid);
	 	} else {
//	  		$option = $row1->value;
	 		$option = $row1;
	 		$title[] = SefExt_com_obrss::_getMenuTitle(@$option, @$task, @$Itemid);
	 	}
		
		switch(@$task) {
			case 'feed':
				$title[] = $this->GetJlord_rssName($id);
				unset($task);
				break;
		}

		$newUri = $uri;
		if (count($title) > 0) $newUri = JoomSEF::_sefGetLocation($uri, $title, @$task, null, null, @$vars['lang']);
		
		return $newUri;
	}
}
?>
