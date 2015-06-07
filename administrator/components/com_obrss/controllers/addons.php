<?php
/**
 * @package	foobla RSS Feed Creator for Joomla.
 * @created: Setember 2008.
 * @updated: 2009/06/30
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author	foobla
 * @license	GNU/GPL, see LICENSE
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controller');
JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables' );
class obRSSControllerAddons extends obController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('uninstallext', 'delete');
		$this->registerTask('apply', 'save_editext');
	}
	function display($cachable=false, $urlparams=array())
	{
		$vName = 'addons';
		$mName = 'addons';
		switch ($this->getTask()) {
			case 'editext':
				$vLayout 	= 'editext';
				break;
			case 'publish':
				$this->changeState(1);
				break;
			case 'unpublish':
				$this->changeState(0);
				break;
			default:
				$vLayout 	= 'default';
				break;
		}
		$document 	= JFactory::getDocument();
		$vType 		= $document->getType();
		$view 		= $this->getView($vName, $vType);
		if ($model 	= $this->getModel($mName)) {
			$view->setModel($model, true);
		}
		$view->setLayout($vLayout);
		$view->display();
	}
	function doinstall()
	{		 
	 	global	$mainframe,$option;
		$model	= &$this->getModel('addons');
		$msg	= $model->install();		
		$mainframe->enqueueMessage($msg[0],$msg[1]);
		$mainframe->redirect("index.php?option=$option&controller=addons&showins=1");	   	 
	}
	/**
	 * save_editext
	 *
	 */
	 function save_editext()
	 {
		global $mainframe;
		$task = &JRequest::getVar('task');
	 	$model = &$this->getModel('addons');
	 	$url_redirect = 'index.php?option=com_obrss&controller=addons';
	 	if($task == 'apply') {
	 		$cid = &JRequest::getVar('cid');
	 		$url_redirect = 'index.php?option=com_obrss&controller=addons&task=editext&cid[]='.$cid;
	 	}
	 
		if ($model->store()) {
			$msg = JText::_( 'Addon Saved' );
			$mainframe->redirect($url_redirect,$msg);
		} else {
//		  $msg = JText::_( 'Error Saving Addon' );notice
			$mainframe->enqueueMessage('Error Saving Addon','error');
			$mainframe->redirect($url_redirect);
		}
	}
	
	/**
	 * Delete
	 *
	 */
	function delete()
	{
		global $mainframe;
		$models = $this->getModel('addons');
		if ($models->delete()) {
			$msg = JText::_( 'Uninstall Addon Success' );
			$mainframe->redirect('index.php?option=com_obrss&controller=addons',$msg);
		} else {
			$msg = JText::_( 'Uninstall Addon Error' );
			$mainframe->enqueueMessage($msg,'error');
		 	$mainframe->redirect('index.php?option=com_obrss&controller=addons');
		}
	}
	function changeState($state=0)
	{
		global $mainframe;
		$cid 	= JRequest::getVar('cid', array(), '', 'array');
		$controller = JRequest::getVar('controller');
		JArrayHelper::toInteger($cid);
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$db 	= &JFactory::getDBO();
		$query = 'UPDATE `#__obrss_addons`'
		. ' SET `published` = '.$state
		. ' WHERE `id` = '.$cid[0]
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg() );
		}
		$msg = 'Changed Item';
		$mainframe->redirect('index.php?option=com_obrss&controller='.$controller, $msg);
	}
} // end class
?>