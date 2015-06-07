<?php
/**
 * @version		$Id: config.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.client.helper');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

// Include class autoupdate
//require_once( JPATH_COMPONENT.DS.'helpers'.DS.'autoupdate.php' );
// Include class autoupdate
//require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );
//require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jlordcore.php' );

class JLORDRSSControllerConfig extends obController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display()
	{
		$mName		= 'config';
		$document = JFactory::getDocument();
		$vType 		= $document->getType();
		$vName 		= JRequest::getVar('view', 'config');
		$vLayout	= JRequest::getVar('layout', 'default');
		
		$view = $this->getView($vName, $vType);
		$view->setLayout($vLayout);
			
		// Get/Create the view
		$view = &$this->getView( 'config', $vType);
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		
		$show = JRequest::getVar('show');
		switch ($show) {
			case 'all':
				ConfigHelper::setState('show_config',1);
				break;
			default:
				break;
		}
		
		$view->display();
	}
/**
 *  function saveConfig
 */
	protected function saveCF(){
		$models = $this->getModel('config');
		$check_firsttime = $models->check_firsttime();
		if(!$check_firsttime){
			$info	= $models->getInfoConfig();
			$models->saveInfoConfig($info);
			$models->setCheck();
		}		
		$models->saveDataConfig();		
	}
	function cancel(){
		global $mainframe, $option;
		$mainframe->redirect('index.php?option='.$option);
	}
	function save(){
		global $mainframe, $option;
		$this->saveCF();
		$msg = JText::_('OBRSS_SETTINGS_UPDATED_SUCCESSFULLY');
		$mainframe->redirect('index.php?option='.$option.'&controller=config', $msg);		
	}
	function saveConfig(){
		global $mainframe, $option;
		$this->saveCF();
		$msg = JText::_('OBRSS_SETTINGS_UPDATED_SUCCESSFULLY');
		$mainframe->redirect('index.php?option='.$option, $msg);
	}
	
} // end class
?>
