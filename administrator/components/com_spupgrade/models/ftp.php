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
jimport('joomla.client.ftp');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class SPUpgradeModelFTP extends JModelLegacy {

    /**
     *
     * @var JFTP ftp connection
     */
    protected $ftp;

    /**
     * Destructor
     * 
     * FTP disconnect
     */
    public function __destruct() {
        if (is_object($this->ftp))
            $this->ftp->quit();
    }

    /**
     * Constructor
     *
     * @param   array  $config  An array of configuration options (ftp credentials, name, state, dbo, table_path, ignore_request).
     *
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        if (!empty($config))
            $credentials = JArrayHelper::toObject($config);
        else
            $credentials = null;
        $this->ftp_connect($credentials);
    }

    /**
     * Method to get model state variables
     *
     * @param   string  $property  Optional parameter name
     * @param   mixed   $default   Optional default value
     *
     * @return  object  The property where specified, the state object where omitted
     *
     */
    public function getState($property = null, $default = null) {
        static $set;

        if (!$set) {

            //remote
            $folder_remote = JRequest::getVar('folder_remote', '', '', 'path');
            $this->setState('folder_remote', $folder_remote);

            $parent_remote = str_replace("\\", "/", dirname($folder_remote));
            $parent_remote = ($parent_remote == '.') ? null : $parent_remote;
            $this->setState('parent_remote', $parent_remote);

            //local
            $folder_local = JRequest::getVar('folder_local', '', '', 'path');
            $this->setState('folder_local', $folder_local);

            $parent_local = str_replace("\\", "/", dirname($folder_local));
            $parent_local = ($parent_local == '.') ? null : $parent_local;
            $this->setState('parent_local', $parent_local);

            $set = true;
        }

        return parent::getState($property, $default);
    }

    /**
     * get Remote items
     *     
     * @param string $folder_remote
     * @return list of remote files
     */
    public function getItemsRemote($folder_remote = null) {

        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_spupgrade');
        $root = str_replace('//', '/', $params->get("ftp_root", '/'));

        // Get current path from request
        if (is_null($folder_remote))
            $folder_remote = $this->getState('folder_remote');
        $current = $folder_remote;

        if ($current == 'undefined') {
            $current = '';
        }
        if (strlen($current) != '/') {
            $basePath = str_replace('//', '/', $root . '/' . $current);
        } else {
            $basePath = $root;
        }

        //ftp connect
        if (!is_object($this->ftp))
            if (!$this->ftp_connect())
                return false;
        $ftp = $this->ftp;

        if (!$ftp->chdir($basePath)) {
            $app->enqueueMessage(JText::sprintf('COM_SPUPGRADE_ERROR_PATH', $basePath), 'error');
            return false;
        }

        $folders = $ftp->listDetails(null, 'folders');
        $files = $ftp->listDetails(null, 'files');
        $items = array_merge($folders, $files);

        $list = array();

        // Get parent path from request
        $parent = $this->getState('parent_remote');
        if (!empty($current)) {
            $tmp = new JObject();
            $tmp->type = 1; //go up
            $tmp->name = '..';
            $tmp->icon_16 = "com_spupgrade/mime-icon-16/folderup.png";
            if (empty($parent)) {
                $tmp->path = '';
            } else {
                $tmp->path = $parent;
            }
            $list[] = $tmp;
        }

        foreach ($items as $key => $item) {

            if ($current != '') {
                $item['path'] = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($current . '/' . $item['name']));
            } else {
                $item['path'] = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($item['name']));
            }

            switch ($item['type']) {
                case 0: //files
                    //$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath . '/' . $detail['name']));
                    //$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);                    
                    $ext = strtolower(JFile::getExt($item['name']));
                    //$tmp->icon_32 = "media/mime-icon-32/".$ext.".png";
                    $item['icon_16'] = "com_spupgrade/mime-icon-16/" . $ext . ".png";
                    break;
                case 1://folders
                    $item['icon_16'] = "com_spupgrade/mime-icon-16/folder.png";
                    break;
                case 2://shortcuts
                    //$item['icon_16'] = "com_spupgrade/mime-icon-16/link.png";
                    break;
                default:
                    break;
            }
            if ($item['type'] != 2)
                $list[] = JArrayHelper::toObject($item);
        }

        return $list;
    }

    /**
     * get Local items
     *     
     * @param string $folder_local
     * @return list of local files
     */
    public function getItemsLocal($folder_local = null) {

        // Get current path from request
        if (is_null($folder_local))
            $folder_local = $this->getState('folder_local');
        $current = $folder_local;


        if ($current == 'undefined') {
            $current = '';
        }
        if (strlen($current) != '/') {
            $basePath = str_replace('//', '/', JPATH_ROOT . '/' . $current);
        } else {
            $basePath = JPATH_ROOT;
        }

        // Get the list of files and folders from the given folder
        $files = JFolder::files($basePath);
        $folders = JFolder::folders($basePath);
        $list = array();

        // Get parent path from request
        $parent = $this->getState('parent_local');
        if (!empty($current)) {
            $tmp = new JObject();
            $tmp->type = 1; //go up
            $tmp->name = '..';
            $tmp->icon_16 = "com_spupgrade/mime-icon-16/folderup.png";
            if (empty($parent)) {
                $tmp->path = '';
            } else {
                $tmp->path = $parent;
            }
            $list[] = $tmp;
        }

        foreach ($folders as $key => $item) {
            $tmp = new JObject();
            $tmp->type = 1;
            $tmp->name = $item;
            if ($current != '') {
                $tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($current . '/' . $item));
            } else {
                $tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($item));
            }

            $tmp->icon_16 = "com_spupgrade/mime-icon-16/folder.png";
            $list[] = $tmp;
        }

        foreach ($files as $key => $item) {
            $tmp = new JObject();
            $tmp->type = 0;
            $tmp->name = $item;
            $ext = strtolower(JFile::getExt($item));
            $tmp->size = filesize($basePath . '/' . $item);
            $tmp->icon_16 = "com_spupgrade/mime-icon-16/" . $ext . ".png";
            $list[] = $tmp;
        }

        return $list;
    }

    /**
     * Transfer from remote folder to local
     * 
     * @param type $folder_remote
     * @param type $folder_local
     * @param type $items
     * @return boolean
     */
    public function transfer($folder_remote = null, $folder_local = null, $items = null) {

        $factory = new CYENDFactory();

        //Define files and folder names
        if (is_null($items))
            $items = $this->getItemsRemote($folder_remote);

        // Get current path from request   
        if (is_null($folder_local))
            $folder_local = $this->getState('folder_local');

        $current_local = $folder_local;
        if ($current_local == 'undefined') {
            $current_local = '';
        }
        if (strlen($current_local) != '/') {
            $localPath = str_replace('//', '/', JPATH_ROOT . '/' . $current_local);
        } else {
            $localPath = JPATH_ROOT;
        }

        //ftp connect        
        if (!is_object($this->ftp)) {
            if (!$this->ftp_connect())
                return false;
        }
        $ftp = $this->ftp;

        $params = JComponentHelper::getParams('com_spupgrade');
        $root = str_replace('//', '/', $params->get("ftp_root", '/'));

        // Get current path from request
        if (is_null($folder_remote)) {
            $folder_remote = $this->getState('folder_remote');
        }
        $current = $folder_remote;

        if ($current == 'undefined') {
            $current = '';
        }
        if (strlen($current) != '/') {
            $basePath = str_replace('//', '/', $root . '/' . $current);
        } else {
            $basePath = $root;
        }
        $basePath = str_replace('//', '/', $basePath);

        $replace = $params->get("replace_files", 1); //1 - no, 0 - yes
        foreach ($items as $item) {

            if (!$ftp->chdir($basePath)) {
                return false;
            }
            if ($item->type == 0) {
                //file
                if ($replace == 1 && JFile::exists($localPath . '/' . $item->name)) {
                    $factory->writeLog('<p>' . JText::sprintf('COM_SPUPGRADE_COPY_FILE_REPLACE', $localPath . '/' . $item->name) . '</p>');
                    continue;
                } else {
                    if (!$ftp->get($localPath . '/' . $item->name, $item->name)) {
                        $factory->writeLog('<p><font color="red">' . JText::sprintf('COM_SPUPGRADE_COPY_FILE_ERROR', $localPath . '/' . $item->name) . '</font></p>');
                        return false;
                    } else {
                        $factory->writeLog('<p>' . JText::sprintf('COM_SPUPGRADE_COPY_FILE_SUCCESS', $localPath . '/' . $item->name) . '</p>');
                    }
                }
            } else {
                //folder
                if ($item->name != '..') {
                    //create folder if not exist
                    if (!file_exists($localPath . '/' . $item->name)) {
                        if (!JFolder::create($localPath . '/' . $item->name)) {
                            $factory->writeLog('<p><font color="red">' . JText::sprintf('COM_SPUPGRADE_CREATE_FOLDER_ERROR', $localPath . '/' . $item->name) . '</font></p>');
                            return false;
                        } else {
                            $factory->writeLog('<p>' . JText::sprintf('COM_SPUPGRADE_CREATE_FOLDER_SUCCESS', $localPath . '/' . $item->name) . '</p>');
                        }
                    }

                    //get new items
                    $items_remote = $this->getItemsRemote($folder_remote . '/' . $item->name);
                    if (!$items_remote)
                        return false;

                    //call recursive function
                    if (!$this->transfer($folder_remote . '/' . $item->name, $folder_local . '/' . $item->name, $items_remote))
                        return false;
                }
            }
        }

        return true;
    }

    /**
     * Connect to remote server and assign $this->ftp
     * @param stdClass $credentials
     * @return boolean
     */
    protected function ftp_connect($credentials = null) {
        if (empty($credentials)) {
            $params = JComponentHelper::getParams('com_spupgrade');
            $credentials = new stdClass();
            $credentials->host = $params->get("ftp_host", 'localhost');
            $credentials->port = $params->get("ftp_port", '21');
            $credentials->user = $params->get("ftp_user", '');
            $credentials->pass = $params->get("ftp_pass", '');
            $credentials->root = str_replace('//', '/', $params->get("ftp_root", '/'));
        }

        $ftp = new JFTP(Array());
        if (!$ftp->connect($credentials->host, $credentials->port)) {
            return false;
        }
        if (!$ftp->login($credentials->user, $credentials->pass)) {
            $ftp->quit();
            return false;
        }

        $this->ftp = $ftp;
        return true;
    }

    /**
     * check connection
     * 
     * @return boolean
     */
    public function checkConnection() {

        if (is_null($this->ftp))
            return false;
        if ($this->ftp->isConnected()) {
            $items = $this->getItemsRemote('includes');
            if (!empty($items))
                foreach ($items as $item) {
                    if ($item->name == 'joomla.php')
                        return true;
                }
            return false;
        }
        else
            return false;
    }

    /**
     * Method to change the current working directory on the FTP server
     *
     * @param   string  $path  Path to change into on the server
     *
     * @return  boolean True if successful
     *
     */
    public function chdir($path) {
        return $this->ftp->chdir($path);
    }

}

