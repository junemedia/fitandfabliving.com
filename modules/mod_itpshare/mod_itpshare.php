<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPShare
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPShare is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

JLoader::register('ItpShareHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$doc = JFactory::getDocument();
/** $doc JDocumentHTML **/

// Loading style.css
if($params->get("loadCss")) {
    $doc->addStyleSheet("modules/mod_itpshare/style.css");
}

// URL
$url    = JURI::getInstance()->toString();
$title  = $doc->getTitle();

// Convert the url to short one
if($params->get("shortener_service")) {
	$url = ItpShareHelper::getShortUrl($url, $params);
}
        
$title  = JString::trim($title);
require JModuleHelper::getLayoutPath('mod_itpshare', $params->get('layout', 'default'));