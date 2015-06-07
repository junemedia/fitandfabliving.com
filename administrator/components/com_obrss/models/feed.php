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
require_once JPATH_COMPONENT.DS.'helpers'.DS.'obparameter.php';
class obRSSModelFeed extends obModel
{
	public $_data1;
	public $_params;
	public $_lists1;
	public $_sql;
	public $_data;
	public $_total;
	
	public $_lists;
	
	public $_pagination;
	public $_dataFeedburner;
	public $_stats_fb;
	/**
	 * constructor
	 * @return array Array of objects containing the data from the database
	 */
	function __construct() {
		parent::__construct();
		$mainframe = JFactory::getApplication();
		$option = 'com_obrss';
//		global $mainframe, $option;
		// Get the pagination request variables
		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState( 'limit', $limit );
		$this->setState( 'limitstart', $limitstart );
	}
	/**
	 * Retrieves the params
	 * @return array Array of objects containing the data from the database
	 */
	function getParams(){
		$this->_params = new OBParameter($this->_data1->params,  JPATH_COMPONENT_ADMINISTRATOR.DS.'elements'.DS.'feed_params.xml', 'component');
		return $this->_params;
	}
	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getDataJlord_rss1() {
		$mainframe= JFactory::getApplication();
		$user		= JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(0), '', 'array' );
		$option 	= JRequest::getCmd( 'option');
		JArrayHelper::toInteger($cid, array(0));
		$edit		= JRequest::getVar( 'edit', true );
		$uid 		= (int) $cid[0];
		$this->_data1 = JTable::getInstance(OB_TABLE_RSS, 'Table');
		// load the row from the db table
		if($edit){
			$this->_data1->load( $uid );
		}
		if ($this->_data1->isCheckedOut( $user->get('id') )) {
			$mainframe->redirect( 'index.php?option='. $option, JText::_( "OBRSS_TI" ) . $this->_data1->name . JText::_( "OBRSS_EAA" ) );
		}
		return $this->_data1;
	}
	/**
	 * Retrieves the lists
	 * @return array Array of objects containing the data from the database
	 */
	function getListsJlord_rss1()
	{
		$mainframe	= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$option 	= JRequest::getCmd( 'option');
		$uid 		= (int) $cid[0];
		$edit		= JRequest::getVar( 'edit', true );
		$rows 		= JTable::getInstance(OB_TABLE_RSS, 'Table');
		$components		=  JRequest::getCmd( 'addon','content');
		$components .= '.xml';
		// load the row from the db table
		if($edit){
			$rows->load( $uid );
		}
		if ($rows->isCheckedOut( $user->get('id') )) {
			$mainframe->redirect( 'index.php?option='. $option, JText::_( "OBRSS_TI" ) . $this->_data1->name . JText::_( "OBRSS_EAA" ) );
		}
		if($uid){
			$rows->checkout( $user->get('id') );
		}
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__'.OB_TABLE_RSS.''
			. ' ORDER BY ordering'
			;
		if($edit){
			$this->_lists1['ordering'] 	= JHtml::_('list.ordering',  $this->_data1, $query,'class="span12" size="1"',$cid[0] );
		}
		else{
			$this->_lists1['ordering'] 	= JHTML::_('list.ordering',  $this->_data1, $query,'class="span12" size="1"' );
		}
		$query_1 = 'SELECT use_feedburner as uf'.
 ' FROM #__'.OB_TABLE_RSS.
 ' WHERE id = '.$uid;
		$db->setQuery($query_1);
		$result = $db->loadResult();
		$check_g = '';
		$check_n = '';
		$check_y = '';
		switch($result){
			case '2': $check_g = ' selected="selected"';
			break;
			case '0': $check_n = ' selected="selected"';
			break;
			case '1': $check_y = ' selected="selected"';
		 	break;
		}
		$this->_lists1['use_feedburner']  = '<select class="inputbox" id="ordering" name="use_feedburner">';
		$this->_lists1['use_feedburner'] .= '	<option value="2"'.$check_g.'>Use global</option>';
		$this->_lists1['use_feedburner'] .= '	<option value="0"'.$check_n.'>No</option>';
		$this->_lists1['use_feedburner'] .= '	<option value="1"'.$check_y.'>Yes</option>';
		$this->_lists1['use_feedburner'] .= '</select>';
		//Feedbutton images uit de directory laden
		$button_path = JPATH_SITE .DS. "components".DS."com_obrss".DS."images".DS."buttons";
		$dir = @opendir($button_path);
		$button_images = array();
		$button_col_count = 0;
		while( $file = @readdir($dir) )
		{
			if( $file != '.' && $file != '..' && is_file($button_path . '/' . $file) && !is_link($button_path . '/' . $file) )
			{
				if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file) )
				{
					$button_images[$button_col_count]	= $file;
					$button_name[$button_col_count]		= strtoupper(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $file)));
					$buttons[]							= JHTML::_( 'select.option', $button_images[$button_col_count], $button_name[$button_col_count]);
					$button_col_count++;
				}
			}
		}
		@closedir($dir);
		$this->_lists1['feedButtons'] = JHTML::_( 'select.genericList', $buttons, 'feed_button', 'onchange="loadButton(this)" class="span12" ','value', 'text',$edit ? $rows->feed_button : 'rss_2.0.png');
		$query = "SELECT `element` as value, `name` as text FROM #__extensions WHERE `type`='plugin' and `folder`='obrss' and `enabled`=1 ORDER BY `name`";
		$db->setQuery( $query );
		$addons = $db->loadObjectList();
		$this->_lists1['components'] = JHTML::_( 'select.genericList', $addons, 'components', 'class="span4 obrss_data_source" onchange = "showComParamater();"','value', 'text',$edit ? $rows->components : $components);
		return $this->_lists1;
	}
	
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$option = 'com_obrss';
		$filter_state			= $mainframe->getUserStateFromRequest( "$option.filter_state",		'filter_state',		'',		'word' );
		$filter_addon			= $mainframe->getUserStateFromRequest( "$option.filter_addon",		'filter_addon',		'',		'word' );
		$filter_state_feed		= $mainframe->getUserStateFromRequest( "$option.filter_state_feed",	'filter_state_feed', '',	'word' );
		$filter_state_display	= $mainframe->getUserStateFromRequest( "$option.filter_state_display",	'filter_state_display', '',	'word' );
		$filter_order			= $mainframe->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'j.ordering', 'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'',		'word' );
		$where 					= array();
		$search					= $mainframe->getUserStateFromRequest( 'search', 'search', '',	'string' );
		$search					= JString::strtolower($search);
		if( $filter_addon ){
			$where[] = "j.components like '{$filter_addon}%'";
		}
		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'j.published = 1';
			}
			else if ($filter_state == 'U' )
			{
				$where[] = 'j.published = 0';
			}
		}
		if($filter_state_feed){
			if ( $filter_state_feed == 'F' )
			{
				$where[] = 'j.feeded = 1';
			}
			else if ($filter_state_feed == 'UF' )
			{
				$where[] = 'j.feeded = 0';
			}
		}
		if($filter_state_display){
			if ( $filter_state_display == 'D' )
			{
				$where[] = 'j.display_feed_module = 1';
			}
			else if ($filter_state_display == 'UD' )
			{
				$where[] = 'j.display_feed_module = 0';
			}
		}
		if ($search){
			$where[] = 'LOWER(j.name) LIKE '.$db->Quote( '%'.$db->escape( $search ).'%', false );
		}
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
		$query = "SELECT j.* FROM #__".OB_TABLE_RSS." as j"
		. $where 
		. $orderby;
		return $query;
	}
	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// load the data if it doesn't already exist
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_data;
	}
	/**
	 * Paginates the data
	 * @return array Array of objects containing the data from the database
	 */
	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			// load obPagination
			//echo __LINE__;exit();
			include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'obpagination.php');
// 			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
			$this->_pagination = new obPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	/**
	 * Retrieves the count 
	 * @return array Array of objects containing the data from the database
	 */
	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	/**
	 * Retrieves the lists
	 * @return array Array of objects containing the data from the database
	 */
	function getLists(){
		$mainframe = JFactory::getApplication();
		$option = 'com_obrss';
		$db = JFactory::getDBO();
		$filter_order			= $mainframe->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'j.id', 'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'DESC',		'word' );
		$filter_state			= $mainframe->getUserStateFromRequest( "$option.filter_state",		'filter_state',		'',		'word' );
		$filter_addon			= $mainframe->getUserStateFromRequest( "$option.filter_addon",		'filter_addon',		'',		'word' );
		$filter_state_feed		= $mainframe->getUserStateFromRequest( "$option.filter_state_feed",	'filter_state_feed', '',	'word' );
		$filter_state_display	= $mainframe->getUserStateFromRequest( "$option.filter_state_display",	'filter_state_display', '',	'word' );
		$search					= $mainframe->getUserStateFromRequest( 'search', 'search', '',	'string' );
		$search					= JString::strtolower($search);
		$this->_lists['addon'] = $this->getLAddon($filter_addon);'addon list'; # placeholder
		$this->_lists['state']	= JHTML::_('grid.state',  $filter_state );
		$state_feed[] 	= JHTML::_('select.option',  '', '- '. JText::_( 'OBRSS_SELECT_STATEFEED' ) .' -' );
		$state_feed[] 	= JHTML::_('select.option', 'F', JText::_( 'OBRSS_FEED' ) );
		$state_feed[] 	= JHTML::_('select.option', 'UF', JText::_('OBRSS_UNFEED' ) );
		$this->_lists['feed'] 	= JHTML::_('select.genericlist',   $state_feed, 'filter_state_feed', 'class="inputbox" onchange="submitform( );"', 'value', 'text', $filter_state_feed );
		$state_display[] 						= JHTML::_('select.option',  '', '- '. JText::_( 'OBRSS_SELECT_STATEDISPLAY' ) .' -' );
		$state_display[] 						= JHTML::_('select.option', 'D', JText::_( 'OBRSS_DISPLAY' ) );
		$state_display[] 						= JHTML::_('select.option', 'UD', JText::_('OBRSS_UNDISPLAY' ) );
		$this->
		$this->_lists['display_feed_module'] 	= JHTML::_('select.genericlist',   $state_display, 'filter_state_display', 'class="inputbox" onchange="submitform( );"', 'value', 'text', $filter_state_display );
		// table ordering
		$this->_lists['order_Dir']	= $filter_order_Dir;
		$this->_lists['order']		= $filter_order;
		// search filter
		$this->_lists['search']     = $search;
		return $this->_lists;
	}
	function getLAddon($selected='')
	{
		$db	= JFactory::getDBO();
//		$qry	= "SELECT `id`, `file` FROM #__obrss_addons WHERE `published` = 1"; // with .xml as suffix
		$qry ="SELECT a.extension_id as `id`, `a`.`element` FROM #__extensions as `a` where `a`.`type`='plugin' and `a`.`folder`='obrss' and `a`.`enabled`=1";
		$db->setQuery($qry);
		$addOns 	= $db->loadObjectList();
		$options	= array();
		if( $addOns ) {
			foreach( $addOns as $add ) {
				$newa	= new stdClass();
				$v		= $add->element;
				$v		= explode(".", $v);
				$v		= $v[0];
				//var_dump($v);
				$newa->value	= $v;
				$newa->text		= $v;
				$options[]		= $newa;
			}
		}
		array_unshift($options, JHTML::_('select.option', '', JText::_('OBRSS_SELECT_ADDON')));
		return JHTML::_('select.genericlist',  $options, 'filter_addon', 'onchange="submitform( );"', 'value', 'text', $selected);
	}
	
	function save()
	{
		jimport('joomla.filesystem.folder');
		$mainframe = JFactory::getApplication();
		$option = 'com_obrss';
		$user 	= JFactory::getUser();
		$row  	= JTable::getInstance(OB_TABLE_RSS,'Table');
		$post 	= JRequest::get('post');
		if ( !$row->bind( $post ) ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit('Error bind');
		}

		$components = JRequest::getVar('components');
		$params	= JArrayHelper::getValue( $_POST['component'], 'default');
		$comname = pathinfo($components, PATHINFO_FILENAME);

		$detail	= JRequest::getVar('detail');
		$paramsforcomponent = JArrayHelper::getValue( $_POST[$detail], 'default');
		if (is_array($paramsforcomponent)){
			$registry = new JRegistry();
			$registry->loadArray($paramsforcomponent);
			$row->paramsforowncomponent = $registry->toString();
		}
		$row->components = JRequest::getVar('components');
		$row->use_feedburner = JRequest::getVar('use_feedburner');
		$feed_type= JRequest::getVar('feed_button');
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $feed_type) ){
		   $type = strtoupper(str_replace("_", "", preg_replace('/^(.*)\..*$/', '\1', $feed_type)));
		}
		$row->feed_type = $type;
		$row->id = (int) $row->id;
		$isNew = ($row->id < 1);
		if ($isNew) {
			$row->created			= $row->created ? $row->created : date( "Y-m-d H:i:s" );
			$row->created_by 		= $row->created_by ? $row->created_by : $user->get('id');
			$row->modified 			= date( "Y-m-d H:i:s" );
			$row->modified_by 		= $user->get('id');
		} else {
			$row->modified 			= date( "Y-m-d H:i:s" );
			$row->modified_by 		= $user->get('id');
			$row->checkin();
		}
		if(empty($row->alias)) {
			$row->alias = $row->name;
		}
		$row->alias = JFilterOutput::stringURLSafe($row->alias);
		if(trim(str_replace('-','',$row->alias)) == '') {
			$datenow =& JFactory::getDate();
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}
		if ( !$row->store() ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit($row->getError());
		}
		$this->clearCache($row->id);
		$link	= "index.php?option=$option&controller=feed";
		$task	= JRequest::getCmd('task');
		/*$task	= 'apply';
		$task	= 'save2new';
		$task	= 'save2copy';*/
		switch ($task) {
			case 'apply':
				$link .= "&task=edit&cid[]=$row->id";
				break;
			case 'save2new':
				$link .= "&task=add";
				break;
			case 'save2copy':
				$link .= "&task=edit&cid[]={$row->id}&ascopy=1";
				break;
		}
		/*$msg = $row->name . JText::_( 'OBRSS_SCSA' );*/
		$msg_format = JText::_("OBRSS_SCSA");
		$msg = sprintf( $msg_format, $row->name );
		$mainframe->redirect( $link, $msg );
	}//end save
	
	
	function copy($id){
		$row = JTable::getInstance(OB_TABLE_RSS,'Table');
		$row->load($id);
		$row->id	= null;
		$row->name	= $row->name." (copy)";
		$row->alias	= $row->alias."-copy";
		if ( !$row->store() ) {
			return $row->getError();
		}
		return JText::_('OBRSS_FEED_COPIED')." {$id}";
	}
	
	
	function loadElement($addon)
	{
		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'common.php';
		ObRssCommon::loadElement($addon);
		return;
	}
	
	
	function getListAddOn()
	{
		global $isJ25;
		$mainframe = JFactory::getApplication();
		$option = 'com_obrss';
		$db		= JFactory::getDBO();
		$fid	= JRequest::getVar( 'cid', array(0), '', 'array' );
		$fid	= intval($fid[0]);
		$feed	= false;
		if($fid){
			$qry	= "SELECT `components`,`paramsforowncomponent` FROM #__".OB_TABLE_RSS." WHERE id = $fid";
			$db->setQuery($qry);
			$feed	= $db->loadObject();
		}
		$qry	= "SELECT `a`.`extension_id` AS `id`, `a`.`element`, `a`.`params` FROM #__extensions as `a` where `a`.`type`='plugin' and `a`.`folder`='obrss' and `a`.`enabled`=1";
		$db->setQuery($qry);
		$addOns = $db->loadObjectList();
		if(!$feed){
			$feed = new stdClass();
// 			$first_addon = isset($addOns[0])?$addOns[0]->element:'';
			$first_addon = 'content';
			$feed->components = JRequest::getCmd( 'addon',$first_addon);
			$feed->paramsforowncomponent = '';
		}
		$htmlParams = '';
		$arrList	= array();

		$qry	= "SELECT `a`.`extension_id` AS `id`, `a`.`element`, `a`.`params` FROM #__extensions as `a` where `a`.`type`='plugin' and `a`.`folder`='obrss' and `a`.`enabled`=1";
		$db->setQuery($qry);
		$addOns = $db->loadObjectList();
		for ($i=0;$i<count($addOns);$i++) {
			$addOn = $addOns[$i];
			$display		= 'none';
			if ($addOn->element == $feed->components) {
				$addOn->params	= $feed->paramsforowncomponent;
				$display	= 'block';
			}
			$name			= $addOn->element;

			// load plugin/addon language
			$lang = JFactory::getLanguage();
			$lang->load('plg_obrss_'.$name, JPATH_SITE.DS.'plugins'.DS.'obrss'.DS.$name, 'en-GB', true);
			
			$arrList[]		= 'obrss_addon_'.$name;
			$this->loadElement($name);
			#$params			= new JParameter($addOn->params, JPATH_COMPONENT_SITE.DS.'addons'.DS.$name.DS.$addOn->file);
			$xml_file = JPATH_SITE.DS.'plugins'.DS.'obrss'.DS.$name.DS.$name.'.xml';
			if(!is_file($xml_file)){
				$mainframe->enqueueMessage(JText::_('plugins'.DS.'obrss'.DS.$name.DS.$name.'.xml'.' does not exists'),'notice');
				
			}
			$params 			= new OBParameter($addOn->params, $xml_file,$name);
			$addonRender	= $params->render($name);
			$addonRender	= $addonRender?$addonRender:"<div style = \"color:#ff6600;padding:5px;text-align:center;font-weight:bold\">".JText::_('OBRSS_NONEXIST_PARAMATER')."</div>";
			$htmlParams		.=  "<div id = \"obrss_addon_$name\" name=\"obrss_addon_$name\" style = \"display:$display\"> $addonRender </div>\n";
		}
		$res = new stdClass();
		$res->lists = $arrList;
		$res->params= $htmlParams; 
		
		return $res;
	}
	function getFeedburnerData(){
		$db 		= JFactory::getDBO();
		$cid		= JRequest::getVar('cid');
		$cid 		= (int)$cid;
		$option 	= JRequest::getCmd( 'option');
		$controller = JRequest::getVar('controller');
		$task 		= JRequest::getVar('task');
		$uri		= '';
		$query = "SELECT f.uri FROM #__".OB_TABLE_RSS." as f WHERE id = ".$cid;
		$db->setQuery($query);
		$uri = $db->loadResult();
		$dataFeedburner 			= new stdClass();
		$dataFeedburner->cid		= $cid;
		$dataFeedburner->option 	= $option;
		$dataFeedburner->controller	= $controller;
		$dataFeedburner->task		= $task;
		$dataFeedburner->uri		= $uri;
		$this->_dataFeedburner 		= $dataFeedburner;
		return $this->_dataFeedburner;
	}
	function saveFeedBurner(){
		$cid		= JRequest::getInt('cid');
		$uri 		= JRequest::getVar('uri');
		$option 	= JRequest::getCmd('option');
		$controller = JRequest::getVar('controller');
		$db 		= JFactory::getDBO();
		$query = "
			UPDATE `#__obrss`
				SET `uri`='".addslashes($uri)."'
			WHERE `id`=".(int)$cid."
		";
		$db->setQuery($query);
		if (!$db->query($query)) {
			echo __LINE__; exit();
		}
	}
	function getStatisticsFeedburner(){
		$session		= JFactory::getSession();
		$option			= JRequest::getCmd('option');
		$controller 	= JRequest::getVar('controller');
		$cid 			= JRequest::getInt('cid');
		$fdate 			= JRequest::getVar('fromdate');
		$tdate 			= JRequest::getVar('todate');
		$fdate_session  = $session->get('fdate');
		$tdate_session  = $session->get('tdate');
		$view  			= JRequest::getVar('list_view');
		$chart 			= JRequest::getVar('list_chart');
		$xml_2		    = JFactory::getXMLParser('Simple');
		$list_view 					 = $this->getListView($view);
		$list_chart 				 = $this->getListChart($chart);
		$this->_stats_fb->list_view  = $list_view;
		$this->_stats_fb->list_chart = $list_chart;
		$db = JFactory::getDBO();
		$query = "SELECT f.name, f.uri FROM #__".OB_TABLE_RSS." as f WHERE id = ".$cid;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$uri = $result[0]->uri;
		$this->_stats_fb->feedname = $result[0]->name;
		if (!$fdate && !$tdate) {
			$current_date_default	= date('Y-m-d',time());
			$past_date_default		= date('Y-m-d',strtotime($current_date_default." -10 day"));
			$dates	= '&dates='.$past_date_default.','.$current_date_default;
		} else {
			$dates	= '&dates='.$fdate.','.$tdate;
		}
		$dataPath	= JPATH_ROOT.DS.'cache'.DS.'com_obrss'.DS.'Statsbydates.xml';
		if(!$fdate_session || !$tdate_session || $fdate_session != $fdate || $tdate_session != $tdate){
			$contentXml = $this->getFeedburnerXmlByDates($uri,$dates);
			$xml_2->loadString($contentXml);
			JFile::write($dataPath, $contentXml);
		} else {
			$xml_2->loadString(JFile::read($dataPath));
		}
		if($xml_2->document == null || $xml_2->document->attributes('stat')!="ok")
		{
			$this->_stats_fb->view 				= '';
			$this->_stats_fb->number_record		= 0;
			$this->_stats_fb->fromdate 			= '';
			$this->_stats_fb->todate			= '';
			return $this->_stats_fb;
		} 
		$n = count($xml_2->document->feed[0]->entry);
		$current_date = $xml_2->document->feed[0]->entry[$n - 1]->attributes('date');
		$tdate = ($tdate > $current_date)?$current_date:$tdate;
		switch ($view){
			case 'daily' 		: $data = $this->getDataByDate($xml_2);
				$this->_stats_fb->time 			= $data->date;
				$this->_stats_fb->number_record 	= $data->count;
				$this->_stats_fb->fromdate 		=  $xml_2->document->feed[0]->entry[0]->attributes('date');
				$this->_stats_fb->todate 			=  $xml_2->document->feed[0]->entry[$n-1]->attributes('date');	  
			break;
			case 'weekly'		: $data = $this->getDataByWeek($xml_2,$fdate,$tdate);
				$this->_stats_fb->time 			= $data->week;
				$this->_stats_fb->number_record	= $data->count;
				$this->_stats_fb->fromdate 		=  $xml_2->document->feed[0]->entry[0]->attributes('date');
				$this->_stats_fb->todate 			=  $xml_2->document->feed[0]->entry[$n-1]->attributes('date');
			break;
			case 'monthly'		: $data = $this->getDataByMonth($xml_2,$fdate,$tdate);
				$this->_stats_fb->time 			= $data->month;
				$this->_stats_fb->number_record 	= count($data->month);
				$this->_stats_fb->fromdate 		=  $xml_2->document->feed[0]->entry[0]->attributes('date');
				$this->_stats_fb->todate 			=  $xml_2->document->feed[0]->entry[$n-1]->attributes('date');	  		  
			break;
			case 'annually'		: $data = $this->getDataByYear($xml_2,$fdate,$tdate);
				$this->_stats_fb->fromdate  		=  $xml_2->document->feed[0]->entry[0]->attributes('date');
				$this->_stats_fb->todate 			=  $xml_2->document->feed[0]->entry[$n-1]->attributes('date');
				$this->_stats_fb->time 			= $data->year;
				$this->_stats_fb->number_record	= $data->count;
			break;
			default				: $data = $this->getDataByDate($xml_2,$current_date);
				$this->_stats_fb->fromdate 		= date('Y-m-d',strtotime($current_date." -6 day"));
				$this->_stats_fb->todate			= $current_date;
				$this->_stats_fb->time 			= $data->date;
				$this->_stats_fb->number_record	= $data->count;
			break;
		}
		$session->set('fdate',$this->_stats_fb->fromdate);
		$session->set('tdate',$this->_stats_fb->todate);
		$this->_stats_fb->circulation   = $data->circulation;
		$this->_stats_fb->hits			= $data->hits;
		$this->_stats_fb->downloads 	= $data->downloads;
		$this->_stats_fb->reach			= $data->reach;
		$this->_stats_fb->id = $cid;
		$this->_stats_fb->list_view = $list_view;
		$this->_stats_fb->view = $view;
		$this->getChartData();
		return $this->_stats_fb;
	}
	function getFeedburnerXmlByDates($uri,$dates){
		$url = 'http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.$uri.$dates;
		$content	= ObRssCommon::getUrlContent($url);
		return $content;
	} 
	function getDataByDate($xml_2,$cdate=''){
		$data = null;
		$i	  = 0;
		if($cdate){
			$pdate = date('Y-m-d',strtotime($cdate." -6 day"));
			foreach ($xml_2->document->feed[0]->entry as $entry) {
				if($entry->attributes('date') >= $pdate && $entry->attributes('date') <= $cdate){
					$data->date[] 			= $entry->attributes('date');
					$data->circulation[]  	= $entry->attributes('circulation');
					$data->reach[]	 		= $entry->attributes('reach');
					$data->hits[]			= $entry->attributes('hits');
					$data->downloads[]	 	= $entry->attributes('downloads');
					$i++;
				}
			}
		} else {
			foreach ($xml_2->document->feed[0]->entry as $entry) {
				$data->date[] 			= $entry->attributes('date');
				$data->circulation[]  	= $entry->attributes('circulation');
				$data->reach[]	 		= $entry->attributes('reach');
				$data->hits[]			= $entry->attributes('hits');
				$data->downloads[]	 	= $entry->attributes('downloads');
				$i++;
			}
		}
		$data->count = $i;
		return $data;
	}
	function getDataByWeek($xml,$fdate,$tdate){
		$data 		 = null;
		$weeks 		 = $this->get_weeks($fdate, $tdate);
		$data->count = count($weeks->number);
		for($i = 0; $i< count($weeks->number); $i++){
			if($weeks->begindate[$i]==$weeks->enddate[$i]){
				$data->week[] = $weeks->begindate[$i];
			}else $data->week[] = $weeks->begindate[$i]." To ".$weeks->enddate[$i];
			$data->circulation[$i]	= 0;
			$data->hits[$i]			= 0;
			$data->downloads[$i]	= 0;
			$data->reach[$i] 		= 0;
			foreach ($xml->document->feed[0]->entry as $entry){
				if($entry->attributes('date')>=$weeks->begindate[$i]&&$entry->attributes('date')<=$weeks->enddate[$i]){
					$data->circulation[$i]	+= $entry->attributes('circulation');
					$data->hits[$i]			+= $entry->attributes('hits');
					$data->downloads[$i]	+= $entry->attributes('downloads');
					$data->reach[$i]		+= $entry->attributes('reach');
				}
			}
		}
		return $data;
	}
	function getDataByMonth($xml,$fdate,$tdate){
		$data	= null;
		$months = $this->get_months($fdate, $tdate);
		for($i=0; $i<count($months); $i++){
			$data->month[$i]		= $months[$i];
			$data->circulation[$i]	= 0;
			$data->hits[$i]			= 0;
			$data->downloads[$i]	= 0;
			$data->reach[$i]		= 0;
			foreach ($xml->document->feed[0]->entry as $entry){
				$time = mb_substr($entry->attributes('date'), 0, 7);
				if($time == $months[$i])
				{
					$data->circulation[$i]	+= $entry->attributes('circulation');
					$data->hits[$i]			+= $entry->attributes('hits');
					$data->downloads[$i]	+= $entry->attributes('downloads');
					$data->reach[$i]		+= $entry->attributes('reach');
				}
			}
		}
		$lastmonth	= explode('-', $data->month[$i-1]);
		$end_date	= $this->getLastOfMonth($lastmonth[1], $lastmonth[0]);
		$end_date	= $this->getLastOfMonth($lastmonth[1], $lastmonth[0]);
		if(mb_substr($fdate, 8, 9) != '01')
		{
			$data->month[0]  .= " (from ".$fdate.")";
		}
		if($end_date != $tdate)
		{
			$data->month[$i-1] .= " (to ".$tdate.")";
		}
		return $data;
	}
	function getDataByYear($xml,$fdate,$tdate){
		$data	= null;
		$years	= $this->get_years($fdate,$tdate);
		for($i=0; $i<count($years); $i++){
			$data->year[$i]			= $years[$i];
			$data->circulation[$i]	= 0;
			$data->hits[$i]			= 0;
			$data->downloads[$i]	= 0;
			$data->reach[$i]		= 0;
			foreach ($xml->document->feed[0]->entry as $entry){
				$time = mb_substr($entry->attributes('date'), 0, 4);
				if($time == $years[$i])
				{
					$data->circulation[$i]	+= $entry->attributes('circulation');
					$data->hits[$i]			+= $entry->attributes('hits');
					$data->downloads[$i]	+= $entry->attributes('downloads');
					$data->reach[$i]		+= $entry->attributes('reach');
				}
			}
		}
		if( mb_substr($fdate, 5) != '01-01')
		{
			$data->year[0] .= " (from ".$fdate.")";
		}
		if( mb_substr($tdate, 5) != '12-31')
			{
				$data->year[$i-1] .= " (to ".$tdate.")";
			}
		$data->count = $i;
		return $data;
	}
	function get_weeks($fdate,$tdate){
		$weeks		 = new stdClass();
		$array_fdate = explode('-',$fdate);
		$array_tdate = explode('-',$tdate);
		$f_date_time = mktime(0,0,0,$array_fdate[1],$array_fdate[2],$array_fdate[0]);
		$t_date_time = mktime(0,0,0,$array_tdate[1],$array_tdate[2],$array_tdate[0]);
		$fday		 = date('N',$f_date_time);
		$end_of_week = strtotime('+ '.(7-$fday).'days',$f_date_time);
		if($end_of_week > $t_date_time)
		{
			$weeks->number[] 	 = 1;
		 	$weeks->begindate[] = $fdate;
		 	$weeks->enddate[]	 = $tdate;
			return $weeks;
		}  
		$begindate 	= $f_date_time;
		$enddate	= $end_of_week;
		$i 			= 0;
		while($enddate < $t_date_time){
			$weeks->number[] 	= $i+1;
			$weeks->begindate[] = date('Y-m-d',$begindate);
			$weeks->enddate[]	= date('Y-m-d',$enddate);
			$begindate  = strtotime('+ 1day',$enddate);
			$enddate	= strtotime('+ 7days',$enddate);
			$i++;
		}
		$weeks->number[]	= $i + 1;
		$weeks->begindate[] = date('Y-m-d',$begindate);
		$weeks->enddate[]	= $tdate;
		return $weeks;
	}
//get months from 'fdate' to 'tdate'
	function get_months($fdate, $tdate) {
		$months			= array();
		$array_fdate	= explode('-', $fdate);
		$array_tdate	= explode('-', $tdate);
		$currentyear	= $array_fdate[0];
		$currentmonth	= $array_fdate[1];
		$lastyear		= $array_tdate[0];
		$lastmonth		= $array_tdate[1];
		//get first month
		$months[0] 		= $currentyear.'-'.$currentmonth;
		//check if fdate and tdate are in one month
		if((int)$currentyear==(int)$lastyear&&(int)$currentmonth==(int)$lastmonth)
		{
			return $months;
		}
		$nextmonth = '';
		while($nextmonth < $lastyear.'-'.$lastmonth){
			$nextmonth			= $this->getNextMonth($currentmonth, $currentyear);
			$months[]			= $nextmonth;
			$array_nextmonth	= explode('-',$nextmonth);
			$currentyear		= $array_nextmonth[0];
			$currentmonth		= $array_nextmonth[1];
		}
		return $months;
	}
	function getNextMonth($month,$year){
		return date("Y-m", strtotime('+1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
	}
	function getLastOfMonth($month,$year) {
		return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
	}
	function get_years($fdate,$tdate){
		$years		= array();
		$fyear		= mb_substr($fdate, 0, 4);
		$tyear		= mb_substr($tdate, 0, 4);
		$years[]	= $fyear;
		if((int)$fyear == (int)$tyear)
		{
			return $years;
		}
		$currentyear = $fyear;
		while((int)$currentyear < (int)$tyear){
			$currentyear = $years[] = (int)$currentyear + 1;
		}
		return $years;
	}
	function getListView($view){
		$select_d = '';
		$select_w = '';
		$select_m = '';
		$select_y = '';
		switch ($view){
			case 'daily': $select_d = "selected";
			break;
			case 'weekly': $select_w = "selected";
			break;
			case 'monthly': $select_m = "selected";
			break;
			case 'annually': $select_y = "selected";
			break;
		}
		$list_view 	= "<select name='list_view'>";
		$list_view .= "<option value='daily' ".$select_d.">Daily</option>";
		$list_view .= "<option value='weekly' ".$select_w." >Weekly</option>";
		$list_view .= "<option value='monthly' ".$select_m.">Monthly</option>";
		$list_view .= "<option value='annually' ".$select_y.">Annually</option>";
		$list_view .= "</select>";
		return $list_view;
	}
	function getChartData(){
		$charttype	= JRequest::getVar('list_chart');
		$data		= $this->_stats_fb;
		if($data->number_record){
			$count = $data->number_record;
		} else return null;
		switch($charttype){
			case 'line_area'	: $this->getAmlineChartData($data); 
				break;
			case 'column_bar'	: $this->getAmColumnChartData($data);
				break;
			default				: $this->getAmlineChartData($data);
				break;
		}
		$doc	= new DOMDocument('1.0', 'UTF-8');
		$root	= $doc->createElement("chart");
		$doc->appendChild($root);
		$series = $doc->createElement("series");
		$root->appendChild($series);
		$graphs = $doc->createElement("graphs");
		$root->appendChild($graphs);
		$graph1 = $doc->createElement("graph");
		$graph1->setAttribute("gid","1");
		$graphs->appendChild($graph1);
		$graph2 = $doc->createElement("graph");
		$graph2->setAttribute("gid","2");
		$graphs->appendChild($graph2);
		$graph3 = $doc->createElement("graph");
		$graph3->setAttribute("gid","3");
		$graphs->appendChild($graph3);
		for($i=0; $i<$count; $i++){
			$value_series = $doc->createElement("value");
			$value_series->setAttribute("xid",$i);
			$value_series->appendChild($doc->createTextNode($data->time[$i]));
			$series->appendChild($value_series);
			$value_graph1 = $doc->createElement("value");
			$value_graph1->setAttribute("xid",$i);
			$value_graph1->appendChild($doc->createTextNode($data->circulation[$i]));
			$graph1->appendChild($value_graph1);
			$value_graph2 = $doc->createElement("value");
			$value_graph2->setAttribute("xid",$i);
			$value_graph2->appendChild($doc->createTextNode($data->reach[$i]));
			$graph2->appendChild($value_graph2);
			$value_graph3 = $doc->createElement("value");
			$value_graph3->setAttribute("xid",$i);
			$value_graph3->appendChild($doc->createTextNode($data->hits[$i]));
			$graph3->appendChild($value_graph3);
		}
		$path = $this->_stats_fb->chart->path_data;
		$doc->save($path);
		$this->_stats_fb->swfobject_file = 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'swfobject.js';
		return $this->_stats_fb;
	}
	function getAmlineChartData(){
		$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amline'.DS.'data.xml';
		$this->_stats_fb->chart->path 		= 'chart'.DS.'amline'.DS;
		$this->_stats_fb->chart->setting 	= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amline'.DS.'settings.xml';
		$this->_stats_fb->chart->data		= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amline'.DS.'data.xml';
		$this->_stats_fb->chart->swf_file 	= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amline'.DS.'amline.swf';
		$this->_stats_fb->chart->name 		= 'amline';
		$this->_stats_fb->chart->path_data 	= $path;
	}
	function getAmColumnChartData($data){
		$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amcolumn'.DS.'data.xml';
		$this->_stats_fb->chart->path 		= 'chart'.DS.'amcolumn'.DS;
		$this->_stats_fb->chart->setting 	= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amcolumn'.DS.'settings.xml';
		$this->_stats_fb->chart->data		= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amcolumn'.DS.'data.xml';
		$this->_stats_fb->chart->swf_file 	= 'components'.DS.'com_obrss'.DS.'js'.DS.'chart'.DS.'amcolumn'.DS.'amcolumn.swf';
		$this->_stats_fb->chart->name 		= 'amcolumn';
		$this->_stats_fb->chart->path_data 	= $path;
	}
	function getListChart($chart){
		$select_l = '';
		$select_c = '';
		switch ($chart){
			case 'line_area'	: $select_l = "selected";
			break;
			case 'column_bar'	: $select_c = "selected";
			break;
			default				: $select_l = "selected";
			break;
		}
		$list_chart  = "<select name='list_chart'>";
		$list_chart .= "<option value='line_area' ".$select_l." >Line & Area</option>";
		$list_chart .= "<option value='column_bar' ".$select_c.">Column & Bar</option>";
		$list_chart .= "</select>";
		return $list_chart;
	}
	function clearCache($fid) {
		$dir	= JPATH_SITE.DS.'cache'.DS.'com_obrss'.DS;
		if(!is_dir($dir)){
			$this->RssMkDir($dir.'index.html');
			return;
		}
		jimport('joomla.filesystem.file');
		$caches	= JFolder::files($dir,$fid.'.xml$',false,true);
		for($i=0;$i<count($caches);$i++){
			JFile::delete($caches);
		}
	}
	function RssMkDir($file){
		jimport('joomla.filesystem.file');
		$dir = dirname($file);
		if(!JFolder::exists($dir)){
			$file = $dir.DS.'index.html';
			$html	= '<html><body bgcolor="#FFFFFF">&nbsp;</body></html><html>';
			if(!JFile::write($file,$html)) return false;
		}
		return true;
	}
	function checkDate($val,$name){
		$session	= JFactory::getSession();
		$oldVal = $session->get($name,'');
		$same	= true;
		if($oldVal!='' || $val != $oldVal ){
			$same = false;
		}
		$session->set($name, $val);
		 return $same;
	}
	
	public function getAddonOptions()
	{
		$db		= JFactory::getDBO();
		$qry 	= "SELECT `a`.`element` AS `value`, `a`.`element` AS `text` FROM #__extensions as `a` where `a`.`type`='plugin' and `a`.`folder`='obrss' and `a`.`enabled`=1";
		$db->setQuery($qry);
		$opts 	= $db->loadObjectList();
		return $opts;
	}
}