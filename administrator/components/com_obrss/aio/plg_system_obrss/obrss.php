<?php
/**
 * @version		$Id: obrss.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define ('DS',DIRECTORY_SEPARATOR);

jimport('joomla.plugin.plugin');

class plgSystemobrss extends JPlugin {
	var $_plugin;
	
	/**
	 * Constructor
	 *
	 * @return plgSystemJLord_Rss
	 */
	function plgSystemobrss(& $subject) {
		parent::__construct($subject);
		$this->_plugin 		=  JPluginHelper::getPlugin('system','obrss');
	} // end plgSystemJLord_Rss
	
	function onAfterRoute() {
		//echo "hello world";
		$obRouter	= JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'router.php';
		if(!is_file($obRouter)) return;
		require_once $obRouter;
		$database 	= JFactory::getDBO();
		$query 		= "SELECT `id`, `name`,`alias`, `use_feedburner`, `uri`, `feed_type` FROM `#__obrss` WHERE `published` = 1 AND `feeded` = 1 ORDER BY `ordering`";
		
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		
		// get view_feedburner configuration
		$params = JComponentHelper::getParams('com_obrss');
		$config = $params->get('view_feedburner');
		
		$mainframe	= JFactory::getApplication();
		if(!$mainframe->isAdmin()){
			// call itemshelper
			require_once(JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'itemshelper.php');
			for($i = 0; $i<count($rows);$i++){
				$format = itemsHelper::getFeedTypePrefix($rows[$i]->feed_type);
				$url	= obRSSUrl::Sef('index.php?option=com_obrss&task=feed&id='.$rows[$i]->id.':'.$rows[$i]->alias.'&format='.$format);
						//= obRSSUrl::Sef('index.php?option=com_obrss&task=feed&id='.$item->id.':'.$item->alias.'&format='.$format);
				if($rows[$i]->use_feedburner == 1){
					$url 	= 'http://feeds.feedburner.com/'.$rows[$i]->uri;
				} else { 
					if($rows[$i]->use_feedburner == 2){
						if((int)$config == 1) $url 	= 'http://feeds.feedburner.com/'.$rows[$i]->uri;
					}
				}
				$feed_type = $rows[$i]->feed_type;
				$rss_xml_array = array("RSS2.0", "RSS1.0", "RSS0.9");
				$atom_xml_array = array("ATOM", "ATOM0.3");
				$json_array = array("JSON");
				$html_array = array("HTML");
				if (in_array($feed_type, $rss_xml_array)) {
					$feed_type_type = "application/rss+xml";
				} elseif(in_array($feed_type, $atom_xml_array)) {
					$feed_type_type = "application/atom+xml";
				} elseif (in_array($feed_type, $json_array)) {
					$feed_type_type = "application/json";
				} else {
					$feed_type_type = "text/html";
				}
				$html	= '<link href="'.$url.'" rel="alternate" type="'.$feed_type_type.'" title="'.$rows[$i]->name.'" />';
				$document= JFactory::getDocument();
				if($document->getType() == 'html') {
					$document->addCustomTag($html);
				}
			}
		}
		$view			= JRequest::getVar('view');
		$option			= JRequest::getVar('option');
		$filters_type	= JRequest::getVar('filters_type');
		$filters_group	= JRequest::getVar('filters_group');
		//com_installer&view=manage&filters_type=plugin&filters_group=obrss
// 		Add a back button.
		if( $option == 'com_installer' && $view=='manage') {
			$jv = new JVersion();
			$isJ25 = ($jv->RELEASE=='2.5');
			if($isJ25){
				$res = $mainframe->getUserStateFromRequest('com_obrss.fillter', 'filters',array(),'ARRAY');
				$type = $res['type'];
				$group = $res['group'];
				if($type=='plugin'&&$group=='obrss'){
					JToolBarHelper::back('obRSS','index.php?option=com_obrss');
					JToolBarHelper::divider();
				}
			}else{
// 				index.php?option=com_installer&view=manage&filter_type=plugin&filter_group=obrss
				$mainframe = JFactory::getApplication();
				if(isset($_REQUEST['filter_type'])){
					$type 	= $mainframe->getUserStateFromRequest('com_obrss_fillter_type', 'filter_type','','string');
				}
				if(isset($_REQUEST['filter_group'])){
					$group 	= $mainframe->getUserStateFromRequest('com_obrss_fillter_group', 'filter_group','','string');
				}
				$type = $mainframe->getUserState('com_obrss_fillter_type');
				$group = $mainframe->getUserState('com_obrss_fillter_group');
				if($type=='plugin'&&$group=='obrss'){
					$bar = JToolBar::getInstance('toolbar');
					$bar->appendButton('Link', 'feed','obRSS','index.php?option=com_obrss');
					$content = ".icon-feed{color:#faa732;}";
					$document= JFactory::getDocument();
					$document->addStyleDeclaration($content);
					JToolBarHelper::divider();
				}
			}
		}
	}
}