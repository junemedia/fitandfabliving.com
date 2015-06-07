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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
include_once 'ftp.php';

class SPUpgradeModelExtension extends JModelLegacy {

    protected $jAp;
    protected $tableLog;
    protected $destination_db;
    protected $destination_query;
    protected $destination_table;
    protected $table_name;
    protected $source_db;
    protected $source_query;
    protected $user;
    protected $params;
    protected $task;
    protected $factory;
    protected $source;
    protected $id;

    function __construct($config = array()) {
        parent::__construct($config);
        $this->factory = new CYENDFactory();
        $this->source = new CYENDSource();
        $this->jAp = & JFactory::getApplication();
        $this->tableLog = $this->factory->tableLog;
        $this->tableLog = $this->factory->getTable('Log', 'SPUpgradeTable');
        $this->destination_db = $this->getDbo();
        $this->destination_query = $this->destination_db->getQuery(true);
        $this->source_db = $this->source->source_db;
        $this->source_query = $this->source_db->getQuery(true);
        $this->user = JFactory::getUser();
        $this->params = JComponentHelper::getParams('com_spupgrade');
    }

    public function init($extension_name, $name) {
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $jAp = $this->jAp;
        $factory = $this->factory;
        
        $message = ('<h3>Table: <u>' . $name . '</u></h3>');
        $factory->writeLog($message);        
        
        // Load items
        $destination_query->clear();
        $destination_query->select('*');
        $destination_query->from('#__spupgrade_tables');
        $destination_query->where('extension_name LIKE ' .$destination_db->quote($extension_name));
        $destination_query->where('name LIKE ' .$destination_db->quote($name));
        $destination_db->setQuery($destination_query);
        $result = CYENDFactory::execute($destination_db);
        
        if (!$result) {
            $jAp->enqueueMessage(JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $destination_db->getErrorMsg()), 'error');
            $message = '<p><b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_ERROR_QUERY', $source_db->$destination_db()) . '</font></b></p>';
            $factory->writeLog($message);
            return false;
        }
        
        $this->task = $destination_db->loadObject();             

    }

    public function setTable($name) {
        $factory = $this->factory;

        //Exit if empty table
        $source_table_name = '#__' . $name;

        // Init
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $source_db = $this->source_db;
        $source_query = $this->source_query;

        //Define destination table name
        $destination_table_name = '#__' . $name;

        // Get tables descriptions
        $query = 'SHOW CREATE TABLE ' . $source_table_name;

        $source_db->setQuery($query);
        if (!CYENDFactory::execute($source_db))
            return false;
        $source_table_desc = $source_db->loadObject();

        $query = 'describe ' . $destination_table_name;
        $destination_db->setQuery($query);
        if (!CYENDFactory::execute($destination_db)) {
            //Create table
            $this->setError(JText::plural('COM_SPUPGRADE_MSG_DESTINATION_TABLE_MISSING', $name));
            $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_MSG_DESTINATION_TABLE_MISSING', $name) . '</font></b>';
            $factory->writeLog($message);
            return false;
        }
        
        return true;

        $destination_table_desc = $destination_db->loadAssocList();

        //Compare tables
        $query = 'describe ' . $source_table_name;
        $source_db->setQuery($query);
        $result = CYENDFactory::execute($source_db);
        $source_table_desc = $source_db->loadAssocList();
        //$compare_desc = array_diff($destination_table_desc, $source_table_desc);
        //if (!empty($compare_desc)) {                
        if ($destination_table_desc != $source_table_desc) {
            // Different structure
            //@task - Deal option if different structure
            $message = '<b><font color="red">' . JText::sprintf('COM_SPUPGRADE_DATABASE_DIFFERENT_STRUCTURE', $destination_table_name) . '</font></b>';
            $factory->writeLog($message);
            return false;
        }

        return true;
    }
    
    public function transfer($ids = null, $name) {
        
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
        
        $message = ('<h4>' . JText::_('COM_SPUPGRADE_TRANSFER') . '</h4>');
        $factory->writeLog($message);

        // Load items
        $query = 'SELECT source_id
            FROM #__spupgrade_log
            WHERE tables_id = ' . (int) $task->id . ' AND state >= 2
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

            //Build query
            //$query = "INSERT INTO #__" . $name . " (";
            //if ($params->get("new_ids", 0) == 2)
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

    public function tables($extension) {        
        // Create a new query object.
        $db = $this->destination_db;
        $query = $this->destination_query;
        $query->clear();

        // Select the required fields from the table.
        $query->select('a.name');
        $query->from('#__spupgrade_tables AS a');
        $query->where('`extension_name` LIKE '.$db->quote($extension));        
        $query->order('a.id ASC');
        $db->setQuery($query);
        if(!CYENDFactory::execute($db)) {
            $this->setError($db->getErrorMsg());
            return false;
        }      
        $tables = $db->loadColumn();

        return $tables;
    }
    
    public function media($folders = null) {
        
        $factory = $this->factory;
        $ftp = new SPUpgradeModelFTP();
        
        $message = ('<h2>' . JText::_($this->task->extension_name) . ' - ' . JText::_($this->task->name) . '</h2>');
        $factory->writeLog($message);

        foreach ($folders as $folder) {
            JFolder::move($folder, $folder.'_bkp' . JFactory::getDate()->format('_Y_m_d_G_i_s'), JPATH_SITE);
            
            $message = '50% ' . JText::_('COM_SPUPGRADE_MSG_PROCESSED') . '<br/>';
            $factory->writeLog($message);

            //JFolder::copy($source->source_path . $folder, JPATH_SITE . '/' . $folder);
            $ftp->transfer($folder, $folder);
        }                
        
        $message = '100% ' . JText::_('COM_SPUPGRADE_MSG_PROCESSED') . '<br/>';
        $factory->writeLog($message);
    }

}
