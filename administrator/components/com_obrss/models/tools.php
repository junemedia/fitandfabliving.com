<?php
/**
 * @version		$Id: tools.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.base.tree');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
class JlordRssModelTools extends obModel {
	function install_pluginArtio(){
		global $mainframe;
		if(is_dir(JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS)){
			$src1 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'joomsef'.DS.'com_obrss.php';
			$src2 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'joomsef'.DS.'com_obrss.xml';
			$dest = JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS;
			JFile::copy($src1,$dest.'com_obrss.php',''); 
			JFile::copy($src2,$dest.'com_obrss.xml',''); 
		}
		$msg = "Artio JoomSEF plugin has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);	
	}
	
	function install_pluginSh404sef(){
		global $mainframe;
		if(is_dir(JPATH_SITE.DS.'components'.DS.'com_sh404sef'.DS.'sef_ext'.DS)){
			$src  = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'sh404sef'.DS.'com_obrss.php';
			$dest = JPATH_SITE.DS.'components'.DS.'com_sh404sef'.DS.'sef_ext'.DS;
			JFile::copy($src,$dest.'com_obrss.php','');
		}
		$msg = "sh404SEF plugin has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function install_pluginAcesef(){
		global $mainframe;
		if(is_dir(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS)){
			$src1 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'acesef'.DS.'com_obrss.php';
			$src2 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'acesef'.DS.'com_obrss.xml';
			$dest = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS;
			JFile::copy($src1,$dest.'com_obrss.php',''); 
			JFile::copy($src2,$dest.'com_obrss.xml',''); 
		}
		
		$xml = &JFactory::getXMLParser('Simple');
		$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'sef'.DS.'acesef'.DS.'com_obrss.xml';
		$xml->loadString(JFile::read($path));
		$name = $xml->document->name[0]->data();
		
		$db = &JFactory::getDBO();
		$qr = "UPDATE #__acesef_extensions SET name = '".$name."' WHERE extension = 'com_obrss'";
		$db->setQuery($qr);
		$db->query();
		
		$msg = "AceSEF plugin has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);	
	}
	
	function uninstall_pluginArtio(){
		global $mainframe;
		if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.'com_obrss.php')){
			$dest = JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.'com_obrss.php';
			JFile::delete($dest);
		}
		if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.'com_obrss.xml')){
			$dest = JPATH_SITE.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.'com_obrss.xml';
			JFile::delete($dest);
		}
		$msg = "Artio JoomSEF plugin has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function uninstall_pluginSh404sef(){
		global $mainframe;
		
		if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_sh404sef'.DS.'sef_ext'.DS.'com_obrss.php')){
			$dest = JPATH_SITE.DS.'components'.DS.'com_sh404sef'.DS.'sef_ext'.DS.'com_obrss.php';
			JFile::delete($dest);
		}
		
		$msg = "sh404SEF plugin has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools4',$msg);
	}
	
	function uninstall_pluginAcesef(){
		global $mainframe;
		if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS.'com_obrss.php')){
			$dest = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS.'com_obrss.php';
			JFile::delete($dest);
		}
		if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS.'com_obrss.xml')){
			$dest = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'extensions'.DS.'com_obrss.xml';
			JFile::delete($dest);
		}
		
		$db = &JFactory::getDBO();
		$qr = "UPDATE #__acesef_extensions SET name = '' WHERE extension = 'com_obrss'";
		$db->setQuery($qr);
		$db->query();
		
		$msg = "AceSEF plugin has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function install_module() {
		global $mainframe;
		$db = &JFactory::getDBO();

		$src = JPATH_BASE.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'mod_obrss'.DS;
		$dest = JPATH_SITE.DS.'modules'.DS.'mod_obrss'.DS;
		JFolder::copy($src,$dest,'',true); 
		$query = "
			INSERT IGNORE INTO `#__modules`(`title`,`position`,`module`,`params`)
			VALUES('obRSS','left','mod_obrss','rssdesc=0\nword_count=10\nobrss_css=ul#jlord-rss {margin:0;padding:0;} ul#jlord-rss li {margin:0;padding:0;list-style:none;} ul#jlord-rss li a {} ul#jlord-rss li a:hover {} ul#jlord-rss li span {}')";
		$db->setQuery($query);
		if(!$db->query()){
			echo $db->getErrorMsg();
		}
		
		$mod_id = $db->insertid();
		$query = 'INSERT INTO #__modules_menu'
		. ' SET moduleid = '.$mod_id.' , menuid = 0'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $row->getError() );
		}
		$msg = "obRSS Module has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function install_plugin_live() {
		global $mainframe;
		$db = &JFactory::getDBO();

		$src	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'plg_obrss'.DS;
		$dest	= JPATH_SITE.DS.'plugins'.DS.'system'.DS;
		JFolder::copy($src,$dest,'',true);
		
		$query="
			INSERT IGNORE INTO `#__plugins`(`name`,`element`,`folder`,`ordering`,`published`)
			VALUES('System - obRSS','obrss','system',99,1)
		";
		$db->setQuery($query);
		
		if( !$db->query() ) {
			echo $db->getErrorMsg();
		}
		$msg = "obRSS Plugin Live-feed-icon has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function install_plugin_load() {
		global $mainframe;
		$db = &JFactory::getDBO();
		$src	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'plg_load_obrss'.DS;
		$dest	= JPATH_SITE.DS.'plugins'.DS.'content'.DS;
		JFolder::copy($src,$dest,'',true);
		
		$query="
			INSERT IGNORE INTO `#__plugins`(`name`,`element`,`folder`,`ordering`,`published`)
			VALUES('Content - Load obRSS','load_obrss','content',999,1)
		";
		$db->setQuery($query);
		
		if( !$db->query() ) {
			echo $db->getErrorMsg();
		}
		$msg = "obRSS Plugin load feed has been implemented!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
/**
 * install joomfish element
 */	
	function install_joomfish(){
		global $mainframe;
		$JFDir	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS;	
		if(is_dir($JFDir)){		
			$src	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'joomfish'.DS.'obrss.xml';
			JFile::copy($src,$JFDir.'obrss.xml');
			$msg = "Added Joom!Fish element obrss";
		}
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function uninstall_module() {
		global $mainframe;
		$db = &JFactory::getDBO();

			// 2. uninstall module
		if(JFolder::exists(JPATH_SITE.DS.'modules'.DS.'mod_obrss')){
			$dest = JPATH_SITE.DS.'modules'.DS.'mod_obrss';
			JFolder::delete($dest);
		}
		
		//2.1: Select id from table jos_modules
		$qry = "SELECT id FROM `#__modules` WHERE `module` = 'mod_obrss'";
		$db->setQuery( $qry );
		$mod_id=$db->loadResult();
	
		//2.2: Delete data about module in table jos_modules_menu
		if($mod_id){
			$query = "DELETE FROM `#__modules_menu`
					WHERE `moduleid`=$mod_id";
			$db->setQuery($query);
			if(!$db->query()){
				echo $db->getErrorMsg();
			}
		}
		
		//2.3: Delete data about module in table jos_modules
		$query = "
			DELETE FROM `#__modules`
				WHERE `module` = 'mod_obrss'
		";
		$db->setQuery($query);
		if(!$db->query()){
			echo $db->getErrorMsg();
		}
		$msg = "Foobla RSS Module has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function uninstall_plugin_live() {
		global $mainframe;
		$db = &JFactory::getDBO();

		$dest = JPATH_SITE.DS.'plugins'.DS.'system'.DS;
		JFile::delete($dest.'obrss.php'); 
		JFile::delete($dest.'obrss.xml'); 
		$query = "
			DELETE FROM `#__plugins`
			WHERE `name` = 'System - Foobla RSS'
		";
		$db->setQuery($query);
		if( !$db->query() ) {
			echo $db->getErrorMsg();
		}
		$msg = "obRSS Plugin Live-feed-icon has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}
	
	function uninstall_plugin_load() {
		global $mainframe;
		$db = &JFactory::getDBO();

		$dest = JPATH_SITE.DS.'plugins'.DS.'content'.DS;
		JFile::delete($dest.'load_obrss.php'); 
		JFile::delete($dest.'load_obrss.xml'); 
		$query = "
			DELETE FROM `#__plugins`
			WHERE `name` = 'Content - Load Foobla RSS'
		";
		$db->setQuery($query);
		if( !$db->query() ) {
			echo $db->getErrorMsg();
		}
		$msg = "obRSS Plugin Load feed has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);
	}

/**	
 * uninstall joomfish element
 */
	function uninstall_joomfish(){
		global $mainframe;
		$src = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_obrss'.DS.'aio'.DS.'joomfish'.DS.'obrss.xml';
		JFile::delete($src);
		$msg = "Joomfish has been removed!";
		$mainframe->redirect('index.php?option=com_obrss&controller=tools',$msg);		
	}
}
?>