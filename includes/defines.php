<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! Application define.
 */

//Global definitions.
//Joomla framework path definitions.
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

//Defines.
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));

define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . '/administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . '/libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . '/plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . '/installation');
define('JPATH_THEMES',        JPATH_BASE . '/templates');
define('JPATH_CACHE',         JPATH_BASE . '/cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . '/manifests');
if(!defined('DS')) define('DS', '/');

function leon_debug_check()
{
    $local = array(
            '127.0.0.1',                // Local
            '60.216.3.163',             // E5 Jinan Office
            '66.54.186.254',           // SC Chicago office
            '10.10.13.240'                 // E5 Jinan Office internal IP
            );
    $user = array('leonzw');
    $is_user = $is_local = false;
    if(isset($_SERVER['PHP_AUTH_USER']) && array_search($_SERVER['PHP_AUTH_USER'], $user) !== false) $is_user = true;
    if(isset($_SERVER['REMOTE_ADDR']) && array_search($_SERVER['REMOTE_ADDR'], $local) !== false) $is_local = true;
    
    //if($is_user && $is_local){
    if($is_user && $is_local){
        define('LEON_DEBUG', true);
    }else{
        define('LEON_DEBUG', false);
    }
}

leon_debug_check();
require_once(dirname(__FILE__) . "/functions.php");
