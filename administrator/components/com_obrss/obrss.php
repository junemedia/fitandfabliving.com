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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_obrss'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

global $mainframe,$option,$isJ25,$obRootSite,$isJ3;
$obRootSite	= JURI::root();
$version	= new JVersion();
$obJVer		= $version->getShortVersion();
$isJ3 = ($version->RELEASE == '3.0' || $version->RELEASE == '3.1');
$isJ25	= substr($obJVer, 0,3) == '2.5';
$controller = JRequest::getVar('controller','cpanel');
$task		= JRequest::getVar('task');
$option = 'com_obrss';

if ($isJ25) {
	jimport( 'joomla.application.component.controller' );
	jimport( 'joomla.application.component.model');
	jimport( 'joomla.application.component.view');

	class obController	extends JController {}
	class obModel		extends JModel {}
	class obView		extends JView {}
}else{
	class obController	extends JControllerLegacy {}
	class obModel		extends JModelLegacy {}
	class obView		extends JViewLegacy {}
}

$document = JFactory::getDocument();
$document->addStyleSheet('components/'.$option.'/assets/obstyle.css');
if ($isJ25) {
	$document->addStyleSheet(JURI::root().'/components/'.$option.'/assets/jui/css/icomoon.css');
	$document->addStyleSheet(JURI::root().'/components/'.$option.'/assets/jui/css/chosen.css');
	$document->addStyleSheet(JURI::root().'/components/'.$option.'/assets/jui/css/bootstrap.css');
	$document->addStyleSheet(JURI::root().'/components/'.$option.'/assets/jui/css/bootstrap-extended.css');
	$document->addScript(JURI::root().'/components/'.$option.'/assets/jui/js/jquery.min.js');
	$document->addScript(JURI::root().'/components/'.$option.'/assets/jui/js/jquery-noconflict.js');
	$document->addScript(JURI::root().'/components/'.$option.'/assets/jui/js/chosen.jquery.min.js');
	$document->addScript(JURI::root().'/components/'.$option.'/assets/jui/js/bootstrap.min.js');
}

if( !$isJ25 && $controller=='cpanel' ) {
	$option		= 'com_obrss';
	$mainframe	= JFactory::getApplication();
	$uri = (string) JUri::getInstance();
	$return = urlencode(base64_encode($uri));
	jimport( 'joomla.html.parameter' );
// 	JSubMenuHelper::addEntry(JText::_('OBRSS_DASHBOARD'), 'index.php?option='.$option.'&controller=cpanel',($controller=='cpanel'));
// 	JSubMenuHelper::addEntry(JText::_('OBRSS_FEED_MANAGER'), 'index.php?option='.$option.'&controller=feed',($controller=='feed'));
// 	JSubMenuHelper::addEntry(JText::_('OBRSS_ADDONS'), 'index.php?option=com_installer&view=manage&filter_type=plugin&filter_group=obrss');
// 	JSubMenuHelper::addEntry(JText::_('COM_OBRSS_SUBMENU_CONFIGURATION'), 'index.php?option=com_config&view=component&component=com_obrss&return='.$return);
}
// load base controller
require_once(JPATH_COMPONENT.DS.'defines.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Ola
$controller = JControllerLegacy::getInstance('obrss');
$cname = JRequest::getVar('controller','cpanel');
if(file_exists(JPATH_COMPONENT.DS.'controllers'.DS.$cname.'.php')){
	require JPATH_COMPONENT.DS.'controllers'.DS.$cname.'.php';
}
if($cname=='cpanel'){
	require JPATH_COMPONENT.DS.'controllers'.DS.'default.php';
}
$class_name = 'ObRSSController'.$cname;
$controller = new $class_name();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>