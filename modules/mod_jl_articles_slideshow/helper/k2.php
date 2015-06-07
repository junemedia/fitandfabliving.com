<?php
/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
require_once JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php';
class modJLArticlesSlideshowK2{
	var $db		= null;
	var $rows	= null;
	function  __construct() {
		global $module;
		$this->db = JFactory::getDBO();
		$this->_baseurl = JURI::base();
		$this->_pathimg = JURI::base().'modules/'.$module->module.'/libraries/timthumb.php';
		$this->_dirthumb		= JPATH::clean(JPATH_ROOT . '/cache/' .$module->module.'_'.$module->id.'/thumbnail');
		$this->_dir				= JPATH::clean(JPATH_ROOT . '/cache/' .$module->module.'_'.$module->id.'/images');
		$this->_pathCacheThumb		= JUri::base().'cache/'.$module->module.'_'.$module->id.'/thumbnail/';
		$this->_pathCacheImage		= JUri::base().'cache/'.$module->module.'_'.$module->id.'/images/';
	}
	function getRows(&$params){
		$categoryId		= $params->get('catid','0');
		if( !is_array($categoryId) || empty($categoryId) ){
			return false;
		}
		if( empty( $this->rows ) ){
			$ordering_list	= $params->get('ordering_list','order');
			$count			= $params->get('count','5');
			$jl_show_dess	= $params->get('jl_show_dess','1');
			$categoryId		= implode(',', $categoryId);
			// query
			// Create a new query object.
			$db		= $this->db;
			$query	= $db->getQuery(true);
			// tra ve obj JDatabaseQueryMySQLi , neu false tra ve string query.
			// doi tuong JDatabaseQueryMySQLi,moi su dung duoc function $query->select()...etc
			$user	= JFactory::getUser();
			
			// Select the required fields from the table.
			$query = "SELECT i.*, CASE WHEN i.modified = 0 THEN i.created ELSE i.modified END as lastChanged, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams";
			$query.=" FROM #__k2_items as i LEFT JOIN #__k2_categories AS c ON c.id = i.catid";
			$query .= " WHERE i.published = 1 AND ";
			$query .= "i.access IN(".implode(',', $user->getAuthorisedViewLevels()).")"
					." AND i.trash = 0"
					." AND c.published = 1"
					." AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).")"
					." AND c.trash = 0";
			$mainframe = &JFactory::getApplication();
			$languageFilter = $mainframe->getLanguageFilter();
			if( $languageFilter ) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND c.language IN (".$db->quote($languageTag).",".$db->quote('*').")
				AND i.language IN (".$db->quote($languageTag).",".$db->quote('*').")" ;
			}
			$query .= " AND c.id IN($categoryId) ";
			

			// Add the list ordering clause.
			$orderby	= $this->orderbySecondary($ordering_list);
			$query .= " ORDER BY ".$orderby;
			$db->setQuery($query,0,$count);
			
			$rows = $db->loadObjectList();
			for ($i = 0; $n = count($rows), $i < $n; $i++) {
				$this->_renderLink($rows[$i]);
				$this->_renderImages($rows[$i],$params);
				if( $jl_show_dess ){
					$this->_renderDisplayText($rows[$i],$params);
				}
			}
			$this->rows = $rows;
			//echo nl2br(str_replace('#__','enuv1_',$query));exit;
			return $this->rows;
		}
	}
	function _renderImages(&$row,$params) {
		$pathimg		= $this->_pathimg;
		$thumbWidth		= $params->get('thumbnail_width','60');
		$thumbHeight	= $params->get('thumbnail_height','60');
		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$row->id).'_L.jpg')){
            $row->image = 'media/k2/items/cache/'.md5("Image".$row->id).'_L.jpg';
        }else{
			//$regex = "/\<img\s*src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
			$regex = '/src=([\'"])?(.*?)\\1/';
			preg_match ($regex, $row->introtext, $images);
			if( isset($images[2]) && $images[2] ){
				$row->image		= $images[2];
			}else{
				$row->image		= 'modules/mod_jl_articles_slideshow/assets/images/demo.jpg';
			}
		}
		$row->createdThumb = $this->createThumb($row,$params);
		if( !isset($row->createdThumb) && $row->image ){
			$row->createdThumb = $this->_pathimg.'?src='.$this->_baseurl.$row->image.'&amp;h='.$ThumbHeight.'&amp;w='.$ThumbWidth;
		}
		$row->realimage		= $this->createRealImage($row,$params);
	}
	function createThumb($row,$params){
		global $module;
		if( !JFolder::exists($this->_dirthumb) ){
			JFolder::create($this->_dirthumb);
		}
		$fileurl = JFile::getName($this->_baseurl.$row->image);
		JLImageHelper::createImage(JPath::clean(JPATH_ROOT.'/'.$row->image), JPath::clean($this->_dirthumb.'/'.$fileurl), $params->get('thumbnail_width','60'), $params->get('thumbnail_height','60'));
		$result	= $this->_pathCacheThumb.$fileurl;
		return $result;
	}
	function createRealImage($row,$params){
		global $module;
		if( !JFolder::exists($this->_dir) ){
			JFolder::create($this->_dir);
		}
		$fileurl = JFile::getName($this->_baseurl.$row->image);
		JLImageHelper::createImage(JPath::clean(JPATH_ROOT.'/'.$row->image), JPath::clean($this->_dir.'/'.$fileurl), $params->get('main_width','661'), $params->get('main_height','352'));
		$result	= $this->_pathCacheImage.$fileurl;
		return $result;
	}
	/**
	 * Render article link
	 * @access private
	 * @param object $row
	 * @param int $Itemid
	 */

	function _renderLink(&$row) {
		$row->link		= urldecode(JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.urlencode($row->alias), $row->catid.':'.urlencode($row->categoryalias))));
		$row->catlink	= JRoute::_(K2HelperRoute::getCategoryRoute($row->catid));
	}
	function _renderDisplayText (&$row,$params) {
		$row->introtext				= strip_tags($row->introtext);
		$row->displaytext			= $row->introtext;
		$textcount					= $params->get('jl_limit_desc','100');
		$jl_readmore				= $params->get('jl_readmore','1');
		$titleMaxChars				= $params->get( 'title_max_chars', '100' );
		$replacer					= $params->get('replacer','...');
		$enable_desc_on_navigation	= $params->get( 'enable_desc_on_navigation', 1 );
		$limit_desc_on_navigation	= $params->get( 'limit_desc_on_navigation', '40' );

		if( $textcount ) {
			$row->desc	= $this->cutstr($row->displaytext,$textcount,$row->link,$jl_readmore);
		}
		$row->subtitle		= $this->substring( $row->title, $titleMaxChars, $replacer );
		$row->date			= JHtml::_('date', $row->created, JText::_('DATE_FORMAT_LC2'));
		$row->category_title= $row->categoryname;
		if( $enable_desc_on_navigation ){
			$row->subdesc	= $this->substring( $row->displaytext, $limit_desc_on_navigation, $replacer );
		}
	}
	function substring( $text, $length = 100, $replacer='...', $isAutoStripsTag = true ){
		$string =  $isAutoStripsTag?  strip_tags( $text ):$text;
		return JString::strlen( $string ) > $length ?  JHtml::_('string.truncate', $string, $length ): $string;
	}
	function cutStr($str,$limit,$link,$jl_readmore){
	    if(strlen($str)<=$limit){
			if( $jl_readmore ){
				$str.= "<a href='".$link."'>"."&nbsp;".JText::sprintf('%s',JText::_('JL_READMORE'))."</a>";
			}
	        return $str;
	    }
	    else{
	        if(strpos($str," ",$limit) > $limit){
	            $new_limit 	= strpos($str," ",$limit);
	            $new_str 	= substr($str,0,$new_limit)."<a href='".$link."'>"."&nbsp;".JText::sprintf('%s',JText::_('JL_READMORE'))."</a>";
	            return $new_str;
	        }
	        $new_str = substr($str,0,$limit);
			if( $jl_readmore ){
				$new_str.= "<a href='".$link."'>"."&nbsp;".JText::sprintf('%s',JText::_('JL_READMORE'))."</a>";
			}
	        return $new_str;
	    }
	}
	public function orderbySecondary($orderby, $orderDate = 'created'){
			switch ($orderby) {
				case 'date':
						$orderby = 'i.created ASC';
						break;

				case 'rdate':
						$orderby = 'i.created DESC';
						break;

				case 'alpha':
						$orderby = 'i.title';
						break;

				case 'ralpha':
						$orderby = 'i.title DESC';
						break;

				case 'order':
						$orderby = 'c.ordering, i.ordering';
						break;

				case 'rorder':
						$orderby = 'c.ordering DESC, i.ordering DESC';
						break;

				case 'featured':
						$orderby = 'i.featured DESC, i.created DESC';
						break;

				case 'hits':
						$orderby = 'i.hits DESC';
						break;

				case 'rand':
						$orderby = 'RAND()';
						break;

				case 'modified':
						$orderby = 'lastChanged DESC';
						break;

				case 'publishUp':
						$orderby = 'i.publish_up DESC';
						break;

				case 'id':
				default:
						$orderby = 'i.id DESC';
						break;
			}
		return $orderby;
	}

}
?>