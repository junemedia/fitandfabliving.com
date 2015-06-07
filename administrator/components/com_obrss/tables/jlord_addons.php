<?php
/**
 * @version		$Id: jlord_addons.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class Tablejlord_addons extends JTable
{
    var $id				= null;
    var $file			= null;
    var $name			= null;
    var $creationdate	= null;
    var $author			= null;
    var $copyright		= null;
    var $authorEmail	= null;
    var $authorUrl		= null;
    var $version		= null;
    var $description	= null;
    var $params			= null;
    var $published		= null;
    var $isCore			= 0;
    
    function check()
    {
    	$db = &JFactory::getDBO();
    	$query = "SELECT count(*) FROM `#__jlord_addons` WHERE `file`= '".$this->file."'" ;
    	$db->setQuery($query);
    	$res = $db->loadResult();
    	if($res != '0'){
    		return false;
    	}
    	return true;
    }
    
    function Tablejlord_addons(& $db) {
        parent::__construct('#__jlord_addons', 'id', $db);
    }
}
?>
