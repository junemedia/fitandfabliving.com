<?php
/**
 * @version		$Id: view.html.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.model' );
jimport( 'joomla.html.parameter' );
class obrssViewFeeds extends JViewLegacy
{
	function display($tpl = NULL)
	{
		$rss = $this->getShowdata();
		$this->assignRef('rss', $rss);
		parent::display();
	}
	
	function getShowdata()
	{
		global $isJ25, $mainframe;
		$params 	= $mainframe->getParams();
		$Itemid 	= JRequest::getVar('Itemid', 0);
		//global $mainframe;
		//$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		//if($limit == 0 && $total>3000 ) $limit = 100;
		$limit = 100;
		$limitstart	= JRequest::getVar('limitstart', 0, 'int' );
		$db 	= JFactory::getDBO();
		$qry 	= " SELECT COUNT(*) FROM `#__".OB_TABLE_RSS."` WHERE `published` = 1";
		$db->setQuery($qry);
		$total 	= $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav= new JPagination( $total, $limitstart, $limit );
		$qry 	= "
			SELECT
				`id`,`name`,`alias`,`hits`, f.`feed_type`, f.`uri`, f.`use_feedburner`,f.`params`
			FROM #__".OB_TABLE_RSS." as f
			WHERE
				`published` = 1
			ORDER BY `ordering` ASC
		";
		$db->setQuery($qry, $pageNav->limitstart, $pageNav->limit);
		$feeds 	= $db->loadObjectList();
//		echo '<pre>'.print_r($db, true).'</pre>';
		
// 		$qry	= "SELECT `name`,`value` FROM `#__obrss_config`";
// 		$db->setQuery($qry);
// 		$rowCfs	= $db->loadObjectList();
// 		$configs = array();
// 		foreach ($rowCfs as $row){
// 			$configs[$row->name] = $row->value;
// 		}
// we no longer need it
// 		if (!isset($configs['show_number'])) {
// 			$qry_update_config = '
// 				INSERT IGNORE INTO `#__obrss_config` (`id`, `name`, `value`, `params`)
// 				VALUES
// 					("", "show_number", "0", "")
// 			';
// 			$db->setQuery($qry_update_config);
// 			$db->query();
// 		}
		if ($params->get('description') != '0' AND $Itemid != 0) {
			$menu = $mainframe->getMenu();
			$menu_params = $menu->getItem($Itemid);
//			echo '<pre>'.print_r($menu_params, true).'<pre>';
//			exit(''.__LINE__);
			$menu_params_obj = $menu_params->params;
			# image align
			if ($menu_params_obj->get('obrss_image_align') == '') { # global align
				$attribs['align'] = $params->get('image_position');
			} else { # menu align option
				$attribs['align'] = $menu_params_obj->get('obrss_image_align');
			}
			$image = '';
			if ($menu_params_obj->get('obrss_image') == -1) { # no image
				$image = '';
			} elseif ($menu_params_obj->get('obrss_image') == '') { # global image
				$image = JHTML::_('image', 'images/'.$params->get('image'), '', $attribs);
			} else { # specific image
				$image = JHTML::_('image', 'images/'.$menu_params_obj->get('obrss_image'), '', $attribs);
			}
			$description = $image;
			if ($menu_params_obj->get('obrss_description_source') == '') { # global description
				$description .= $params->get('description_text');
			} else { # use description from menu parameters
				$description .= $menu_params_obj->get('obrss_description');
			}
		}
		$lfeeds	= array();
		foreach($feeds as $element){
			switch($element->use_feedburner){
				case '0': $element->use_fb = 0;
					break;
				case '1': $element->use_fb = 1;
					break;
				case '2':
					if((int)$params->get('view_feedburner')!=0) {
						$element->use_fb = 1;
					} else {
						$element->use_fb = 0;
					}
					break;
			}
			if($this->ofThisLang($element)){
				$lfeeds[] = $element;
			}
		}
		$rss = new stdClass();
		if ($params->get('description') != '0' AND $Itemid != 0) {
			$desc	= explode('{RSS}', $description);
			$rss->description	= count($desc)==2 ? $desc : $description;
		} else {
			$rss->description = '';
		}
		//$rss->configs		= $configs;$params
		$rss->configs		= $params;
		$rss->items			= $lfeeds;
		$rss->pageNav		= $pageNav;
		return $rss;
	}
	function ofThisLang($feed){
		$params	= new JRegistry($feed->params);
		$flang	= $params->get( 'feed_lang', '*' );
		$lang	= JFactory::getLanguage();
		$tag	= $lang->getTag();
		if($flang =='*' || $tag == $flang) return true;
		return false;
	}
	
	function getLinkReder($type,$pathImg,$url_base,$feed_link)
	{
		switch ($type){
			case 'google':
				$img	= 'add-to-google-104x17.gif'; 
				$rLink	= htmlentities("http://fusion.google.com/add?feedurl=".$url_base.rawurlencode($feed_link));
				$width	= 104; break;
			case 'yahoo':
				$img	= 'addYahoo-91x17.gif';
				$rLink	= "http://add.my.yahoo.com/rss?url=".$url_base.rawurlencode($feed_link);
				$width	= 91; break;
			case 'bloglines':
				$img	= 'add_bloglines-79x17.gif';
				$rLink	=  "http://www.bloglines.com/sub/".$url_base.rawurlencode($feed_link);
				$width	= 79; break;
			case 'newsgator':
				$img	= 'addGator-91x17.gif';
				$rLink	= "http://www.newsgator.com/ngs/subscriber/subext.aspx?url=".$url_base.rawurlencode($feed_link);
				$width	= 91; break;
			case 'msn':
				$img	= 'addMSN-91x17.gif';
				$rLink	= htmlentities("http://my.msn.com/addtomymsn.armx?m=1&id=rss&ut=".$url_base.rawurlencode($feed_link));
				$width	= 91; break;
			default:
				$img	= 'xml.gif';
				$rLink	= $feed_link;
				$width	= 36;
		}
		$img = '<img border="0" src="'.$pathImg.$img.'" alt="'.$img.'" title="'.JText::_('COM_OBRSS_COMPONENT_'.strtoupper($type).'_DESC').'" width="'.$width.'" height="17" align="top" />';
		return '<a href="'.$rLink.'" target="_blank">'.$img.'</a>';
	}
	function getDescLang($desc){
		$descl	= preg_replace('/\n/','',$desc);
		$regex	= '/\{oblang:(.*?)\}(.*?)\{oblang-end\}/';
		preg_match_all($regex,$descl,$langs);
		$tlang	= count($langs[0]);
		if($tlang<1) return $desc;
		$descs	= array();
		for($i=0;$i<$tlang;$i++){
			$descl	= str_replace($langs[0][$i],'',$descl);
			$descs[$langs[1][$i]]	= $langs[2][$i];  
		}
		$tagLang = &JFactory::getLanguage()->getTag();
		$lang = substr($tagLang,0,2);
		$desc = isset($descs[$lang])?$descs[$lang]:$descl;
		return $desc;
	}
	function getListFeed(){
	}
}