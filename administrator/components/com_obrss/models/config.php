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
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.helper');
class JlordRSSModelConfig extends obModel
{
	function getInfoConfig()
	{
		global $option;
		$db = &JFactory::getDBO();
		$query = "
			SELECT `id`
			FROM `#__components`
			WHERE `link` = 'option=$option'
		";
		$db->setQuery($query);
		$id = $db->loadResult();
		$query = "
			SELECT *
			FROM `#__obrss_config`
			WHERE `name` != 'check_firsttime'
		";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$entities = array();
		foreach ($rows as $row)
			$entities[] = $row->name;
		$info 			= new stdClass();
		$info->entities	= $entities;
		$info->id		= $id;
		return $info;
	}
	/**
	 * Get Value from Name
	 *
	 * @param unknown_type $name
	 */
	function getConfigValue($name)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT `value`
			FROM `#__obrss_config`
			WHERE `name` = '$name'
		";
		$db->setQuery($query);
//		var_dump($db->loadObjectList()); die();
		return $db->loadResult();
	}
	function saveInfoConfig($info)
	{
		global $mainframe, $option;
		$db 		= &JFactory::getDBO();
		$entities	= $info->entities;
		$id			= $info->id;
		// update jlord_core_config table
		foreach ($entities as $entity) {
			$cName 	= substr($entity, 6);
			if (($entity	=='show_cpanel')
			OR ($entity =='show_langs')
			OR ($entity =='show_tools')
			OR ($entity =='show_upgrade')
			OR ($entity =='show_addons')
			OR ($entity =='show_items')) {
				// get id from jos_componets table
				$query = "
					SELECT `id` FROM `#__components` WHERE `admin_menu_link` ='option=$option&controller=$cName'
				";
				$db->setQuery($query);
				$entity_id 	= $db->loadResult();
				$query = "
					UPDATE `#__obrss_config`
					SET `params` = 'option=$option&controller=$cName,$id,$entity_id'
					WHERE `name` = '$entity'
				";
				$db->setQuery($query);
				if(!$db->query()) return false;
			} else if ($entity == 'show_categories') {
				$query = "
					SELECT `id` FROM `#__components` WHERE `admin_menu_link` ='option=com_$cName&section=$option'
				";
				$db->setQuery($query);
				$entity_id 	= $db->loadResult();
				$query = "
					UPDATE `#__obrss_config`
					SET `params` = 'option=com_$cName&section=$option,$id,$entity_id'
					WHERE `name` = '$entity'
				";
				$db->setQuery($query);
				if (!$db->query()) return false;
			}
		}
	}
	/**
	 * Check first time 
	 *
	 * @return boolean
	 */
	function check_firsttime()
	{
		$db 	= &JFactory::getDBO();
		$query 	= "
			SELECT `value` FROM `#__obrss_config`
			WHERE `name` = 'check_firsttime'
		";
		$db->setQuery($query);
		$resval = $db->loadResult();
		if($resval)
		{
			return true;
		}
		return false;
	}
	function setCheck()
	{
		$db 	= &JFactory::getDBO();
		$query 	= "
			UPDATE `#__obrss_config`
			SET		`value` = '1'
			WHERE  `name` 	= 'check_firsttime'
		";
		$db->setQuery($query);
		$db->query(); 
	}
	/**
	 * getDataConfig
	 *
	 */
	function saveDataConfig()
	{
		global $mainframe, $option;
		$db = &JFactory::getDBO();
		$entities = array();
		$entities['show_config'] 				= JRequest::getVar('show_config', 1);
		$entities['show_cpanel']				= JRequest::getVar('show_cpanel', 1);
		$entities['show_tools'] 				= JRequest::getVar('show_tools', 1);
		$entities['show_items'] 				= JRequest::getVar('show_items', 1);
		$entities['show_langs'] 				= JRequest::getVar('show_langs', 1);
		$entities['show_upgrade'] 				= JRequest::getVar('show_upgrade', 1);
		$entities['show_addons'] 				= JRequest::getVar('show_addons', 1);
		$entities['show_cpanel_info'] 			= JRequest::getVar('show_cpanel_info', 1);
		$entities['show_cpanel_latestitems'] 	= JRequest::getVar('show_cpanel_latestitems', 1);
		$entities['show_cpanel_latestfaqs'] 	= JRequest::getVar('show_cpanel_latestfaqs', 1);
		$entities['show_cpanel_addons'] 		= JRequest::getVar('show_cpanel_addons', 1);
		$entities['view_feedburner']			= JRequest::getVar('view_feedburner', 1);
		$entities['button_xml']					= JRequest::getVar('button_xml', 1);
		$entities['button_google']				= JRequest::getVar('button_google', 1);
		$entities['button_msn']					= JRequest::getVar('button_msn', 1);
		$entities['button_yahoo']				= JRequest::getVar('button_yahoo', 1);
		$entities['button_bloglines']			= JRequest::getVar('button_bloglines', 1);
		$entities['button_newsgator']			= JRequest::getVar('button_newsgator', 1);
		$entities['feed_author']				= JRequest::getVar('feed_author');
		$entities['feed_authoremail']			= JRequest::getVar('feed_authoremail');
		#$entities['description']				= JRequest::getVar('description', 1);
		$entities['description_text']			= addslashes(JRequest::getVar('description_text', '', 'post', 'string', JREQUEST_ALLOWRAW));
		$entities['component']					= JRequest::getVar('component');
		$entities['image']					    = JRequest::getVar('image');
		$entities['image_position']				= JRequest::getVar('image_position');
		/*$a = JRequest::getVar('content_components','');
		$entities['content_components']			= implode(",",$a);*/
		$entities['components']			        = JRequest::getVar('components');
		$entities['show_hits']			        = JRequest::getVar('show_hits', 0);
		$entities['show_number']			    = JRequest::getVar('show_number', 0);
		$tmps['show_config'] 					= 'show_config';
		$tmps['show_cpanel']					= 'show_cpanel';
		$tmps['show_tools'] 					= 'show_tools';
		$tmps['show_items'] 					= 'show_items';
		$tmps['show_langss'] 					= 'show_langs';
		$tmps['show_upgrade'] 					= 'show_upgrade';
		$tmps['show_addons'] 					= 'show_addons';
		$tmps['show_cpanel_info'] 				= 'show_cpanel_info';
		$tmps['show_cpanel_latestfaqs'] 		= 'show_cpanel_latestfaqs';
		$tmps['show_cpanel_latestitems'] 		= 'show_cpanel_latestitems';
		//$tmps['license']						= 'license';
		$tmps['view_feedburner']				= 'view_feedburner';
		$tmps['button_xml']						= 'button_xml';
		$tmps['button_google']					= 'button_google';
		$tmps['button_msn']						= 'button_msn';
		$tmps['button_yahoo']					= 'button_yahoo';
		$tmps['button_bloglines']				= 'button_bloglines';
		$tmps['button_newsgator']				= 'button_newsgator';
		#$tmps['description']					= 'description';
		$tmps['description_text']				= 'description_text';
		$tmps['component']						= 'component';
		$tmps['image']					   	 	= 'image';
		$tmps['image_position']					= 'image_position';
		//$tmps['content_components']				= 'content_components';
		$tmps['show_cpanel_addons']				= 'show_cpanel_addons';
		$tmps['show_number']				    = 'show_number';
		$tmps['show_hits']				        = 'show_hits';
		$tmps['components']					    = 'components';
		$tmps['feed_author']				= 'feed_author';
		//$tmps['feed_authoremail']			=  'feed_authoremail';
		foreach ($tmps as $tmp) {
			$query = "
				UPDATE `#__obrss_config`
				SET 	`value` = '".$entities[$tmp]."'
				WHERE 	`name` 	= '".$tmp."'
			";
			$db->setQuery($query);
			$db->query();
		}
		//$mainframe->redirect( 'index.php?option='.$option.'&controller=config');
	}
	/**
	 * 
	 */
	function saveConfig()
	{
		global $mainframe, $option;
		$db 	= &JFactory::getDBO();
		$query 	= "
			SELECT * FROM `#__obrss_config`
			WHERE `name` != 'check_firsttime'
		";
		$db->setQuery($query);
		$resvals = $db->loadObjectList();
		foreach ($resvals as $resval) {
			if (($resval->name =='show_cpanel')
			//OR ($resval->name =='show_langs')
			OR ($resval->name =='show_tools')
			OR ($resval->name =='show_upgrade')
			OR ($resval->name =='show_addons')
			OR ($resval->name =='show_items')) {
				$param 	= $resval->params;
				$arr	= explode(',',$param);
				if ($resval->value) {
					$query = "
						UPDATE `#__components`
						SET `enabled` 			= 1,
						 	`admin_menu_link` 	= '".$arr[0]."'
						WHERE `id`				= '".$arr[2]."' 
					";
					$db->setQuery($query);
					$db->query();
				} else {
					$query = "
						UPDATE `#__components`
						SET `enabled` 			= 0,
						 	`admin_menu_link` 	= ''
						WHERE `id`				= '".$arr[2]."' 
					";
					$db->setQuery($query);
					$db->query();
				}
			}
		}
		//$mainframe->redirect( 'index.php?option='.$option.'&controller=config');
	}
} // end class
?>
