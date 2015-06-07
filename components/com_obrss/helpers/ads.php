<?php
/**
 * @version		$Id: ads.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.utilities.utility' );
class obRssAds {
	public static function getAds_banner($config) {
		global $isJ25;
		$db		= &JFactory::getDBO();
		if ($isJ25) {
			$qry	= "
				SELECT
					`bid` AS `id`, `name` as title, `custombannercode`, `imageurl` as img
				FROM
					`#__banner`
				WHERE
					`showBanner` = 1
					AND (`publish_up` = ".$db->Quote($db->getNullDate())." OR `publish_up` <= NOW())
					AND (`publish_down` = ".$db->Quote($db->getNullDate())." OR `publish_down` >= NOW())
			";
		} else {
			$qry	= "
				SELECT
					`id`, `name` as title, `custombannercode`, `params` as img
				FROM
					`#__banners`
				WHERE
					`state` = 1
					AND (`publish_up` = ".$db->Quote($db->getNullDate())." OR `publish_up` <= NOW())
					AND (`publish_down` = ".$db->Quote($db->getNullDate())." OR `publish_down` >= NOW())
			";
		}
		$db->setQuery($qry);
		$items	= $db->loadObjectList();
		$srcDir	= JURI::base().'images/banners/';
		for ($i=0; $i<count($items); $i++) {
			$link = ($isJ25) ? 'index.php?option=com_banners&task=click&bid='. $items[$i]->id : 'index.php?option=com_banners&task=click&id='. $items[$i]->id;
			$items[$i]->link	= JRoute::_( $link );
			if (!$isJ25) {
				$image_array = json_decode($items[$i]->img);
				$items[$i]->img = JURI::base().$image_array->imageurl;
			} else {
				$items[$i]->img		= $srcDir.$items[$i]->img;
			}
		}
		$ads->items	= $items;
		return $ads;
	}
	public static function getAds_obbanner($config){
		$ad	= new stdClass();
		$ad->obrssCustom	= '<h3>{obbanner}</h3>';
		$items	= array();
		$items[0]	= $ad;
		$ads->items	= $items;
		return 	$ads;
		return array();
	}
	public static function getAds_flexbanner($config){
		$ad	= new stdClass();
		$ad->obrssCustom	= '<h3>{flexbanner}</h3>';
		$items	= array();
		$items[0]	= $ad;
		$ads->items	= $items;
		return 	$ads;
		return array();
	}
	public static function getAds_rsbanners($config){
		$db		= &JFactory::getDBO();		 
		$qry	= "SELECT b.`ad_code` as obrssCustom FROM `#__rsbanners_ad` as b WHERE `status` = 1";
		$db->setQuery($qry);
		$items	= $db->LoadObjectList();
		if(!$items){
			//return array();
			$ad	= new stdClass();
			$ad->obrssCustom	= '<h3>{rsbanners}</h3>';
			$items	= array();
			$items[0]	= $ad;
		}
		$ads->items	= $items;
		return 	$ads;
	}
	public static function getAds($config){
		$ad_name	= $config->ads_from;
		if($ad_name=='') return array();
		$method		= 'getAds_'.$ad_name; 
		$ads	= obRssAds::$method($config);
		return $ads;
	}
	public static function addAds($html,$ads=array()){
		if (count($ads)<1) {
			return $html;
		}
		$items	= $ads->items;
		$a 		= count($items);
		$n		= rand(0,(count($items)-1));
		$html	.= obRssAds::renderAds($items[$n]);
		return $html;
	}
	public static function renderAds($ads){
		if(isset($ads->obrssCustom)){
			return $ads->obrssCustom;
		}
		#$html	= "<h3>{$ads->title}</h3>";
		$html	= '<div style=\"clear: both\"><a href="'.$ads->link.'"><img alt="'.$ads->title.'" src="'.$ads->img.'"/></a></div>';
		return $html;
	}
}