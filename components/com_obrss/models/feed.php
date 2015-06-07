<?php
/**
 * @version		$Id: feed.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );
jimport( 'joomla.html.parameter' );
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'ads.php' );
class obrssModelFeed extends JModelLegacy {
	function __construct() {
		parent::__construct();
	}
	function showFeed() {
		global $option, $mainframe, $isJ25;
		$params 	= $mainframe->getParams();
		$dispatcher	= JDispatcher::getInstance();
		$results = $dispatcher->trigger( 'onCdAddedToLibrary', array( &$artist, &$title ) );
		$fId		= JRequest::getInt('id');
		if(!$fId) {
			return 1;
		}
		$db			= JFactory::getDBO();
		$glConfig 	= JFactory::getConfig();
		$qry		= "SELECT * FROM `#__".OB_TABLE_RSS."` WHERE `id` = $fId";
		$db->setQuery($qry);
		$feed		= $db->LoadObject();
		if ($feed) {
			$qry = "UPDATE `#__".OB_TABLE_RSS."` SET `hits` = (`hits`+1) WHERE id = $fId" ;
			$db->setQuery( $qry );
			$db->query();
		} else {
			return 2;
		}
//		echo '<pre>'.print_r($feed, true).'</pre>';exit();
		$addon 		= $feed->components;
		$pathAO		= JPATH_SITE.DS.'plugins'.DS.'obrss'.DS.$addon.DS.$addon.'.php';
		if (is_file($pathAO)) {
			$language = JFactory::getLanguage();
			$extension 		= 'plg_obrss_'.$addon;
			$language->load($extension, $pathAO, $language->getTag());
			require_once $pathAO;
			$classAddon = 'addonRss_'.$addon;
			if (class_exists($classAddon)) {
				$classAddon = new $classAddon;
			} else {
				return 4; # The addon class not exist.!
			}
			require_once(JPATH_COMPONENT.DS.'helpers'.DS.'itemshelper.php');
		} else {
			return 3;
		}
		$config		= $this->feedConfig($feed);
		$rssCf		= $config->rss;
		if (intval($rssCf->published) == 0) {
			return 5;
		}
		$lang			= substr($glConfig->get('config.language'),0,2);
		$cacheFile		= $addon.'_'.strtolower( str_replace( '.', '', $rssCf->feed )).$lang."_" .$fId.'.xml';
		$rssCf->file	= JPATH_SITE.DS.'cache'.DS.'com_obrss'.DS.$cacheFile;
		if (!$this->RssFolder($rssCf->file)) {
			return 6;
		}
		// @TODO: get SEF URL & Timezone, pass it to FeedCreator
		require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'feedcreator.php' );
		$rss 			= new UniversalFeedCreator();
		if ($config->item->cache && is_file($rssCf->file)  && !isset($_GET['x'])) {
			$rss->useCached( $rssCf->feed, $rssCf->file, $config->item->cache_time );
		}
		$rss->title						= $feed->name;
		$rss->description				= $feed->description;
		$rss->descriptionHtmlSyndicated = true;
		$rss->link						= $rssCf->link;
		$rss->syndicationURL 			= JURI::getInstance()->toString();
		//$rss->cssStyleSheet 			= JURI::base().'components'.DS.$option.DS.'assets'.DS.'xsl'.DS.'utility.css';
		$rss->xslStyleSheet 			= JURI::base().'components'.DS.$option.DS.'assets'.DS.'xsl'.DS.'atom-to-html.xsl';
		$rss->language					= $rssCf->language;
		$rss->encoding 					= $rssCf->encoding;
		if ($rssCf->image) {
			$image						= new FeedImage();
			$image->url					= $rssCf->image;
			$image->link				= $rssCf->link;
			$image->title				= $feed->name;
			$image->description			= $feed->description;
			$rss->image					= $image;
		}
		$tz = 0;
		if (!$isJ25) {
			$app	= JFactory::getApplication();
			// Gets and sets timezone offset from site configuration and convert it to seconds format
			$tz				= $app->getCfg('offset');
			$serverTimezone = new DateTimeZone($tz);
			$gmtTimeZone	= new DateTimeZone('GMT');
			$myDateTime		= new DateTime(date('r'), $gmtTimeZone);
			$tz 		= $serverTimezone->getOffset($myDateTime); # seconds format
		} else {
			$tzoffset 	= $glConfig->getValue('config.offset'); # in hours
			$tz = 3600 * $tzoffset; # in seconds
		}
		$rows = $classAddon->getItems($config->item);
		$ads	= obRssAds::getAds($config->item);
		// $config	 = JFactory::getConfig();
		for ($i=0;$i<count($rows);$i++) {
			$row	= $rows[$i];
			if (method_exists($classAddon, 'getTitle')) {
				$title = $classAddon->getTitle($row, $config->item);
			} else {
				$title	= htmlspecialchars($row->title);
			}
			$desc	= $classAddon->getDesc($row, $config->item);
			if ($rssCf->hideimages==1) {
				$desc	= itemsHelper::stripTags($desc,'img');
			} elseif($rssCf->resize_img !='0x0') {
				$desc	= itemsHelper::resizeImg($desc,$rssCf->resize_img);
			}
			$desc	= itemsHelper::filterDesc($desc, $config->item);
			$desc	= obRssAds::addAds($desc,$ads);
			$item = new FeedItem();
			$item->title 						= html_entity_decode($title);
			$item->link 						= str_replace("amp;", "",JRoute::_($classAddon->getLink($row), true, 2));
			$item->description 					= $desc;
			$item->descriptionHtmlSyndicated	= true;
			# author
			$configSystem = JFactory::getConfig();
// 			$qr_getauthor = "
// 				SELECT `value` AS author FROM `#__obrss_config` where `name` = 'feed_author'
// 			";
// 			$db->setQuery($qr_getauthor);
// 			$author		= $db->LoadObject();
			// get feed_author from Preferences
			$author = $params->get('feed_author');
			
// 			Timezone debug
// 			if (isset($_GET['k'])) {
//"Kha Nguyen (khant@foobla.com)"
// khant@foobla.com KhaNAHAKLSHFSDKFH
// 				echo '<pre>';
// 				echo 'tz: '.$tz.'---';
// 				print_r($config);
// 				echo '</pre>';
// 				exit('<br />Stop');
// 			}
			if ($config->item->use_global == 1) {
				$item->authorEmail = '';
				$item->author = $author->author;
			} else {
				if (isset($row->author) OR isset($row->author_alias)) {
					if (isset($row->author_alias) && $row->author_alias!='') {
						$item->author = $row->author_alias;
					} else {
						$item->author = $row->author;
					}
					#$item->author = ($row->author_alias!='') ? $row->author_alias : $row->author;
					if (isset($row->authorEmail)) {
						$item->authorEmail = $row->authorEmail;
					} else {
						$item->authorEmail = '';
					}
				} else {
					$item->author = $config->item->mail_author;
					$item->authorEmail = '';
				}
			}
			if ($config->item->hidden_time == 0) {
				#$item->date		= strtotime($row->created)+$tz;
				#var_dump($row->created);
				$created		= isset($row->s4rss_created) ? intval($row->s4rss_created) : 0;
				$item->date		= $created >0 ? ($created+$tz) : '';
			} else {
				$item->date		= '';
			}
			# load Additional Elments
			if (method_exists($classAddon, 'loadAdditionalElements')) {
				$item->additionalElements = $classAddon->loadAdditionalElements($item, $row, $config->item);
			}
			# load Additional Markup
			if (method_exists($classAddon, 'loadAdditionalMarkup')) {
				$item->additionalMarkup = "			".$classAddon->loadAdditionalMarkup($item, $row, $config->item);
			}
			# load Enclosure
			if (method_exists($classAddon, 'getEnclosure')) {
				$enclosure = $classAddon->getEnclosure($row, $config->item);
				if ($enclosure != null) {
					$item->enclosure = new EnclosureItem();
					$item->enclosure->url		= $enclosure->url;
					$item->enclosure->length	= $enclosure->length;
					$item->enclosure->type		= $enclosure->type;
				}
			}
			$rss->addItem($item);
		}
		// Debug
		if (isset($_GET['y'])) {
			echo '<pre>';
			echo 'tz: '.$tz.'---';
			print_r($rssCf);
			echo '</pre>';
			exit('<br />Stop');
		}
		if (!$rss->saveFeed($rssCf->feed, $rssCf->file, true)) {
			return 7;
		}
		return 0;
	}
	function feedConfig($feed)
	{
		$params = new JRegistry($feed->params);
		$date		= JFactory::getDate();
		$feedType	= JRequest::getVar('format', $feed->feed_type);
		$feedType	= strtoupper($feedType);
		$feedType	= in_array($feedType,array('ATOM03','ATOM','RSS091','RSS20','HTML','JSON','RSS10', 'SITEMAP'))?$feedType:'RSS20';
		$itLimit	= intval($params->def( 'count', 20 ));
		$addonPrms	= new JRegistry($feed->paramsforowncomponent);
		//config for feed items;
		$item	= new stdClass();
		$item	= $addonPrms->toObject();
		$item->menu_itemid	= $params->def( 'menu_itemid', 0 );
		$item->limit		= $itLimit >0?$itLimit:20;
		$item->hidden_time	= $params->def( 'hidden_time', 0 );
		$item->strip		= $params->def( 'strip_tags','');
		$item->limit_text	= $params->def( 'limit_text', 1 );
		$item->text_length	= $params->def( 'text_length', 20 );
		$item->now			= $date->toSql();
		$item->cache		= $params->def( 'cache', 1 );
		$item->cache_time	= $params->def( 'cache_time', 3600 );
		$item->ads_from		= $params->def( 'ads_from', '' );
		$item->language		= $params->def( 'feed_lang', '' );
		$item->use_global	= $params->def( 'use_global');
		$item->mail_author		= $params->def( 'mail_author');
		
		//config for feed view;
		$rss				= new stdClass();
		$rss->encoding		= 'utf-8';
		$rss->published 	= $feed->published;
		$rss->link			= htmlspecialchars(JURI::root());
		#$rss->link			= JURI::getInstance()->toString(); # return to the Feed URL instead of homepage
		$rss->feed			= $feedType;
		$rss->image_file	= $params->def( 'image_file', '' );
		$rss->hideimages	= $params->def( 'hideimages', 0);
		$rss->language		= $params->def( 'feed_lang', '' );
		$size				= $params->def( 'resize_img', '0x0');
		if($size!='0x0'){
			$size			= explode('x',$size);
			if(is_array($size) && count($size)==2) {
				$size	= array(intval($size[0]),intval($size[1]));
			} else {
				$size = '0x0';
			}
		}
		$rss->resize_img	= $size;
		$rss->image			= $rss->image_file == -1? '' : JURI::root().'images/'. $rss->image_file;
		$config = new stdClass();
		$config->rss	= $rss;
		$config->item	= $item;
		return $config;
	}
	function RssFolder($file)
	{
		jimport('joomla.filesystem.folder');
		$dir = dirname($file);
		if (!JFolder::exists($dir)) {
			$file	= $dir.DS.'index.html';
			$txt	= '<html><body bgcolor="#FFFFFF">&nbsp;</body></html><html>';
			if (!JFile::write($file,$txt)) {
				return false;
			}
		}
		return true;
	}
}