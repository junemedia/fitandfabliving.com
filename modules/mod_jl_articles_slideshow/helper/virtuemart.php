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


if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();
// Load the language file of com_virtuemart.
JFactory::getLanguage()->load('com_virtuemart');
if (!class_exists( 'calculationHelper' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'calculationh.php');
if (!class_exists( 'CurrencyDisplay' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');
if (!class_exists( 'VirtueMartModelVendor' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'models'.DS.'vendor.php');
if (!class_exists( 'VmImage' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'image.php');
if (!class_exists( 'shopFunctionsF' )) require(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctionsf.php');
if (!class_exists( 'calculationHelper' )) require(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'cart.php');
if (!class_exists( 'VirtueMartModelProduct' )){
   JLoader::import( 'product', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' );
}
if (!class_exists( 'JFolder' )){
   jimport('joomla.filesystem.folder');
}
if (!class_exists( 'JFile' )){
   jimport('joomla.filesystem.file');
}


class modJLArticlesSlideshowVirtuemart{
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
		global $mainframe, $my, $mosConfig_offset;
		$categoryId		= $params->get('catid','0');
		if( empty( $this->rows ) ){
			$ordering_list	= $params->get('ordering_list','order');
			$count			= $params->get('count','5');
			$jl_show_dess	= $params->get('jl_show_dess','1');
			// query
			// Create a new query object.
			$db		= $this->db;
			$query	= $db->getQuery(true);
			$user	= JFactory::getUser();
			$show_price = trim($params->get( 'show_price', '0' ) );
			
			// Add the list ordering clause.
			$orderby	= $this->orderbySecondary($ordering_list);
			$productModel = VmModel::getModel('Product');
			$rows = $this->getProductListing($orderby, $count, $show_price, true, false,true, $categoryId);
			$productModel->addImages($rows);
			
			
			for ($i = 0; $n = count($rows), $i < $n; $i++) {
				$rows[$i]->title			= $rows[$i]->product_name;
				$paymentCurrency			= CurrencyDisplay::getInstance($rows[$i]->product_currency);
				$rows[$i]->jl_product_price	= $paymentCurrency->priceDisplay( $rows[$i]->product_price,$rows[$i]->product_currency) ;
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
	function _renderLink(&$row) {
		$row->catlink	= JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$row->virtuemart_category_id);
	}
	function _renderImages(&$row,$params) {
		$pathimg		= $this->_pathimg;
		$thumbWidth		= $params->get('thumbnail_width','60');
		$thumbHeight	= $params->get('thumbnail_height','60');
		
		if( isset($row->images[0]->file_url) && $row->images[0]->file_url ){
			$row->image		= $row->images[0]->file_url;
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
	function _renderDisplayText (&$row,$params) {
		$row->introtext				= strip_tags($row->product_s_desc);
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
		$row->date			= JHtml::_('date', $row->created_on, JText::_('DATE_FORMAT_LC2'));
		$row->category_title= $row->category_name;
		$row->catid			= $row->virtuemart_category_id;
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
	/*
	 * Overide function virtuemart
	 */
	function getProductListing($group = false, $nbrReturnProducts = false, $withCalc = true, $onlyPublished = true, $single = false,$filterCategory= true, $category_id=0){
		$productModel = VmModel::getModel('Product');
		$app = JFactory::getApplication();
		if($app->isSite() ){
			$front = true;
			if(!class_exists('Permissions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'permissions.php');
			if(!Permissions::getInstance()->check('admin','storeadmin')){
				$onlyPublished = true;
				if ($show_prices=VmConfig::get('show_prices',1) == '0'){
					$withCalc = false;
				}
			}
		} else {
			$front = false;
		}

		$productModel->setFilter();
		if ( $filterCategory=== true)
		{
			if( is_array($category_id) && !empty($category_id) ){
				$this->virtuemart_category_id = implode(',', $category_id);
			}else if ( !is_array($category_id) && $category_id ){
				$this->virtuemart_category_id = $category_id;
			}
		}
		else
		{
			$this->virtuemart_category_id = false;
		}

		$ids = $this->sortSearchListQuery($onlyPublished, $this->virtuemart_category_id, $group, $nbrReturnProducts);

		
		$products = $productModel->getProducts($ids, $front, $withCalc, $onlyPublished,$single);
		return $products;
	}
	function sortSearchListQuery($onlyPublished=true,$virtuemart_category_id = false, $group=false,$nbrReturnProducts=false){

		$app = JFactory::getApplication() ;
		$productModel = VmModel::getModel('Product');
		$groupBy = 'group by p.`virtuemart_product_id`';

		//administrative variables to organize the joining of tables
		$joinCategory 	= false ;
		$joinMf 			= false ;
		$joinPrice 		= false ;
		$joinCustom		= false ;
		$joinShopper 	= false;
		$joinLang 		= true; // test fix Patrick
		$orderBy = ' ';

		$where = array();
		$useCore = true;
		if ($productModel->searchplugin !== 0){
			//reset generic filters ! Why? the plugin can do it, if it wishes it.
			// 			if ($this->keyword ==='') $where=array();
			JPluginHelper::importPlugin('vmcustom');
			$dispatcher = JDispatcher::getInstance();
			$PluginJoinTables = array();
			$ret = $dispatcher->trigger('plgVmAddToSearch',array(&$where, &$PluginJoinTables, $productModel->searchplugin));
			foreach($ret as $r){
				if(!$r) $useCore = false;
			}
		}

		if($useCore){
// 		if ( $this->keyword !== "0" and $group ===false) {
		if ( !empty($this->keyword) and $this->keyword !=='' and $group ===false) {
// 			$groupBy = 'group by p.`product_parent_id`';

			//		$keyword = trim(preg_replace('/\s+/', '%', $keyword), '%');
			$keyword = '"%' . $this->_db->getEscaped($this->keyword, true) . '%"';

			foreach ($this->valid_search_fields as $searchField) {
				if($searchField == 'category_name' || $searchField == 'category_description'){
					$joinCategory = true;
				}else if($searchField == 'mf_name'){
					$joinMf = true;
				}else if($searchField == 'product_price'){
					$joinPrice = true;
				}
				else if(strpos($searchField, '.')== 1){
					$searchField = 'p`.`'.substr($searchField, 2, (strlen($searchField))) ;
				}
				$filter_search[] = '`'.$searchField.'` LIKE '.$keyword;

			}
			if(!empty($filter_search)){
				$where[] = implode(' OR ', $filter_search );
			} else {
				$where[] = '`product_name` LIKE '.$search;
				//If they have no check boxes selected it will default to product name at least.
			}
			$joinLang = true;
		}

// 		vmdebug('my $this->searchcustoms ',$this->searchcustoms);
		if (!empty($this->searchcustoms)){
			$joinCustom = true ;
			foreach ($this->searchcustoms as $key => $searchcustom) {
				$custom_search[] = '(pf.`virtuemart_custom_id`="'.(int)$key.'" and pf.`custom_value` like "%' . $this->_db->getEscaped( $searchcustom, true ) . '%")';
			}
			$where[] = " ( ".implode(' OR ', $custom_search )." ) ";
		}



		if($onlyPublished){
			$where[] = ' p.`published`="1" ';
		}

		if($app->isSite() && !VmConfig::get('use_as_catalog',0) && VmConfig::get('stockhandle','none')=='disableit' ){
			$where[] = ' p.`product_in_stock`>"0" ';
		}

		
		if ( $virtuemart_category_id  ){
			$joinCategory = true ;
			$where[] = ' `#__virtuemart_product_categories`.`virtuemart_category_id` IN('.$virtuemart_category_id.')';
		}

		if ($productModel->product_parent_id){
			$where[] = ' p.`product_parent_id` = '.$this->product_parent_id;
		}


		if ($app->isSite()) {
			if(!class_exists('VirtueMartModelUser')) require(JPATH_VM_ADMINISTRATOR.DS.'models'.DS.'user.php');
			$usermodel = VmModel::getModel('user');
			$currentVMuser = $usermodel->getUser();
			$virtuemart_shoppergroup_ids =  (array)$currentVMuser->shopper_groups;

			if(is_array($virtuemart_shoppergroup_ids)){
				foreach ($virtuemart_shoppergroup_ids as $key => $virtuemart_shoppergroup_id){
					$where[] .= '(s.`virtuemart_shoppergroup_id`= "' . (int) $virtuemart_shoppergroup_id . '" OR' . ' (s.`virtuemart_shoppergroup_id`) IS NULL )';
				}
				$joinShopper = true;
			}
		}


		if ($productModel->virtuemart_manufacturer_id) {
			$joinMf = true ;
			$where[] = ' `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` = '.$productModel->virtuemart_manufacturer_id;
		}

		// Time filter
		if ($productModel->search_type != '') {
			$search_order = $this->_db->getEscaped(JRequest::getWord('search_order') == 'bf' ? '<' : '>');
			switch ($this->search_type) {
				case 'parent':
					$where[] = 'p.`product_parent_id` = "0"';
					break;
				case 'product':
					$where[] = 'p.`modified_on` '.$search_order.' "'.$this->_db->getEscaped(JRequest::getVar('search_date')).'"';
					break;
				case 'price':
					$joinPrice = true ;
					$where[] = 'pp.`modified_on` '.$search_order.' "'.$this->_db->getEscaped(JRequest::getVar('search_date')).'"';
					break;
				case 'withoutprice':
					$joinPrice = true ;
					$where[] = 'pp.`product_price` IS NULL';
					break;
			}
		}


		// special  orders case
		switch ($productModel->filter_order) {
			case 'product_special':
				$where[] = ' p.`product_special`="1" ';// TODO Change  to  a  individual button
				$orderBy = 'ORDER BY RAND()';
				break;
			case 'category_name':
				$orderBy = ' ORDER BY `category_name` ';
				$joinCategory = true ;
				break;
			case 'category_description':
				$orderBy = ' ORDER BY `category_description` ';
				$joinCategory = true ;
				break;
			case 'mf_name':
				$orderBy = ' ORDER BY `mf_name` ';
				$joinMf = true ;
				break;
			case 'ordering':
				$orderBy = ' ORDER BY `#__virtuemart_product_categories`.`ordering` ';
				$joinCategory = true ;
				break;
			case 'product_price':
				//$filters[] = 'p.`virtuemart_product_id` = p.`virtuemart_product_id`';
				$orderBy = ' ORDER BY `product_price` ';
				$joinPrice = true ;
				break;
			case 'created_on':
				$orderBy = ' ORDER BY p.`created_on` ';
				break;
			default ;
				if(!empty($this->filter_order)){
					$orderBy = ' ORDER BY '.$this->_db->getEscaped($this->filter_order).' ';
				} else {
					$this->filter_order_Dir = '';
				}
			break;
		}

		//Group case from the modules
		if($group){

			$groupBy = 'group by p.`virtuemart_product_id`';
			switch ($group) {
				case 'featured':
					$where[] = 'p.`product_special`="1" ';
					$orderBy = 'ORDER BY RAND()';
					break;
				case 'latest':
					$date = JFactory::getDate( time()-(60*60*24*7) ); //Set on a week, maybe make that configurable
					$dateSql = $date->toMySQL();
					$where[] = 'p.`modified_on` > "'.$dateSql.'" ';
					$orderBy = 'ORDER BY p.`modified_on`';
					$this->filter_order_Dir = 'DESC';
					break;
				case 'random':
					$orderBy = ' ORDER BY RAND() ';//LIMIT 0, '.(int)$nbrReturnProducts ; //TODO set limit LIMIT 0, '.(int)$nbrReturnProducts;
					break;
				case 'topten';
					$orderBy = ' ORDER BY product_sales ';//LIMIT 0, '.(int)$nbrReturnProducts;  //TODO set limitLIMIT 0, '.(int)$nbrReturnProducts;
					$this->filter_order_Dir = 'DESC';
			}
			// 			$joinCategory 	= false ; //creates error
			// 			$joinMf 		= false ;	//creates error
			$joinPrice 		= true ;
			$this->searchplugin	= false ;
// 			$joinLang = false;
		}
		}

		//write the query, incldue the tables
		// 		$selectFindRows = 'SELECT SQL_CALC_FOUND_ROWS * FROM `#__virtuemart_products` ';
		// 		$selectFindRows = 'SELECT COUNT(*) FROM `#__virtuemart_products` ';
		if($joinLang){
			$select = ' * FROM `#__virtuemart_products_'.VMLANG.'` as l';
			$joinedTables = ' JOIN `#__virtuemart_products` AS p using (`virtuemart_product_id`)';
		} else {
			$select = ' * FROM `#__virtuemart_products` as p';
			$joinedTables = '';
		}

		if ($joinCategory == true) {
			$joinedTables .= ' LEFT JOIN `#__virtuemart_product_categories` ON p.`virtuemart_product_id` = `#__virtuemart_product_categories`.`virtuemart_product_id`
			 LEFT JOIN `#__virtuemart_categories_'.VMLANG.'` as c ON c.`virtuemart_category_id` = `#__virtuemart_product_categories`.`virtuemart_category_id`';
		}
		if ($joinMf == true) {
			$joinedTables .= ' LEFT JOIN `#__virtuemart_product_manufacturers` ON p.`virtuemart_product_id` = `#__virtuemart_product_manufacturers`.`virtuemart_product_id`
			 LEFT JOIN `#__virtuemart_manufacturers_'.VMLANG.'` as m ON m.`virtuemart_manufacturer_id` = `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` ';
		}


		if ($joinPrice == true) {
			$joinedTables .= ' LEFT JOIN `#__virtuemart_product_prices` as pp ON p.`virtuemart_product_id` = pp.`virtuemart_product_id` ';
		}
		if ($productModel->searchcustoms) {
			$joinedTables .= ' LEFT JOIN `#__virtuemart_product_customfields` as pf ON p.`virtuemart_product_id` = pf.`virtuemart_product_id` ';
		}
		if ($this->searchplugin!==0) {
			if (!empty( $PluginJoinTables) ) {
				$plgName = $PluginJoinTables[0] ;
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_custom_plg_'.$plgName.'` as '.$plgName.' ON '.$plgName.'.`virtuemart_product_id` = p.`virtuemart_product_id` ' ;
			}
		}
		if ($joinShopper == true) {
			$joinedTables .= ' LEFT JOIN `#__virtuemart_product_shoppergroups` ON p.`virtuemart_product_id` = `#__virtuemart_product_shoppergroups`.`virtuemart_product_id`
			 LEFT  OUTER JOIN `#__virtuemart_shoppergroups` as s ON s.`virtuemart_shoppergroup_id` = `#__virtuemart_product_shoppergroups`.`virtuemart_shoppergroup_id`';
		}

		if(count($where)>0){
			$whereString = ' WHERE ('.implode(' AND ', $where ).') ';
		} else {
			$whereString = '';
		}
		
		$product_ids =  $productModel->exeSortSearchListQuery(2, $select, $joinedTables, $whereString, $groupBy, $orderBy, $this->filter_order_Dir, $nbrReturnProducts);
		return $product_ids;

	}
	public function orderbySecondary($orderby, $orderDate = 'created'){
		$queryDate = $orderDate;
		switch ($orderby){
			case 'latest' :
				$orderby = 'latest';
				break;
			case 'random' :
				$orderby = 'random';
				break;
			case 'topten' :
				$orderby = 'topten';
				break;
			case 'featured' :
				$orderby = 'featured';
				break;
			default :
				$orderby = 'random';
				break;
		}
		return $orderby;
	}
}
