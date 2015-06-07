<?php
/**
 * $Id: com_obrss.php 732 2013-07-22 08:53:07Z tsvn $
 * AceSEF extension for obRSS
 * Developed by foobla.com
 * Copyright (C) 2007-2012 foobla.com. All rights reserved.
 * License: GNU/GPL, see LICENSE
 */
// No Permission
defined('_JEXEC') or die('Restricted access');
class AceSEF_com_obrss extends AcesefExtension {
	function build(&$vars, &$segments, &$do_sef, &$metadata, &$item_limitstart) {
		extract($vars);
		if (isset($view)){
			//$segments[] = 'feeds';
			unset($vars['view']);
		}
		if (isset($task)){
			//$segments[] = 'feed';
			unset($vars['task']);
		}
		if (isset($id)){
			$row = AceDatabase::loadRow("SELECT name, alias FROM #__obrss WHERE id = ".$id);
			$segments[] = $row[0];
			unset($vars['id']);
		}
	}
}
?>
