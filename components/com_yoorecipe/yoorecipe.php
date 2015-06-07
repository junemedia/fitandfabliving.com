<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

// require helpers files
JLoader::register('JHtmlYooRecipeUtils', dirname(__FILE__) .'/helpers/html/yoorecipeutils.php');
JLoader::register('JHtmlYooRecipeAdminUtils', JPATH_ADMINISTRATOR.'/components/com_yoorecipe/helpers/html/yoorecipeadminutils.php');
JLoader::register('JHtmlYooRecipeJsUtils', dirname(__FILE__) .'/helpers/html/yoorecipejsutils.php');
JLoader::register('JHtmlYooRecipeCommonJsUtils', JPATH_ADMINISTRATOR.'/components/com_yoorecipe/helpers/html/yoorecipecommonjsutils.php');
JLoader::register('JHtmlYooRecipeHelperRoute', dirname(__FILE__) .'/helpers/html/yoorecipehelperroute.php');
JLoader::register('JHtmlYooRecipeIcon', dirname(__FILE__) .'/helpers/html/yoorecipeicon.php');

// Load admin language file
$lang = JFactory::getLanguage();
$extension = 'com_yoorecipe';
$base_dir = JPATH_ADMINISTRATOR;

$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);
$lang->load('', $base_dir, $language_tag, $reload);

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by YooRecipe
$controller = JControllerLegacy::getInstance('YooRecipe');
 
// Perform the Request task
$input 	= JFactory::getApplication()->input;
$controller->execute($input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();