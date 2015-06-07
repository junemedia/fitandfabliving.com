<?php

/* @package Jmodules LikeBox for Joomla 3.0!  
 * @link       http://jmodules.com/ 
 * @copyright (C) 2012- Sean Casco
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once (dirname(__FILE__).'/helper.php');

$input = jmodlbox::getinput($params);

require(JModuleHelper::getLayoutPath('mod_jmodlbox'));