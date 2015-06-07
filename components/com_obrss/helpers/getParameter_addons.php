<?php
/**
 * @version		$Id: getParameter_addons.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
function getParameter_addons($id) {
	global $mainframe;
	$db 	= &JFactory::getDBO();
	$feed 	= JTable::getInstance('obrss','Table');
	$feed->load( $id );
	$params = new JParameter( $feed->params );
	$params->id 			= $id;
	$params->published 		= $feed->published;
	$iso 					= split( '=', _ISO2 );
	// parameter intilization
	$params->nullDate 		= '0000-00-00';
	$date 	= &JFactory::getDate();
	$now	= $date->toMySQL();
	$params->now 			= $now;
	$params->date    		= date( 'r' );
	$params->year    		= date( 'Y' );
	$params->encoding    	= $iso[1];
	$params->link    		= htmlspecialchars(JURI::root());
	$params->cache    		= $params->def( 'cache', 1 );
	$params->cache_time    	= $params->def( 'cache_time', 3600 );
	$params->count   		= $params->def( 'count', 30 );
	$params->title    		= $feed->name;
	$params->description   	= $feed->description;
	$params->image_file   	= $params->def( 'image_file', -1 );
	if ( $params->image_file == -1 ) {
		 $params->image		= NULL;
	} else{
		$params->image		= JURI::root().'images/M_images/'. $params->image_file;
	}
	$params->show_image   	= 'show';
	$params->limit_text    	= $params->def( 'limit_text', 0 );
	$params->text_length    = $params->def( 'text_length', 20 );
	$params->feed    		= $feed->feed_type;
	$params->live_bookmark  = '';
	return $params;
}