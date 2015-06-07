<?php
/**
 * @version		$Id: controller.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class obrssController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = array())
	{	//test 2010.11.19
		$document 	= JFactory::getDocument();
		$vType		= $document->getType();
		$view		= $this->getView('feeds',$vType);
		$view->display($cachable);
	}
	
	function feed()
	{
		$mod = $this->getModel('feed');
		$errShow = $mod->showFeed();
		switch ($errShow){
			case 1:
				$msg = '[CODE: 1] Invalid Feed ID';
				break;
			case 2:
				$msg = '[CODE: 2] Feed does not exist.';
				break;
			case 3:
				$msg = '[CODE: 3] The addon does not exist.';
				break;
			case 4:
				$msg = '[CODE: 4] The addon class does not exist. You probably installed out-dated version of the particular addon.';
				break;			
			case 5:
				$msg = '[CODE: 5] The feed has been disabled by Administrator. If you are the admin, you can go to the backend to enable it.';
				break;
			case 6:
				$msg = '[CODE: 6] Can not create the feed file in cache folder. Please make sure Joomla/cache/ folder is writable.';
				break;
			case 7:
				$msg = '[CODE: 7] Can not save the feed. Please make sure Joomla/cache/com_obrss/ folder is writable.';
				break;
			default:
				$msg = '[CODE: 0] Unknown Error, please report this problem at http://foob.la/support';
				break;
		}
		echo "<h3>$msg! [$errShow]</h3>";
	}
}
?>
