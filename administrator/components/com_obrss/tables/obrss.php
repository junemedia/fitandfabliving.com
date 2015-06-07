<?php
/**
 * @version		$Id: obrss.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class TableOBRSS extends JTable {
	var $id 					= null;
	var $name					= null;
	var $alias					= null;
	var $description			= null;
	
	var $published				= 1;
	var $feeded					= 1;
	var $display_feed_module	= 1;
	var $feed_type				= 'RSS2.0';
	var $feed_button			= null;
	
	var $params					= null;
	var $components				= 'content';
	var $paramsforowncomponent	= null;

	var $created				= null;
	var $created_by				= null;
	var $modified				= null;
	var $modified_by			= null;
	var $checked_out_time		= null;
	var $checked_out			= null;
	var $ordering				= null;
	var $hits					= null;
	var $uri					= null;
	var $use_feedburner 		= 2;


	function __construct(&$db){
		parent::__construct( '#__'.OB_TABLE_RSS, 'id', $db );
	}
	
	function bind($array, $ignore = '')	{
		if (isset( $array['component']['default'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['component']['default']);
			$array['params'] = $registry->toString();
		}		
		return parent::bind($array, $ignore);
	}
}
?>