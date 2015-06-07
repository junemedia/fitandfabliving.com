<?php
/**
 * @version		$Id: helper.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$mainframe = JFactory::getApplication();
class modOBRSSHelper
{
	function getFeedItemid($feed){
		$addonPrms	= new JRegistry($feed->params);
		return $addonPrms->def( 'menu_itemid', 0 );
	}
	function getAddonPerFeed($feeds){
		global $option;
		$oFeeds	= array();
		$addon	= substr($option,4);
		$fAddon	= JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'addons'.DS.$addon.DS.$addon.'.php';
		if(is_file($fAddon)){
			require_once $fAddon;			 
			$classAddon = 'addonRss_'.$addon;
			if(class_exists($classAddon)){
				$classAddon = new $classAddon;
				if(method_exists( $classAddon,'getPerFeed')){
					$oFeeds	= $classAddon->getPerFeed($feeds);
				}
			}
		}
		return $oFeeds;
	}
	public static function ofThisLang($feed){
		$params = new JRegistry($feed->params);
		$flang 	= $params->def( 'feed_lang', '*' );
		$lang 	= JFactory::getLanguage();
		$tag 	= $lang->getTag();
		if($flang =='*' || $tag == $flang) return true;
		return false;
	}
	public static function getFeeds($params){
		global $isJ25, $option;
		$mainframe = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$qry	= "
			SELECT
				`id`,`name`,`alias`,`params`,`components`,`paramsforowncomponent`,`hits`,`feed_type`,`feed_button`, `uri`, `use_feedburner`
			FROM
				`#__obrss`
			WHERE
				`published`='1' AND
				`display_feed_module`='1'
			ORDER BY `ordering` ASC
		";
		$db->setQuery( $qry );
		$feeds	= $db->loadObjectList();
		
		if(!$feeds) {
			return array();
		}
		
		// get feedburner configuration value
// 		$query 	= "SELECT value FROM #__obrss_config WHERE name='view_feedburner'";
// 		$db->setQuery( $query );
// 		$showFeedburner = $db->loadResult();
		$params = JComponentHelper::getParams('com_obrss');
		$showFeedburner = $params->get('view_feedburner');
		
		
		$listf = array();
//		exit(''.__LINE__);
		foreach($feeds as $feed){
			
			if($feed->uri!='' && (int) $feed->use_feedburner!=0 && ((int) $feed->use_feedburner==1 ||(int)$showFeedburner ==1)){
				$feed->view = 'feedburner';
			}else $feed->view = 'none';
			if(self::ofThisLang($feed)){
				$listf[] = $feed;
			}
		}
		$feeds = $listf;
		$type_show	= $params->get('type_show','1');

		
		if($type_show=='1') return $feeds;
		if($option!='com_content'){
			
			$menu	= $mainframe->getMenu();
			$item	= $menu->getActive();
			$Itemid	= $item?$item->id:0;
			$oFeeds	= array();
			if($Itemid>0){
				foreach($feeds as $feed){
					$fItemid	= modobrssHelper::getFeedItemid($feed);
					if($fItemid == $Itemid){
						$oFeeds[]	= $feed;
					}
				}
				if(count($oFeeds)>0) return $oFeeds;
			}
			
			$oFeeds = modobrssHelper::getAddonPerFeed($feeds);
			
			if(count($oFeeds)>0) return $oFeeds;
			$aFile	= substr($option,4).'.xml';
			foreach($feeds as $feed){
				if($feed->components == $aFile)  $oFeeds[]	= $feed;
			}
			return $oFeeds;
		}
		$view	= JRequest::getVar('view','');
		if(!in_array($view,array('category','section','frontpage','article'))) return $feeds;
		$id		= (int)JRequest::getInt('id',0);
		$rows	= array();
		if($view=='section'){
			$secId	= $id;
		}elseif ($view=='article'){
			$qry	= "SELECT `catid` FROM `#__content` WHERE `id`=$id";
			$db->setQuery( $qry );
			$id	= $db->loadResult();
			$view	= 'category';
		}
		exit(''.__LINE__);
		if($view=='category' && $isJ25){
			$catId	= $id;
			$qry	= "SELECT `section` FROM `#__categories` WHERE `id`='$catId'";
			$db->setQuery($qry);
			$secId	= $db->loadResult();
			$qry	= "SELECT count(`section`) FROM `#__categories` WHERE `section`='$secId'";
			$db->setQuery($qry);
			$catsOfSec	= $db->loadResult();
		}
		for($i = 0;$i<count($feeds);$i++){
			$feed	= $feeds[$i];
			if($feed->components != 'content.xml') continue;
			$addonPrms	= new JRegistry($feed->paramsforowncomponent);
			$paramObj = $addonPrms->toObject();
			switch ($view){
				case 'frontpage':
					if($paramObj->frontpage=='1'){
						$rows[]=$feed;
					}
					break;
				case 'category':
					$cats	= $paramObj->categories;
					if(!$isJ25){
						if(count($cats)==1 && $cats[0]==$id){
							$rows[]=$feed;
						}
					}else {
						if(!is_array($cats)) $cats = array($cats);
						if(count($cats)==1 && $catId==$cats[0]){
							$rows = array();
							$rows[] = $feed;
						}
						/*
						if(!is_array($cats) && $catId==$cats){
							$rows = array();
							$rows[] = $feed;
							break;
						}
						$isOfCat	= is_array($cats) && !in_array($catId,$cats);
						if(!$isOfCat)$isOfCat = ($catsOfSec == 1 && $cats == '');
						if(!$isOfCat)$isOfCat = ($catsOfSec == 2 && $cats != $catId);
						if($isOfCat){
							$rows[]=$feed;
						}*/
					}
					break;
				case 'section':
					/*$secs	= $paramObj->sections;
					if(!is_array($secs) && $secs == $secId){
						$rows[]=$feed;
					}*/
					break;
			}
		}
		if(!$rows) return $feeds;
		return $rows;
	}
}