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
class JlordRSSControllerTools extends obController
{
	function __construct()
	{
		parent::__construct();
	}
	function display()
	{
		$mName  	= 'tools';
		$document 	= &JFactory::getDocument();
		$vType		= $document->getType();
		$vName 		= JRequest::getCmd('view', 'tools');
		$vLayout	= JRequest::getCmd('layout', 'tools');
		// Get/Create the view
		$view = &$this->getView( $vName, $vType);
		$view->setLayout($vLayout);
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		// Display the view
		$view->display();
	}
	function install_pluginArtio(){
		$models = $this->getModel('tools');
		$models->install_pluginArtio();
	}
	function install_pluginSh404sef(){
		$models = $this->getModel('tools');
		$models->install_pluginSh404sef();
	}
	function install_pluginAcesef(){
		$models = $this->getModel('tools');
		$models->install_pluginAcesef();
	}
	function uninstall_pluginArtio(){
		$models = $this->getModel('tools');
		$models->uninstall_pluginArtio();
	}
	function uninstall_pluginSh404sef(){
		$models = $this->getModel('tools');
		$models->uninstall_pluginSh404sef();
	}
	function uninstall_pluginAcesef(){
		$models = $this->getModel('tools');
		$models->uninstall_pluginAcesef();
	}
	function install_module(){
		$models = $this->getModel('tools');
		$models->install_module();
	}
	function install_plugin_live(){
		$models = $this->getModel('tools');
		$models->install_plugin_live();
	}
	function install_plugin_load(){
		$models = $this->getModel('tools');
		$models->install_plugin_load();
	}
	function uninstall_module(){
		$models = $this->getModel('tools');
		$models->uninstall_module();
	}
	function uninstall_plugin_live(){
		$models = $this->getModel('tools');
		$models->uninstall_plugin_live();
	}
	function uninstall_plugin_load(){
		$models = $this->getModel('tools');
		$models->uninstall_plugin_load();
	}
	function install_joomfish(){
		$models = $this->getModel('tools');
		$models->install_joomfish();
	}
	function uninstall_joomfish(){
		$models = $this->getModel('tools');
		$models->uninstall_joomfish();
	}
} // end class
?>