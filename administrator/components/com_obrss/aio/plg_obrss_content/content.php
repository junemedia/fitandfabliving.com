<?php
/**
 * @version		$Id: content.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class addonRss_content
{
	function getItems($itemCf)
	{
		global $isJ25;
		$db = &JFactory::getDBO();
		$orderby_date = $itemCf->orderby_date=='created'?'a.created':'a.modified';
		switch (strtolower($itemCf->orderby)) {
			case 'date':
				$orderby = $orderby_date.' ASC';
				break;
			case 'rdate':
				$orderby = $orderby_date.' DESC';
				break;
			case 'alpha':
				$orderby = 'a.title ASC';
				break;
			case 'ralpha':
				$orderby = 'a.title DESC';
				break;
			case 'author':
				$orderby = 'author DESC';
				break;
			case 'rauthor':
				$orderby = 'author ASC';
				break;
			case 'hits':
				$orderby = 'hits DESC';
				break;
			case 'random':
				$orderby = 'Rand()';
				break;
			default:
				$orderby = 'a.created DESC';
		}
		$itemsTime	= $itemCf->hidden_time==1 ? 'h' : '';
		
		// AUTHORS
		$aus	= $itemCf->author;
		if (!is_array($aus)) {
			$aus = array($aus);
		}
		if (!in_array(0,$aus)) {
			$aus = implode(',',$aus);
			$qryAut	= " AND a.created_by IN ($aus)";
		} else {
			$qryAut	= '';
		}
		$j25_images = '';
		if (!$isJ25) {
			$j25_images = '`images`,';
		}
		$qry 	=  '
			SELECT	a.id,a.title, a.introtext as itext,'.($itemCf->text < 2?'':'a.fulltext as ftext,').'
				UNIX_TIMESTAMP( a.created ) AS '.$itemsTime.'s4rss_created,a.created AS `created`, u.name AS author, a.created_by_alias AS author_alias,
				'.$j25_images.'
				CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,
				CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug
				FROM #__content AS a '.
		(intval($itemCf->frontpage)==1?'INNER JOIN #__content_frontpage AS f ON f.content_id = a.id':' ').'
					LEFT JOIN #__users AS u ON u.id = a.created_by
					LEFT JOIN `#__categories` as c on c.id = a.catid
				WHERE
					a.state=1 '.$qryAut.
		($itemCf->excludearticle != ''?" AND a.id NOT IN ( $itemCf->excludearticle )":' ');
		
		// CATEGORIES
		$cats	= $itemCf->categories;
		if(!is_array($cats)) $cats = array($cats);
		if(!in_array(0,$cats)){
			$cats = implode(',',$cats);
			$qry	.= " AND a.catid IN ($cats) ";
		}
		$now		= $itemCf->now;
		$nullDate	= $db->getNullDate();
		$limit		= intval($itemCf->limit);
		if ($limit<1) {
			$limit=30;
		}
		if($itemCf->filter_keywords!=''){
			//metakey
			$keywords	= explode(',',$itemCf->filter_keywords);
			$qry_keywordsArr	= array();
			for($i=0;$i<count($keywords);$i++){
				$vKey	= trim($keywords[$i]);
				$keywordsArr[]	=  "a.`metakey` LIKE '%{$vKey}%'";
				$keywordsArr[]	=  "a.`introtext` LIKE '%{$vKey}%'";
				$keywordsArr[]	=  "a.`fulltext` LIKE '%{$vKey}%'";
			}
			$qry_keywordsArr	= ' AND ( '.implode(' OR ',$keywordsArr).' ) ';
		} else {
			$qry_keywordsArr	= '';
		}
		$qry_access = '';
		if ($itemCf->access != -1) {
			$qry_access	= " AND a.access <= ".$itemCf->access." AND (c.access <= ".$itemCf->access.")";
			/* Change access value for Joomla 1.6+
			 * Reason: in Joomla 1.6+, access value for Public/Registrated/Special = 1/2/3 instead 0/1/2 as Joomla 1.5 as.
			 */
			/*if (!$isJ25) {
				$itemCf->access++;
				$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
				$authorised_arr = implode(',', $authorised);
				$qry_access = ' AND a.access IN ('.$authorised_arr.') ';
			}*/
		}
		$qry	.= $qry_access.$qry_keywordsArr."
			AND (a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now).")
			AND (a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now).")
			ORDER BY $orderby
			LIMIT $limit";//echo '<pre>';echo $qry;print_r($itemCf);exit();
		$db->setQuery($qry);
		
		$rows = $db->loadObjectList();
		
		if (isset($_GET['x'])) {
			echo '<pre>'.$qry.'<br>';
			#print_r($itemCf);
			echo count($rows);
			print_r($rows);
			echo '</pre>';exit();
		}
		return $rows;
	}
	function getLink($row)
	{
		$idslug = $row->slug;
		$catslug = $row->catslug;
		//$sectionlug = $row->sectionid;
		require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
		//$link = ContentHelperRoute::getArticleRoute($idslug, $catslug, $sectionlug);
		$link = ContentHelperRoute::getArticleRoute($idslug, $catslug);
		return $link;
	}
	function getDesc($row,$itemCf)
	{
		global $isJ25;
		if (!$isJ25) {
			return $this->getDesc25($row, $itemCf);
		}
		if ($itemCf->text == 3) {
			$desc = $row->ftext;
		} else {
			$desc = $row->itext;
			if ($itemCf->text == 1) {
				$link = JRoute::_($this->getLink($row), true, 2);
				$desc .= '<br /><a href="'.$link.'" target="_blank">Read more ...</a>';
			} elseif ($itemCf->text == 2) {
				 $desc .= $row->ftext;
			}
		}
		return $desc;
	}
	function getDesc25($row,$itemCf) {
		// com_content global configuration
		$app = &JFactory::getApplication();
		$content_options = $app->getParams('com_content');
		$global_float_intro		= $content_options->get('float_intro');
		$global_float_fulltext	= $content_options->get('float_fulltext');
		$images = $row->images;
		$images_array = json_decode($images);
		$float_intro = '';
		$float_fulltext = '';
		if (isset($images_array->image_intro)) {
			$image_intro = $images_array->image_intro;
			$float_intro = ($images_array->float_intro=='')?$global_float_intro:$images_array->float_intro;
		} else {
			$image_intro = '';
		}
		if (isset($images_array->image_fulltext)) {
			$image_fulltext = $images_array->image_fulltext;
			$float_fulltext = ($images_array->float_fulltext=='')?$global_float_fulltext:$images_array->float_fulltext;
		} else {
			$image_fulltext = '';
		}
		// image floating for intro image
		if ($float_intro=='left') {
			$margin_intro = 'margin-right: 10px;';
		} elseif ($float_intro=='right') {
			$margin_intro = 'margin-left: 10px;';
		} else {
			$margin_intro = '';
		}
		// image floating for fulltext image
		if ($float_fulltext=='left') {
			$margin_fulltext = 'margin-right: 10px;';
		} elseif ($float_fulltext=='right') {
			$margin_fulltext = 'margin-left: 10px;';
		} else {
			$margin_fulltext = '';
		}
		$desc = '';
		if ($itemCf->text == 3) { # show only fulltext
			if ($image_fulltext) :
				$desc .= '<img src="'.JURI::root().$image_fulltext.'" align="'.$float_fulltext.'" style="border: 5px solid #595E62;margin-bottom:10px;'.$margin_fulltext.'" />';
			endif;
			$desc .= $row->ftext;
		} else {
			if ($image_intro) :
				$desc .= '<img src="'.JURI::root().$image_intro.'" align="'.$float_intro.'" style="border: 5px solid #595E62;margin-bottom:10px;'.$margin_intro.'" />';
			endif;
			$desc .= $row->itext;
			if ($itemCf->text == 1) { # intro + readmore
				$link = JRoute::_($this->getLink($row), true, 2);
				$desc .= '<br /><a href="'.$link.'" target="_blank">Read more ...</a>';
			} elseif ($itemCf->text == 2) { # intro + fulltext
				if ($image_fulltext) :
				$desc .= '<img src="'.JURI::root().$image_fulltext.'" align="'.$float_fulltext.'" style="border: 5px solid #595E62;margin-bottom:10px;'.$margin_fulltext.'" />';
				endif;
				$desc .= $row->ftext;
			}
		}
		return $desc;
	}
	function getEnclosure($row, $itemCf)
	{
		if ($itemCf->image_enclosure == 'none') { # don't display image
			return null;
		} elseif ($itemCf->image_enclosure == 'text') { # image from text
			$text = $this->getDesc($row, $itemCf);
			$filename = $this->getFirstImage($text);
		} elseif ($itemCf->image_enclosure == 'intro') { # intro image
			$images = $row->images;
			$images_array = json_decode($images);
			if (isset($images_array->image_intro)) {
				$filename = $images_array->image_intro;
			} else {
				$filename = '';
				return null;
			}
		} else { # full article image
			$images = $row->images;
			$images_array = json_decode($images);
			if (isset($images_array->image_fulltext)) {
				$filename = $images_array->image_fulltext;
			} else {
				$filename = '';
				return null;
			}
		}
	
		$fileFullPath = '';
		$fileURL = '';
		if (preg_match('/^http/', $filename)) {
			$fileFullPath = $fileURL = $filename;
			$type = 'image/jpeg';
			$length = 0;
		} else {
			$fileFullPath = JPATH_ROOT . DS . $filename;
			$fileURL = JURI::root() . $filename;
			if (JFile::exists($fileFullPath)) {
				$length	= filesize($fileFullPath);
			} else {
				$length	= 0;
				return null;
			}
			if (function_exists('mime_content_type')) {
				$type = mime_content_type($fileFullPath);
			} else {
				$type = explode(".", $filename);
				$type = 'image/'.$type[1];
			}
		}
	
		$enclosure = new stdClass();
		$enclosure->url 	= $fileURL;
		$enclosure->length	= $length;
		$enclosure->type 	= $type;
		return $enclosure;
	}
	/**
	 * Get scr of first img in string
	 * @param string $text
	 */
	public function getFirstImage($text='') {
		preg_match( '/<img\s.*?\/>/', $text, $matches_img );
		$img = '';
		if( isset( $matches_img[0] ) ) {
			$imgtag = $matches_img[0];
			preg_match( '/(src)=("([^"]*)")/', $imgtag, $result);
			$img = $result[3];
		};
		if(!$img) return null;
		$parse_url = parse_url($img);
// 		if( !key_exists('scheme',$parse_url)&& !key_exists('host',$parse_url) ){
// 			$img = JURI::root().trim($img,"/ ");
// 		}
		return $img;
	}
}
