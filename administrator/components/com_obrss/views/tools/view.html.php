<?php
/**
 * @package	foobla RSS Feed Creator for Joomla.
 * @subpackage: install.jlord_rss.php
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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class JlordRSSViewTools extends obView {
	function display($tpl = null) {
		global $option,$isJ25;
		JHTML::stylesheet( 'jlord_core.css', 'administrator/components/'.$option.'/assets/' );
		JToolBarHelper::title( JText::_('OBRSS_TOOLS' ),'jlord-tools.png');
//		JToolBarHelper::back('Back', 'index.php?option='.$option);
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.'com_obrss.php')) {
			$install_pluginArtio = JRequest::getVar('install_pluginArtio','uninstall');
		} else {
			$install_pluginArtio = JRequest::getVar('install_pluginArtio','install');
		}
		$this->assignRef('install_pluginArtio',$install_pluginArtio);
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_sh404sef'.DS.'sef_ext'.DS.'com_obrss.php')) {
			$install_pluginSh404sef = JRequest::getVar('install_pluginSh404sef','uninstall');
				 
		} else {
			$install_pluginSh404sef = JRequest::getVar('install_pluginSh404sef','install');
		}
		$this->assignRef('install_pluginSh404sef',$install_pluginSh404sef);
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS.'com_obrss.php')) {
			$install_pluginAcesef = JRequest::getVar('install_pluginAcesef','uninstall');
		} else {
			$install_pluginAcesef = JRequest::getVar('install_pluginAcesef','install');
		}
		$this->assignRef('install_pluginAcesef',$install_pluginAcesef);
		if (is_dir(JPATH_SITE.DS.'modules'.DS.'mod_obrss'.DS)) {
			$install_module = JRequest::getVar('install_module','uninstall');
		} else {
			$install_module = JRequest::getVar('install_module','install');
		}
		$this->assignRef('install_module',$install_module);
		if(!$isJ25){
			$path	= JPATH_SITE.DS.'plugins'.DS.'content'.DS.'load_obrss'.DS.'load_obrss.php';
		}else {
			$path	= JPATH_SITE.DS.'plugins'.DS.'content'.DS.'load_obrss.php';
		}
		if (file_exists($path)) {
			$install_plugin_load = JRequest::getVar('install_plugin_load','uninstall');
		} else {
			$install_plugin_load = JRequest::getVar('install_plugin_load','install');
		}
		$this->assignRef('install_plugin_load', $install_plugin_load);
		if(!$isJ25){
			$path	= JPATH_SITE.DS.'plugins'.DS.'system'.DS.'obrss'.DS.'obrss.php';
		}else {
			$path	= JPATH_SITE.DS.'plugins'.DS.'system'.DS.'obrss.php';
		}
		if (file_exists($path)) {
			$install_plugin_live = JRequest::getVar('install_plugin_live','uninstall');
		} else {
			$install_plugin_live = JRequest::getVar('install_plugin_live','install');
		}
		$this->assignRef('install_plugin_live',$install_plugin_live);
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'joomfish'.DS.'obrss.xml')){
			$install_joomfish = JRequest::getVar('install_joomfish','uninstall');
		} else {
			$install_joomfish = JRequest::getVar('install_joomfish','install');
		}
		$this->assignRef('install_joomfish',$install_joomfish);
		parent::display($tpl);
		echo JHTML::_('behavior.keepalive');
	}//end display
}//end class