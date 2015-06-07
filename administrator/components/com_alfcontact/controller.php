<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * General Controller of AlfContact component
 */

class AlfcontactController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
    function display($cachable = false, $urlparams = false) 
    {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'Alfcontacts'));
 
        // call parent behavior
        parent::display($cachable, $urlparams);
    }
}
