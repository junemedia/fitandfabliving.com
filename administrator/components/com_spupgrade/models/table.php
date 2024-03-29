<?php

/**
 * @package		SP Upgrade
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SPUpgradeModelTable extends JModelList {

    
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        //Get table name
        $name = JRequest::getVar('name');
        $this->setState('name', $name);
        $prefix = JRequest::getVar('prefix');
        $this->setState('prefix', $prefix);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $source = new CYENDSource();
        
        $db = $source->source_db;
        $query = $db->getQuery(true);
        
        // Select the required fields from the table.
        /*
        $column_name = $this->getColumnName();
        $name = $this->getState('name');
        $query->select('*');
        $query->from('#__'.$name.' AS a');
        $query->order($column_name.' ASC');
         * 
         */
        
        $name = $this->getState('name');
        $query = 'select @rownum:=@rownum+1 sp_id, p.* from #__'.$name.' p, (SELECT @rownum:=-1) r';
        $query .= ' ORDER BY sp_id ASC';

        return $query;
    }
    
    protected function getColumnName() {
        $source = new CYENDSource();
        $db = $source->source_db;
        $name = $this->getState('name');
        $query = 'describe #__'.$name;
        $db->setQuery($query);
        CYENDFactory::execute($db);
        $column_name = $db->loadResult();
        return $column_name;
    }
    
     public function getItems($pk = null) {
        //$items = parent::getItems(); 
        // Create a new query object.
         $source = new CYENDSource();
        $db = $source->source_db;
        $query = $this->getListQuery($pk);

        $db->setQuery($query);
        CYENDFactory::execute($db);
        $items = $db->loadObjectList();

        return $items;
    }

}
