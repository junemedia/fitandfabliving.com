<?php
/**
 * @module		com_obrss
 * @script		obrss.php
 * @author-name Thong Tran
 * @copyright	Copyright (C) 2012 foobla.com
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Script file of Ola component
 */
class com_obrssInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
// 		$parent is the class calling this method
//		$parent->getParent()->setRedirectURL('index.php?option=com_obrss');
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
//		echo '<p>' . JText::_('COM_OBRSS_UNINSTALL_TEXT') . '</p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
//		echo '<p>' . JText::_('COM_OBRSS_UPDATE_TEXT') . '</p>';
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
//		echo '<p>' . JText::_('COM_OBRSS_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		$app 		= JFactory::getApplication();
		$installer 	= new JInstaller();
		$ds 		= DIRECTORY_SEPARATOR;
		$db 		= JFactory::getDbo();

		$instal_plg 	= $installer->install(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_obrss'.$ds.'aio'.$ds.'plg_system_obrss');
		$instal_plg2 	= $installer->install(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_obrss'.$ds.'aio'.$ds.'plg_content_load_obrss');
		$instal_plg3 	= $installer->install(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_obrss'.$ds.'aio'.$ds.'plg_obrss_content');
		
		$instal_mod 	= $installer->install(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_obrss'.$ds.'aio'.$ds.'mod_obrss');
		
		if($instal_plg){
			$sql = "UPDATE `#__extensions` SET `enabled`=1 WHERE `type`='plugin' AND `folder`='system' and `element`='obrss'";
			$db->setQuery($sql);
			$db->query();
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG_SUCCESS').'</p>';
		}else {
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG_ERROR').'</p>';
		}


		if($instal_plg2){
			$sql = "UPDATE `#__extensions` SET `enabled`=1 WHERE `type`='plugin' AND `folder`='content' and `element`='load_obrss'";
			$db->setQuery($sql);
			$db->query();
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG2_SUCCESS').'</p>';
		}else {
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG2_ERROR').'</p>';
		}


		if($instal_plg3){
			$sql = "UPDATE `#__extensions` SET `enabled`=1 WHERE `type`='plugin' AND `folder`='obrss' and `element`='content'";
			$db->setQuery($sql);
			$db->query();
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG3_SUCCESS').'</p>';
		}else {
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_PLG3_ERROR').'<p>';
		}
		
		
		if($instal_mod){
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_MOD_SUCCESS').'</p>';
		}else {
			echo '<p>'.JText::_('COM_OBRSS_MSG_INSTALL_MOD_ERROR').'<p>';
		}
		
		#todo: INSERT SAMPLE DATA
		$query = "SELECT count(*) FROM `#__obrss`";
		$db->setQuery( $query );
		$count = $db->loadResult();
		if( !$count ) {
			$date 		= JFactory::getDate();
			$now 		= $date->toSql();
			$user 		= JFactory::getUser();
			$user_id 	= $user->id;
			$sql = "INSERT INTO `#__obrss`
						(
						`name`, `alias`, `description`, `published`, `feeded`, `display_feed_module`,
						`feed_type`, `feed_button`, `components`, `created`, `created_by`, `modified`,
						`modified_by`, `checked_out_time`, `checked_out`, `ordering`, `hits`, `use_feedburner`)
					VALUES
					(
						'Sample Feed', 'sample_feed', 'Sample data for feed', 0, 1, 1, 'RSS2.0', 'rss_2.0', 'content',
						'{$now}', $user_id, '{$now}', $user_id, '0000-00-00 00:00:00', 0, 0, 0, 2)";
			$db->setQuery($sql);
			$db->query();
		}
	}
}