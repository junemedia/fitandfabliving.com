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
global $mainframe,$option,$isJ25,$obRootSite;
$obRootSite	= JURI::root();
$version	= new JVersion();
$obJVer		= $version->getShortVersion();
$isJ25	= substr($obJVer,0,3) == '2.5';
// if(!$isJ25){
	$option	= 'com_obrss';
	$mainframe	= JFactory::getApplication();
	jimport( 'joomla.html.parameter' );
// }

$document = JFactory::getDocument();
$document->addStyleSheet('components/'.$option.'/assets/obstyle.css');
if ($isJ25) {
	// load JUI stylesheet & javascript
	$document->addStyleSheet('components/'.$option.'/assets/jui/css/bootstrap.css');
	$document->addStyleSheet('components/'.$option.'/assets/jui/css/bootstrap-extended.css');
	$document->addStyleSheet('components/'.$option.'/assets/jui/css/icomoon.css');
// 	$document->addStyleSheet('components/'.$option.'/assets/jui/css/chosen.css');
// 	$document->addScript('components/'.$option.'/assets/jui/js/jquery.min.js');
// 	$document->addScript('components/'.$option.'/assets/jui/js/jquery-noconflict.js');
// 	$document->addScript('components/'.$option.'/assets/jui/js/chosen.jquery.min.js');
// 	$document->addScript('components/'.$option.'/assets/jui/js/bootstrap.min.js');
}

$glConfig 	= JFactory::getConfig();
$lang	= substr($glConfig->get('config.language'),0,2);

$language	= JFactory::getLanguage();
$language->load('com_obrss');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'defines.php');
// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
require_once JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'router.php';
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
// Create the controller
$classname	= 'obrssController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
// Redirect if set by the controller
$controller->redirect();

?>
