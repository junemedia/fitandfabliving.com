<?php
/**
 * @package	foobla RSS Feed Creator for Joomla.
 * @created: Setember 2008.
 * @updated: 2009/06/30
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author	foobla
 * @license	GNU/GPL, see LICENSE
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
// ------------------  standard plugin initialize function - don't change ---------------------------
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  load language file - adjust as needed ----------------------------------------
$db = &JFactory::getDBO();
// $query	= "SELECT `value` FROM `#__obrss_config` WHERE `name` = 'component'";
// $db->setQuery($query);
// $comSef	= $db->loadResult();
$params = JComponentHelper::getParams('com_obrss');
$comSef = $params->get('component');
$title[]	= $comSef?$comSef:str_replace('com_obrss_', '', $option);
$title[]	= $lang;

$task = isset($task) ? @$task : null;
switch(@$task) {
	case 'feed':
		$title[] = '';
		break;
}
$view = isset($view) ? @$view : null;
switch(@$view) {
	case 'feed':
		$title[] = '';
		break;
}

// fetch contact name
if (!empty($id)) {
	$query  = "SELECT `alias`,`id` FROM #__obrss WHERE `id`=$id";
	$database->setQuery( $query );
	if (!shTranslateUrl($option, $shLangName)) // V 1.2.4.m
		$result = $database->loadObject(false);
	else $result = $database->loadObject();	
	
	if (!empty($result)) $title[] = $result->alias;
	else $title[] = $id;
}
$title[] = '/';

shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($view))
	shRemoveFromGETVarsList('view');
if (!empty($task))
	shRemoveFromGETVarsList('task');
shRemoveFromGETVarsList('id');
if (!empty($Itemid))
	shRemoveFromGETVarsList('Itemid');

// ------------------  standard plugin finalize function - don't change --------------------------- 
if ($dosef){
	$string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
		(isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
		(isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------	