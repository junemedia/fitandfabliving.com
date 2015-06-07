<?php
/**
 * @version		$Id: feedconvert.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class feedConvert
{
	function start()
	{
		$db = &JFactory::getDBO();
		$qry	= "SELECT * FROM `#__obrss` LIMIT 1";
		$db->setQuery($qry);
		$obrss = $db->loadObject();
		if ($obrss) {
			return 1;
		}else {
			$qry = "SELECT * FROM `#__foobla_rss` LIMIT 1";
			$db->setQuery($qry);
			$foobla_rss = $db->LoadObject();
			if ($foobla_rss) {
				if (!isset($foobla_rss->uri)) {
					$qry = "ALTER TABLE `#__foobla_rss` 
							ADD `uri` VARCHAR( 255 ) NOT NULL AFTER `hits` ,
							ADD `use_feedburner` tinyint(1) NOT NULL default '2' AFTER `uri`";
					$db->setQuery($qry);
					if (!$db->query()){
						echo '<br />'.$db->getErrorMsg();
					}
				}
				$qry	= "RENAME TABLE `#__foobla_rss` TO `#__obrss`";
				$db->setQuery($qry);
				if (!$db->query()){
					echo '<br />'.$db->getErrorMsg();
				}
			}else {
				$qry	= 
					"CREATE TABLE IF NOT EXISTS `#__obrss` (
						`id`					int(11) unsigned NOT NULL auto_increment,
						`name`					varchar(255) NOT NULL default '',
						`alias`					varchar(255) NOT NULL default '',
						`description`			text NOT NULL default '',
						`published`				tinyint(1)	NOT NULL default '0',
						`feeded`				tinyint(1)	NOT NULL default '1',
						`display_feed_module`	tinyint(1)	NOT NULL default '1',
						`feed_type`				varchar(255) NOT NULL default 'RSS2.0',
						`feed_button`			varchar(255) NOT NULL default 'rss_2.0.png',
						`params`				text NOT NULL,
						`components`			varchar(50) NOT NULL default '',
						`paramsforowncomponent`	text NOT NULL,
						`created`				datetime NOT NULL default '0000-00-00 00:00:00',
						`created_by`			int(11) unsigned NOT NULL default '0',
						`modified`				datetime NOT NULL default '0000-00-00 00:00:00',
						`modified_by`			int(11) unsigned NOT NULL default '0',
						`checked_out_time`		datetime NOT NULL default '0000-00-00 00:00:00',
						`checked_out`			int(11)	unsigned NOT NULL default '0',
						`ordering`				int(11)	NOT NULL default '0',
						`hits` 					int(11)	NOT NULL default '0',
						`uri` 					varchar(255) NOT NULL default '',
						`use_feedburner`		tinyint(1)	NOT NULL default '2',
						PRIMARY KEY  (`id`)
					)";
				$db->setQuery($qry);
				if (!$db->query()){
					echo '<br />'.$db->getErrorMsg();
				}
			}
		}
	}
}