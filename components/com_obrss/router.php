<?php
/**
 * @version		$Id: router.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'router.php';
// JoomSEF
if (file_exists(JPATH_BASE.DS.'components/com_sef/classes/config.php')) {
// 	include_once(JPATH_BASE.DS.'components/com_sef/classes/config.php');
// 	$sefConfig = SEFConfig::getConfig();
// 	$Enabled1  = $sefConfig->enabled;
}
// sh404SEF
if (file_exists(JPATH_BASE.DS.'components/com_sh404sef/config/config.sef.php')) {
// 	include_once(JPATH_BASE.DS.'components/com_sh404sef/config/config.sef.php');
}
$Enabled  = isset($Enabled) ? $Enabled : 0;
$Enabled1 = isset($Enabled1)? $Enabled1: 0;
// if ($Enabled1 == 0 && $Enabled ==0) {
if (1==1) {
	function obRSSBuildRoute(&$query)
	{
		$segments = array();
		if(isset($query['task']) && isset($query['id']) && $query['task']=='feed') {
			#id:alias
			$alias = explode(":",$query['id']);
			$jv = new JVersion();
 			if(!isset($alias[1]) && ($jv->RELEASE=='3.0'||$jv->RELEASE=='3.1')){
				return $segments;
 			}
			$segments[] = $alias[1];
			
			unset($query['task']);
			unset($query['id']);
		}
		return $segments;
	}
	function obRSSParseRoute($segments)
	{
		$vars = array();
		$count = count($segments);
		if($count) {
			$vars['task']  = 'feed';
			$alias	= str_replace(':','-',$segments[$count-1]);
			#var_dump($alias);exit();
			# get id from alias
			$db = &JFactory::getDBO();
			$qry = '
				SELECT `id`
				FROM `#__obrss`
				WHERE
					`alias` = "'.$alias.'"
			';
			$db->setQuery($qry);
			$vars['id'] = $db->loadResult();
		}
		return $vars;
	}
}