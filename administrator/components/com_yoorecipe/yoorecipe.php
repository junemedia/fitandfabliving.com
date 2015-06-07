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

// Require helpers files
JLoader::register('YooRecipeHelper', dirname(__FILE__).'/helpers/yoorecipe.php');
JLoader::register('JHtmlYooRecipeAdminUtils', dirname(__FILE__).'/helpers/html/yoorecipeadminutils.php');
JLoader::register('JHtmlYooRecipeCommonJsUtils', dirname(__FILE__).'/helpers/html/yoorecipecommonjsutils.php');
JLoader::register('JHtmlYooRecipeImpexUtils', dirname(__FILE__).'/helpers/html/impexutils.php');
JLoader::register('JHtmlYooCategory', dirname(__FILE__).'/helpers/html/yoocategory.php');

// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-yoorecipe {background-image: url(../media/com_yoorecipe/images/tux-48x48.png);}');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by YooRecipe
$controller = JControllerLegacy::getInstance('YooRecipe');
 
// Perform the Request task
$input 	= JFactory::getApplication()->input;
$controller->execute($input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();