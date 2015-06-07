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

class modJLArticlesSlideshowBanners{
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
			$user	= JFactory::getUser();

			// Select the required fields from the table.
			$query->select(
				'a.id AS id, a.name AS title, a.alias AS alias,'.
				'a.description as introtext, a.params,a.created,a.catid'
			);
			$query->from($db->quoteName('#__banners').' AS a');

			// Join over the categories.
			$query->select('c.title AS category_title');
			$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

			

			$query->where('a.state = "1"');
			
			$query->where('a.catid IN('.$categoryId.')');

			// support check languagle for each content display slideshow
			if( JLanguageMultilang::isEnabled() ){
				$lang_code		= JFactory::getLanguage()->getTag();;
				$query->where('a.language IN ('.$db->quote($lang_code).','.$db->quote('*').') AND c.language IN('.$db->quote($lang_code).','.$db->quote('*').')  ');
			}
			// Add the list ordering clause.
			$orderby	= $this->orderbySecondary($ordering_list);
			$query->order($db->escape($orderby));
			
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
		$paramsrow		= new JParameter($row->params);
		$imageurl		=  $paramsrow->get('imageurl','0');
		if( $imageurl ){
			$row->image		= $imageurl;
		}else{
			$row->image		= 'modules/mod_jl_articles_slideshow/assets/images/demo.jpg';
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
		$row->link		= JRoute::_('index.php?option=com_banners&task=click&id='. $row->id);
		$row->catlink	= '#';
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
		$queryDate = $orderDate;
		switch ($orderby){
			case 'date' :
				$orderby = $queryDate;
				break;

			case 'rdate' :
				$orderby = $queryDate . ' DESC ';
				break;

			case 'alpha' :
				$orderby = 'a.name';
				break;

			case 'ralpha' :
				$orderby = 'a.name DESC';
				break;

			case 'hits' :
				$orderby = 'a.clicks DESC';
				break;

			case 'rhits' :
				$orderby = 'a.clicks';
				break;

			case 'order' :
				$orderby = 'a.ordering';
				break;

			case 'author' :
				$orderby = 'created_by';
				break;

			case 'rauthor' :
				$orderby = 'created_by DESC';
				break;

			default :
				$orderby = 'a.ordering';
				break;
		}

		return $orderby;
	}

}
?>