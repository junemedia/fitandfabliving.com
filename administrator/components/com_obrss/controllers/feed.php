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
// jimport( 'joomla.application.component.controller' );
// Set the table directory
JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables' );
class obRSSControllerFeed extends obController
{
	/**
	 * Custom Constructor
	 */
	function __construct( $default = array()) {
		parent::__construct( $default );
		$this->registerTask( 'apply', 'save');
		$this->registerTask( 'save2new', 'save');
		$this->registerTask( 'save2copy', 'save');
		$this->registerTask( 'unpublish', 'publish');
		$this->registerTask( 'unfeeded'	, 'feeded');
		$this->registerTask( 'orderup'	, 'orderdown');
		$this->registerTask( 'preview'	, 'display');
		$this->registerTask( 'edit'		, 'display');
		$this->registerTask( 'add'		, 'display' );
		$this->registerTask( 'undisplay_feed_module', 'display_feed_module');
		$this->registerTask( 'add_fb'   , 'displayFeedburner');
		$this->registerTask( 'edit_fb'  , 'displayFeedburner');
		$this->registerTask( 'save_fb'  , 'saveFeedburner');		
		$this->registerTask( 'view_stats_fb','displayFeedburnerStats');
	}

	function abc(){
		$db = JFactory::getDBO();
		$qry	= "SELECT * FROM `#__obrss` LIMIT 1";
		$db->setQuery($qry);
		$obFeeds = $db->LoadObject();
		if ($obFeeds) {
			$msg =  'Exist';
			if (!isset($obFeeds->uri)) {
				$qry = "ALTER IGNORE TABLE `#__obrss` 
						ADD `uri` VARCHAR( 255 ) NOT NULL AFTER `hits` ,
						ADD `use_feedburner` tinyint(1) NOT NULL default '2' AFTER `uri`";
				$db->setQuery($qry);
				$a= $db->query();
				if($a){
				$msg =  'Success';
				}else $msg =  'Fail';
			}
		}else $msg =  'no exit';
		exit($msg);
	}
	/**
	 * Method to show the search view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display($cachable = false, $urlparams = array()) {
		$mainframe = JFactory::getApplication();
		$vName = 'feed';
		$mName = 'feed';
		switch( $this->getTask() ) {
			case 'add':
				$vLayout = JRequest::getCmd( 'layout', 'form' );
				JRequest::setVar( 'edit', false  );
				break;
			case 'edit':
			case 'add':
				
				JRequest::setVar( 'hidemainmenu', 1 );
				$vLayout = JRequest::getCmd( 'layout', 'form' );
				JRequest::setVar( 'edit', true  );
				break;
			default:
				$vName 		= 'feed';
				$vLayout   	= 'default';
				break;
			}
		$document = JFactory::getDocument();
		$vType		= $document->getType();
		// Get/Create the view
		$view = $this->getView( $vName, $vType);
		// Get/Create the model
		if ($model = $this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		// Set the layout
		$view->setLayout($vLayout);
		// Display the view
		$view->display();
	}//end display
	function save(){
		$mod	= $this->getModel('feed');
		$mod->save();
	}
	
	
	function copy(){
		$mainframe = JFactory::getApplication();
		$cid		= JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$mod	= $this->getModel('feed');
		$msg	= '';
		for($i=0;$i<count($cid);$i++) {
			$msg	.= "<br />".$mod->copy($cid[$i]);
		} 
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed',$msg);
	}
	
	
	function remove() {
		$mainframe = JFactory::getApplication();
		//$db     	= JFactory::getDBO();
		//$option	= JRequest::getCmd( 'option' );
		$cid		= JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		foreach ($cid as $id) {
			$row = & JTable::getInstance(OB_TABLE_RSS,'Table');
			$row->load( $id );
			$row->delete();
		}
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed');
	}
	
	
	function cancel() {
		$mainframe=JFactory::getApplication();
		//$option	= JRequest::getCmd( 'option' );
		$id		= JRequest::getVar( 'id', 0, '', 'int' );
		if ($id) {
			$row = JTable::getInstance(OB_TABLE_RSS,'Table');
			$row->load($id);
			$row->checkin();
		}
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed' );
	}//end cancel
	
	
	function publish() {
		$mainframe = JFactory::getApplication();
		$db   		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$option		= JRequest::getCmd( 'option' );
		$cid		= JRequest::getVar( 'cid', array(), '', 'array' );
		$state		= ( $this->getTask() == 'publish' ? 1 : 0 );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			$action = $state ? JText::_( "OBRSS_PUBLISHED" ) : JText::_( "OBRSS_UNPUBLISHED" );
			JError::raiseError(500, JText::_( "OBRSS_SELITEM" .$action, true ) );
		}
		$total	= count ( $cid );
		$cids	= implode( ',', $cid );
		$query = 'UPDATE #__'.OB_TABLE_RSS.''
		. ' SET published =' . (int) $state
		. ' WHERE id IN ( '. $cids .' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$db->setQuery( $query );
		if ( !$db->query() ) {
			JError::raiseError(500, $db->getErrorMsg() );
		}
		if (count( $cid ) == 1) {
			$row = & JTable::getInstance(OB_TABLE_RSS,'Table');
			$row->checkin( $cid[0] );
		}
		switch ($state) {
			case 1:
				$msg = $total . JText::_( "OBRSS_ITEMSP" );
				break;
			case 0:
			default:
				$msg = $total . JText::_( "OBRSS_ITEMSUP" );
				break;
		}
		$cache = & JFactory::getCache($option);
		$cache->clean();
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed', $msg);
	}//end publish
	
	
	function orderdown() {
		$mainframe = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		// Initialize variables
		$option	= JRequest::getCmd( 'option' );
		//$db		= & JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		if (isset( $cid[0] )) {
			$row = & JTable::getInstance(OB_TABLE_RSS, 'Table');
			$row->load( (int) $cid[0] );
			if($this->getTask() == 'orderdown') {
				$row->move(1, 0);
			} else {
				$row->move(-1, 0);
			}
			$cache = & JFactory::getCache($option);
			$cache->clean();
		}
		$mainframe->redirect('index.php?option=com_obrss&controller=feed');
	}//end orderdown
	
	
	function saveOrder() {
		$mainframe = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		// Initialize variables
		$option		= JRequest::getCmd( 'option' );
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		// Instantiate an article table object
		$row = JTable::getInstance(OB_TABLE_RSS,'Table');
		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load( (int) $cid[$i] );
			if ( $row->ordering != $order[$i] ) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}
		$cache = & JFactory::getCache($option);
		$cache->clean();
		$row->reorder( );
		$msg = JText::_( 'OBRSS_SAVE_ORDER' );
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed', $msg );
	}//end saveOrder
	
	public function saveOrderAjax(){
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		// Initialize variables
		$option		= JRequest::getCmd( 'option' );
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		// Instantiate an article table object
		$row = JTable::getInstance(OB_TABLE_RSS,'Table');
		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++) {
			$row->load( (int) $cid[$i] );
			if ( $row->ordering != $order[$i] ) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}
		$cache = JFactory::getCache($option);
		$cache->clean();
		$row->reorder( );
		JFactory::getApplication()->close();
	}
	
	
	function feeded() {
		$mainframe = JFactory::getApplication();
		$db   		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$option		= JRequest::getCmd( 'option' );
		$cid		= JRequest::getVar( 'cid', array(), '', 'array' );
		$state_feed	= ( $this->getTask() == 'feeded' ? 1 : 0 );
		JArrayHelper::toInteger($cid);
		$total	= count ( $cid );
		$cids	= implode( ',', $cid );
		$query = 'UPDATE #__'.OB_TABLE_RSS.''
		. ' SET feeded =' . (int) $state_feed
		. ' WHERE id IN ( '. $cids .' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg() );
		}
		if (count( $cid ) == 1) {
			$row = JTable::getInstance(OB_TABLE_RSS,'Table');
			$row->checkin( $cid[0] );
		}
		switch ($state_feed) {
			case 1:
				$msg = $total . JText::_( "OBRSS_ITEMSF" );
				break;
			case 0:
			default:
				$msg = $total . JText::_( "OBRSS_ITEMSUF" );
				break;
		}
		$cache = JFactory::getCache($option);
		$cache->clean();
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed', $msg);
	}//end feeded
	
	
	function display_feed_module() {
		$mainframe 		= JFactory::getApplication();
		$db   			= JFactory::getDBO();
		$user 			= JFactory::getUser();
		$option			= JRequest::getCmd( 'option' );
		$cid			= JRequest::getVar( 'cid', array(), '', 'array' );
		$state_display	= ( $this->getTask() == 'display_feed_module' ? 1 : 0 );
		JArrayHelper::toInteger($cid);
		$total	= count ( $cid );
		$cids	= implode( ',', $cid );
		$query = 'UPDATE #__'.OB_TABLE_RSS
		. ' SET display_feed_module =' . (int) $state_display
		. ' WHERE id IN ( '. $cids .' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg() );
		}
		if (count( $cid ) == 1) {
			$row = JTable::getInstance(OB_TABLE_RSS,'Table');
			$row->checkin( $cid[0] );
		}
		switch ($state_display) {
			case 1:
				$msg = $total . JText::_( "OBRSS_ITEMSD" );
				break;
			case 0:
			default:
				$msg = $total . JText::_( "OBRSS_ITEMSUD" );
				break;
		}
		$cache = & JFactory::getCache($option);
		$cache->clean();
		$mainframe->redirect( 'index.php?option=com_obrss&controller=feed', $msg);
	}//end display_feed_module
	
	
	/*
	 * $version: current version
	 * $url: now must be www.foobla.com
	 * $pc: product code
	 */
	function checkversion( $version, $url, $pc ) {
		$mainframe = JFactory::getApplication();
		$arr_version = split('\.', $version);
		$fp = @fsockopen($url, 80, $errno, $errstr, 30);
		if (!$fp) {
			return ;
		} else {
			$out = "GET /index.php?option=com_jlord_checkversion&view=checkversion&pc=$pc&version=$version&s=".base64_encode(JURI::root())." HTTP/1.1\r\n";
			$out .= "Host: $url\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			$contentf = '';
			while (!feof($fp)) {
				$contentf .= fgets($fp);
			}
			preg_match('/<version>(.*?)<\/version>/', $contentf, $match);
			if (!$match) {
				$lastversion = '0';
			} else {
				$lastversion=$match[1];
			}
			fclose($fp);
		}
		$arr_lastversion = split('\.', $lastversion);
		for ($i=0; $i< count($arr_lastversion); $i++) {
			if ((isset($arr_version[$i]))&&(intval($arr_version[$i])>intval($arr_lastversion[$i]))) {
				return false;
			}
			if ((!isset($arr_version[$i]))||(intval($arr_version[$i])<intval($arr_lastversion[$i]))) {
				return true;
			}
		}
		return false;
	}//end checkversion
	
	
	function resethits(){
		$mainframe = JFactory::getApplication();
		// Initialize variables
		$db	= & JFactory::getDBO();
		$id = JRequest::getCmd( 'id' );
		// Instantiate and load an article table
		$row = & JTable::getInstance(OB_TABLE_RSS, 'Table');
		$row->load($id);
		$row->hits = 0;
		$row->store();
		$row->checkin();
		$msg = JText::_('Successfully Reset Hit count');
		$mainframe->redirect('index.php?option=com_obrss&controller=feed&task=edit&cid[]='.$id, $msg);
	}
	
	
	function listcom(){
		$db	= & JFactory::getDBO();
		$qry = "SELECT `id`,`name`,`option`FROM `#__components` WHERE `parent` =0 AND `id` >30 ORDER BY `id`";
		$db->setQuery($qry);
		$rows = $db->LoadObjectList();
		#echo '<pre>'.$qry;print_r($rows);echo '</pre>';
	}
	
	
	function convert(){
		/*$db	= & JFactory::getDBO();
		$qry = "SELECT * FROM `#__jlord_rss` ORDER BY `id`";
		$db->setQuery($qry);
		$oldFeeds = $db->LoadObjectList();
		echo '<pre>'.$qry;print_r($rows);echo '</pre>';return; */
		$convertPath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'feedconvert.php';
		if(is_file($convertPath)){
			require_once $convertPath;
			$feedConvert = new feedConvert;
			$code = $feedConvert->start();
			if($code>0){echo " [cvc: $code]";}
		}
	}
	
	
	function feedautomake(){
		$creAll	= true;
		$catIds = array();
		$db	= JFactory::getDBO();
		$qry	= '
			SELECT s.id sId,s.title sTitle,s.alias sAlias,c.id cId,c.title cTitle,c.alias cAlias 
			FROM #__categories AS c LEFT JOIN  #__sections AS s ON c.section = s.id 
			WHERE c.published = 1 AND s.published =1
			ORDER BY s.id,c.id';
		$db->setQuery($qry);
		$rows	= $db->LoadObjectList();
		$secCats= array();
		$feeds	= array(); 
		for($i=0;$i< count($rows);$i++){
			$row = $rows[$i];
			$secCats[$row->sId][] = $row->cId;
			if(!$creAll && !in_array($row->cId,$catIds)) continue;
			$feeds[$row->cId]->title = $row->sTitle.' - '.$row->cTitle;
			$feeds[$row->cId]->alias = JFilterOutput::stringURLSafe($row->sAlias.'-'.$row->cAlias);
		}
		foreach ($secCats as $sec => $cats){
			$catsArr = $cats;
			foreach ($cats as $cat){
				if(!$creAll && !in_array($cat,$catIds)) continue;
				$catStr = array();
				foreach ($catsArr as $alem)if($alem != $cat) $catStr[] = $alem;
				$feeds[$cat]->catids		= implode('|',$catStr);
				$feeds[$cat]->section_ids	= $sec;
			}
		}
		$date 		= JFactory::getDate();
		$created	= $date->toMySQL();
		$user = JFactory::getUser();
		$created_by = $user->id;
		$paramsComponent = "frontpage=0\nexcludearticle=\ntext=introtext\norderby=rdate\norderby_date=created";
		$feedVal	= array();
		foreach ($feeds as $feed){
			$paramsCom = $paramsComponent."\nsections= $feed->section_ids\ncategories=$feed->catids";
			$feedVal[] = "('$feed->title', '$feed->alias', '$feed->title', '1', '0', '0', 'content.xml','$paramsCom', '$created', '$created_by')\n";
		}
		$feedVal = implode(',',$feedVal);
		$qry = "
			INSERT INTO `#__".OB_TABLE_RSS."`
				(`name`, `alias`,`description`,`published`, `feeded`, `display_feed_module`, `components`,`paramsforowncomponent`, `created`, `created_by`)
			VALUES $feedVal";
		$db->setQuery($qry);
		if(!$db->query()){
			$msg = 'Auto create Feed fail!'.$db->getErrorMsg();
		}else $msg = 'Auto create Feed success: Created '.count($feeds).' feed!';
		$mainframe = JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_obrss&controller=feed', $msg);
	}	
	function displayFeedburner(){
		$vName = 'feed';
		$mName = 'feed';
		switch( $this->getTask() ) {
			case 'add_fb':
				$vLayout = JRequest::getCmd( 'layout', 'feedburner' );
				JRequest::setVar( 'edit', false  );
				break;
		    case 'edit_fb':
				$vLayout = JRequest::getCmd( 'layout', 'feedburner' );
				JRequest::setVar( 'edit', true  );
		        break;
			default:
				$vName 		= 'feed';
				$vLayout   	= 'default';
				break;
		}
		$document = JFactory::getDocument();
		$vType		= $document->getType();
		// Get/Create the view
		$view = $this->getView($vName,$vType);
		// Get/Create the model
		if ($model = $this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		// Set the layout
		$view->setLayout($vLayout);
		// Display the view
		$view->display_feedburner();
	}//end display
	function displayFeedburnerStats(){
		$vName = 'feed';
		$mName = 'feed';
		$vLayout     = 'fb_statistics';
		JRequest::setVar( 'hidemainmenu', 1 );
		$document    = JFactory::getDocument();
		$vType       = $document->getType();
		$view 		 = &$this->getView($vName,$vType);
		if($model = &$this->getModel($mName)){
			$view->setModel($model,true);
		}
		$view->setLayout($vLayout);
		$view->display_feedburner_stats();
	}
	function saveFeedburner(){
		$mod	= $this->getModel('feed');
		$mod->saveFeedBurner();
		$mainframe = JFactory::getApplication();
		$cid	= JRequest::getInt('cid',1);
		$msg	= 'Save success!';
		$url	= 'index.php?option=com_obrss&controller=feed';
		$url	.= "&task=edit_fb&cid={$cid}&tmpl=component";
		$mainframe->redirect($url, $msg);
	}
}//end class