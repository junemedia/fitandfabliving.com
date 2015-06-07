<?php

/**
 * @package		SP Upgrade
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access to this file
defined('_JEXEC') or die;

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * SPUpgrades Controller
 */
class SPUpgradeControllerExtensions extends JControllerAdmin {
    

    function transfer() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $factory = new CYENDFactory();
        $source = new CYENDSource();

        //Get task ids
        $ids = JRequest::getVar('cid', array(), '', 'array');                
        $task_ids = JRequest::getVar('task_ids', array(), '', 'array');
        //Validate Input IDs        
        $input_ids = JRequest::getVar('input_ids', array(), '', 'array');                                        
        $input_ids = $this->validateInputIDs(JRequest::getVar('input_ids', array(), '', 'array'));
        if (!$input_ids) {
            $this->setRedirect('index.php?option=com_spupgrade&view=extensions', JText::_('COM_SPUPGRADE_MSG_ERROR_INVALID_IDS_BATCH'), 'error');
            return false;
        }

        //Initial tasks
        //Disable warnings
        error_reporting(E_ERROR | E_PARSE);
        set_time_limit(0);

        //monitor log        
        //$message = '';
        $factory->writeLog('<META HTTP-EQUIV="REFRESH" CONTENT="15">', 'w'); // create monitor log        
        $factory->writeLog('<link rel="stylesheet" href="'.JURI::base().'templates/isis/css/template.css" type="text/css" />');
        $factory->writeLog('<div class="well well-large">');
        $factory->writeLog('<p>* This log will be automatically refreshed every 15 seconds.</p>');
        $factory->writeLog('<h1>' . JText::_(COM_SPUPGRADE_START) . '</h1>');

        // Connect to source db
        if (!$source->testConnection()) {
            $this->setRedirect('index.php?option=com_spupgrade', JText::_("COM_SPUPGRADE_MSG_ERROR_CONNECTION"), 'error');
            return false;
        }
        
        // Get the model.
        $model = $factory->getModel('Extensions', 'SPUpgradeModel'); 

        //Main Loop
        foreach ($ids as $i => $id) {
            if (!($item = $model->getItem($id)))
                JError::raiseWarning(500, $model->getError());

            $modelContent = $factory->getModel($item->extension_name, 'SPUpgradeModel');
            $tables = $modelContent->tables($item->extension_name);
            if (!$tables) {
                $this->setRedirect('index.php?option=com_spupgrade&view=extensions', JText::_($modelContent->getError()), 'error');
                return false;
            }

            $message = ('<h2>' . JText::_($item->extension_name) . '</h2>');
            $factory->writeLog($message);

            foreach ($tables as $table) {
                $modelContent->init($item->extension_name, $table);                
                if ($table != 'media') {                                        
                    if(!$modelContent->setTable($table)) {                        
                        continue;
                    }                                       
                    $modelContent->transfer($input_ids[$i], $table);                    
                } else {
                    $modelContent->media();                    
                }
            }            
        }

        // Finish
        //enable warnings
        error_reporting(E_ALL);
        set_time_limit(30);        
        $factory->writeLog('<h1>' . JText::_('COM_SPUPGRADE_COMPLETED') . '</h1>');
        $factory->writeLog('</div>');
        $this->setRedirect('index.php?option=com_spupgrade&view=monitoring_log', JText::_("COM_SPUPGRADE_COMPLETED"));
    }

    function validateInputIDs($input_ids) { 
        foreach ($input_ids as $i => $ids) {                        
            if (!is_numeric($ids) && $ids != "")
                return false;
        }
        return $input_ids;
    }

}
