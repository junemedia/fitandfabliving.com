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
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
include_once 'ftp.php';

class SPUpgradeModelTables extends JModelList {

    public function getTable($type = 'Tables', $prefix = 'SPUpgradeTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getQuery($pk);

        $db->setQuery($query);
        CYENDFactory::execute($db);
        $item = $db->loadObject();

        return $item;
    }

    protected function getQuery($pk) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.extension_name, a.name'
                )
        );
        $query->from('#__spupgrade_tables AS a');        

        // Filter by id
        $query->where('a.id = ' . (int) $pk);

        return $query;
    }

    public function getItems($pk = null) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getListQuery($pk);

        $db->setQuery($query);
        CYENDFactory::execute($db);
        $items = $db->loadObjectList();

        return $items;
    }

    protected function getListQuery($pk = null) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.extension_name, a.name'
                )
        );
        $query->from('#__spupgrade_tables AS a');

        // Filter by extension_name
        // Join over the extension name
        if (!is_null($pk)) {
            //$query->join('LEFT', '`#__extensions` AS l ON l.extension_name = a.extension_name GROUP BY a.extension_name');
            $query->where('l.extension_id = ' . (int) $pk);
        }

        //Limit up to id < 1000
        //$query->where('a.id < 1000');
        //$query->where('`extension_name` NOT LIKE '.$db->quote('com_database'));
        $query->where('`extension_name` LIKE '.$db->quote('com_users'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_content'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_contact'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_weblinks'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_newsfeeds'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_banners'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_menus'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_modules'), 'OR');
        $query->where('`extension_name` LIKE '.$db->quote('com_media'), 'OR');

        // Ordering
        $query->order('a.id ASC');

        return $query;
    }

    public function getTestConnection() {
        //Check connection
        $source = new CYENDSource();
        return $source->testConnection();
    }
    
    public function getPathConnection() {
        //Check connection
        $source = new CYENDSource();
        return $source->testPathConnection();
    }
    
    public function getFtpConnection() {
        $ftp = new SPUpgradeModelFTP();
        return $ftp->checkConnection();
    }

}
