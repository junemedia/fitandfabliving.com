<?php 
/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license		GNU/GPL, see LICENSE
 */

// Check to ensure this file is included in Joomla!
// Set flag that this is a parent file
define( '_JEXEC', 1 );
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../../..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');
jimport('joomla.html.parameter');
$jlang = JFactory::getLanguage();
$jlang->load('mod_jl_articles_slideshow', JPATH_SITE, null, true);

$moduleid	= JRequest::getInt('moduleid','0');
$catid		= 0;
if( $moduleid ){
	$table = JTable::getInstance('module');
	$table->load($moduleid);
	if( $table->id==$moduleid ){
		$params = class_exists('JParameter') ? new JParameter($table->params) : new JRegistry($table->params);
		$catid	= $params->get('catid');
		if( !is_array($catid) ) {
			$catid = array($catid);
		}
	}
}


if (!class_exists('VmConfig'))
    require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');

if (!class_exists('ShopFunctions'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');
if (!class_exists('TableCategories'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'categories.php');

if (!class_exists('VmElements'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'elements' . DS . 'vmelements.php');

//$html = '<option value="0">' . JText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL') . '</option>';
$html = ShopFunctions::categoryListTree($catid);

echo $html;exit;
?>