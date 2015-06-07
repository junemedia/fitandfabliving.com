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

// import the Joomla modellist library
jimport('joomla.application.component.model');

class SPUpgradeModelCom_ComProfiler extends SPUpgradeModelExtension {
    
    public function setTable($name) {
        $factory = $this->factory;

        $prefix = $this->params->get('source_db_prefix');

        //Exit if empty table
        $source_table_name = $prefix . $name;

        // Init
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $source_db = $this->source_db;
        $source_query = $this->source_query;

        //Define destination table name
        $destination_table_name = $destination_db->getPrefix() . $name;

        // Get tables descriptions
        $query = 'SHOW CREATE TABLE ' . $source_table_name;

        $source_db->setQuery($query);
        if (!CYENDFactory::execute($source_db)) {
            $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $source_db->getErrorMsg()) . '</font></b>';
            $factory->writeLog($message);
            return false;
        }
        $source_table_desc = $source_db->loadObject();
        $source_table_desc_new = $source_table_desc;

        $query = 'describe ' . $destination_table_name;
        $destination_db->setQuery($query);
        if (!CYENDFactory::execute($destination_db)) {
            //Create table
            $query = $source_table_desc->{'Create Table'};
            $query = str_replace('CREATE TABLE `' . $source_table_name, 'CREATE TABLE `' . $destination_table_name, $query);            
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                $factory->writeLog($message);
                return false;
            }
            $query = 'describe ' . $destination_table_name;
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                $factory->writeLog($message);
                return false;
            }
        }
        
        $destination_table_desc = $destination_db->loadAssocList();

        //Compare tables
        $query = 'describe ' . $source_table_name;
        $source_db->setQuery($query);
        $result = CYENDFactory::execute($source_db);
        $source_table_desc = $source_db->loadAssocList();

        //$compare_desc = array_diff($destination_table_desc, $source_table_desc);
        //if (!empty($compare_desc)) {                
        if ($destination_table_desc != $source_table_desc) {

            //delete and create table
            $destination_db->dropTable($destination_table_name, true);
            $query = $source_table_desc_new->{'Create Table'};
            $query = str_replace('CREATE TABLE `' . $source_table_name, 'CREATE TABLE `' . $destination_table_name, $query);            
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                $factory->writeLog($message);
                return false;
            }
            $query = 'describe ' . $destination_table_name;
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                $factory->writeLog($message);
                return false;
            }
        }
        return true;
    }

    public function media($folders = null) {
        //define folders to copy
        $folders = Array();
        $folders[] = 'images/comprofiler';
        
        parent::media($folders);
                
    }
    
    public function transfer($ids = null, $name) {
        $this->task->state = 2; //state for success        
        parent::transfer($ids, $name);

        $this->task->state = 4; //state for success
        $this->fix($ids, $name);
    }
    
    private function fix($ids = null, $name) {
        
        // Initialize
        $jAp = $this->jAp;
        $factory = $this->factory;
        $tableLog = $this->tableLog;
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $source_db = $this->source_db;
        $source_query = $this->source_query;
        $task = $this->task;
        $user = $this->user;
        $params = $this->params;
        $destination_table = $this->destination_table;
        $table_name = $this->table_name;
        $id = $this->id;

        $source_table_name = '#__' . $name;
        $destination_table_name = '#__' . $name;
        $items = Array();
        
        $message = ('<h4>' . JText::_('COM_SPUPGRADE_FIX') . '</h4>');
        $factory->writeLog($message);

        // Load items
        $query = 'SELECT source_id
            FROM #__spupgrade_log
            WHERE tables_id = ' . (int) $task->id . ' AND state > 3
            ORDER BY id ASC';
        $destination_db->setQuery($query);
        $result = CYENDFactory::execute($destination_db);
        if (!$result) {
            $jAp->enqueueMessage(JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()), 'error');
            $message = '<p><b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $source_db->$destination_db()) . '</font></b></p>';
            $factory->writeLog($message);
            return false;
        }
        $temp = $destination_db->loadColumn();        

        $query = 'select @rownum:=@rownum+1 sp_id, p.* from #__' . $name . ' p, (SELECT @rownum:=0) r';
        $query .= ' ORDER BY sp_id ASC';

        $source_db->setQuery($query);
        $result = CYENDFactory::execute($source_db);
        if (!$result) {
            $jAp->enqueueMessage(JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $source_db->getErrorMsg()), 'error');
            $message = '<p><b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $source_db->getErrorMsg()) . '</font></b></p>';
            $factory->writeLog($message);
            return false;
        }
        $items2 = $source_db->loadAssocList();

        //Remove unecessary ids
        foreach ($items2 as $i => $item) {        
            $bool = true;        
            foreach ($temp as $k => $temp1) {
                if ($temp1 == $item['sp_id']) {                        
                    $bool = false;
                }
            }
            if ($bool)
                $items[] = $item;
            
        }

        //percentage
        $percTotal = count($items);
        if ($percTotal < 100)
            $percKlasma = 0.1;
        if ($percTotal > 100 && $percTotal < 2000)
            $percKlasma = 0.05;
        if ($percTotal > 2000)
            $percKlasma = 0.01;
        $percTen = $percKlasma * $percTotal;
        $percCounter = 0;
        if ($percTotal == 0) {
            $message = '<p>' . JText::_(COM_SPUPGRADE_NOTHING_TO_TRANSFER) . '</p>';
            $factory->writeLog($message);
        }
        // Loop to save items
        foreach ($items as $i => $item) {
         
            //exit if limit reached
            if (!empty($ids)) {
                if ($i > $ids)
                    break;
            }

            //percentage
            $percCounter += 1;
            if (@($percCounter % $percTen) == 0) {
                $perc = round(( 100 * $percCounter ) / $percTotal);
                $message = $perc . '% ' . JText::_('COM_SPUPGRADE_MSG_PROCESSED') . '<br/>';
                $factory->writeLog($message);
            }

            //log            
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => $task->id, "source_id" => $item['sp_id']));
            $tableLog->created = null;
            $tableLog->note = "";
            $tableLog->source_id = $item['sp_id'];
            $tableLog->destination_id = $item['sp_id'];
            $tableLog->state = 1;
            $tableLog->tables_id = $task->id;

            //params
            $params = $item['params'];
            if (!empty($params)) {
                $item_params = explode("\n", $params);
                foreach ($item_params as $param) {
                    $attribs = explode("=", $param);
                    if (count($attribs) > 1) {
                        if ($attribs[0] == 'timezone') {
                            $new_params[$attribs[0]] = '';
                        } else {
                            $new_params[$attribs[0]] = $attribs[1];
                        }
                    }
                }
                $item['params'] = json_encode($new_params);
            }

            //created_by
            $created_by = $item['created_by'];
            if (!empty($created_by)) {
                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => 1, "source_id" => $created_by));
                $item['created_by'] = $tableLog->destination_id;
            }

            //modified_by
            $modified_by = $item['modified_by'];
            if (!empty($created_by)) {
                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => 1, "source_id" => $modified_by));
                $item['modified_by'] = $tableLog->destination_id;
            }
            
            //user_id
            $user_id = $item['user_id'];
            if (!empty($user_id)) {
                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => 1, "source_id" => $user_id));
                $item['user_id'] = $tableLog->destination_id;
            }

            //viewaccesslevel difference
            if (!is_null($item['viewaccesslevel'])) {
                if ($item['viewaccesslevel'] > 2) {
                    $message = '<p>' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_ACCESSLEVEL', $item['id']) . '</p>';
                    $factory->writeLog($message);
                    continue;
                }
                if ($item['viewaccesslevel'] == 2)
                    $item['viewaccesslevel'] = 3;
                if ($item['viewaccesslevel'] == 1)
                    $item['viewaccesslevel'] = 2;
                if ($item['viewaccesslevel'] == 0)
                    $item['viewaccesslevel'] = 1;
            }

            //reload table log
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => $task->id, "source_id" => $item['sp_id']));
            
            //Build query
            $query = "REPLACE INTO #__" . $name . " (";
            $columnNames = Array();
            $values = Array();
            foreach ($item as $column => $value) {
                if ($column != 'sp_id') {
                    $columnNames[] = $destination_db->quoteName($column);
                    $temp1 = implode(',', $columnNames);
                    $values[] = $destination_db->quote($value);
                    $temp2 = implode(',', $values);
                }
            }
            $query .= $temp1 . ") VALUES (" . $temp2 . ")";
            
            // Create record
            $destination_db->setQuery($query);
            if (!$destination_db->query()) {
                $message = '<p>' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_CREATE', print_r($item, true), $destination_db->getErrorMsg()) . '</p>';
                $factory->writeLog($message);
                $tableLog->note = $message;
                $tableLog->store();
                continue;
            }

            //Log
            $tableLog->state = $this->task->state;
            $tableLog->store();
        } //Main loop end
        if ($message != '<p>' . JText::_(COM_SPUPGRADE_NOTHING_TO_TRANSFER) . '</p>'
                && $message != '100% ' . JText::_('COM_SPUPGRADE_MSG_PROCESSED') . '<br/>') {
            $message = '100% ' . JText::_('COM_SPUPGRADE_MSG_PROCESSED') . '<br/>';
            $factory->writeLog($message);
        }
    }
}
