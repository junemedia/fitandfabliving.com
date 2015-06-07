<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Get an instance of the controller prefixed by AlfContact
$controller = JControllerLegacy::getInstance('Alfcontact');
 
// Perform the Request task
//$controller->execute(JRequest::getCmd('task'));
$controller->execute(JFactory::getApplication()->input->getCmd('task')); 

// Redirect if set by the controller
$controller->redirect();
