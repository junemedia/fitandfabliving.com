<?php

/**
 * @package		SP Libraries
 * @subpackage	Utilites
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('JPATH_PLATFORM') or die;

/**
 * SPGeneral is a class with various frequent used functions
 *
 * @package     spcyend.utilities.factory
 * @subpackage  Utilities
 * @since       1.0.0
 */
 class CYENDFactory {

    /**
     * Constructor.
     *
     * @since   1.0.0
     *
     */
    public function __construct() {
        JFactory::getLanguage()->load('lib_spcyend', JPATH_SITE); //Load library language
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.0.0
     */
    public static function getModel($name = '', $prefix = '', $config = array()) {
        return JModelLegacy::getInstance($name, $prefix, array('ignore_request' => true));
    }

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.0.0
     */
    public static function getTable($name = '', $prefix = 'JTable', $options = array()) {
        return JTable::getInstance($name, $prefix, $options);
    }

    /**
     * Method to write to a log file
     *
     * @param   string  $message    The message to write
     * @param   string  $mode       Define is new file 'w'. Optional.
     * @param   array   $fileName   Log full path file name. Optional.
     *
     * @return  boolean True if success, false if failure
     *
     * @since   1.0.0
     */
    public static function writeLog($message, $mode = 'a', $fileName = null) {
        if (is_null($fileName))
            $fileName = JPATH_COMPONENT_ADMINISTRATOR . '/log.htm';
        $handle = fopen($fileName, $mode);
        if ($handle) {
            fwrite($handle, $message);
            fflush($handle);
            fclose($handle);
        }
        return true;
    }

    /**
     * Method for debuggin. Write a variable, or message on screen.
     *
     * @param   string  $msg    The message to write
     *
     * @return  string  The message
     *
     * @since   1.0.0
     */
    public static function print_r($msg) {
        $return = '<pre>' . print_r($msg, true) . '</pre>';
        echo $return;
        return $return;
    }

    /**
     * Enqueue a system message.
     *
     * @param   string  $msg   The message to enqueue.
     * @param   string  $type  The message type. Default is message.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public static function enqueueMessage($msg, $type = 'message') {
        JFactory::getApplication()->enqueueMessage($msg, $type);
    }

    /**
     * Get current component name
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public static function getComponentName() {
        return JRequest::getCmd('option');
    }

    /**
     * Execute an SQL query
     *
     * @param JDatabase $db The database object to query
     * 
     * @return  boolean, True succes, or False for failure
     *
     * @since   2.0.0
     */
    public static function execute($db) {
        try {
            $db->execute();
        } catch (RuntimeException $e) {
            //JError::raiseWarning(500, $e->getMessage());
            return false;
        }

        return true;
    }
    
    /**
     * Get current url
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public static function getCurrentUrl() {
        $pageURL = 'http';
        if (@$_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    
    /**
     * Encrypt a string bassed on a salt keyword
     *
     * @return  string
     *
     * @since   3.1.1
     */
    public static function encrypt($decrypted, $salt) {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt , true);
        // Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
        srand();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22)
            return false;
        // Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
        // We're done!
        return $iv_base64 . $encrypted;
    }

    /**
     * decryt a string bassed on a salt keyword
     *
     * @return  string
     *
     * @since   3.1.1
     */
    public static function decrypt($encrypted, $salt) {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt, true);
        // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');
        // Remove $iv from $encrypted.
        $encrypted = substr($encrypted, 22);
        // Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
        if (!($decrypted = @rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4")))
            return false;
        // Retrieve $hash which is the last 32 characters of $decrypted.
        $hash = substr($decrypted, -32);
        // Remove the last 32 characters from $decrypted.
        $decrypted = substr($decrypted, 0, -32);
        // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
        if (md5($decrypted) != $hash)
            return false;
        // Yay!
        return $decrypted;
    }

}
