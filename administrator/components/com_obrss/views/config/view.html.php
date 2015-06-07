<?php
/**
 * @version		$Id: view.html.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pane');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jlordcore.php' );

class JLORDRSSViewConfig extends obView
{
	function display($tpl = null)
	{
		global $option,$isJ25;
		$db = &JFactory::getDBO();
		JHTML::stylesheet( 'jlord_core.css', 'administrator/components/'.$option.'/assets/' );
		JToolBarHelper::title( JText::_('OBRSS_SETTINGS'), 'jlord-core.png' );
		JToolBarHelper::save('saveConfig');
		JToolBarHelper::apply('save');
		JToolBarHelper::cancel();
		JToolBarHelper::preferences($option);
/*		JToolBarHelper::help('about', true);*/
		
		// get license
		$model = $this->getModel('config');
		#$this->assign('license', $model->getConfigValue('license'));

		$this->assign('view_feedburner',$model->getConfigValue('view_feedburner'));
		$this->assign('show_number', $model->getConfigValue('show_number'));
		$this->assign('show_hits', $model->getConfigValue('show_hits'));
		$this->assign('button_xml', $model->getConfigValue('button_xml'));
		$this->assign('button_google', $model->getConfigValue('button_google'));
		$this->assign('button_msn', $model->getConfigValue('button_msn'));
		$this->assign('button_yahoo', $model->getConfigValue('button_yahoo'));
		$this->assign('button_bloglines', $model->getConfigValue('button_bloglines'));
		$this->assign('button_newsgator', $model->getConfigValue('button_newsgator'));
		$this->assign('image', $model->getConfigValue('image'));

		/*
		// get description
		$description_options_array = array(
			0	=> JText::_('OBRSS_SETTINGS_NO_DESCRIPTION'),
			1	=> JText::_('OBRSS_SETTINGS_TEXT'),
			2	=> JText::_('OBRSS_SETTINGS_INI'),
			3	=> JText::_('OBRSS_SETTINGS_MENU')
		);
		
		foreach ($description_options_array AS $key=>$value) {
			$description_options[] = JHTML::_('select.option', $key, $value);
		}
		
		$lists['description'] = JHTML::_( 'select.genericlist', $description_options, 'description', 'class="inputbox"', 'value', 'text', $model->getConfigValue('description') );
		*/
		$this->assign('description_text', $model->getConfigValue('description_text'));
		$this->assign('component', $model->getConfigValue('component'));
		$this->assign('feed_author', $model->getConfigValue('feed_author'));
		$this->assign('feed_authoremail', $model->getConfigValue('feed_authoremail'));
		#$this->assign('show_hits', JHTML::_( 'select.booleanlist', 'show_hits', '', $model->getConfigValue('show_hits'), 'OBRSS_SETTINGS_SHOW', 'OBRSS_SETTINGS_HIDE' ));
		#$this->assign('show_number', JHTML::_( 'select.booleanlist', 'show_number', '', $model->getConfigValue('show_number'), 'OBRSS_SETTINGS_SHOW', 'OBRSS_SETTINGS_HIDE' ));

		// build the select list for the image positions
		$active =  ( $model->getConfigValue('image_position') ? $model->getConfigValue('image_position') : 'left' );
		$lists['image_position'] 	= JHTML::_('list.positions',  'image_position', $active, NULL, 0, 0 );
		// Imagelist
		$name = 'image';
		$extensions =  array('bmp','gif','jpg','png');
		$directory = '/images/'.(!$isJ25?'':'');	
		
		jimport( 'joomla.filesystem.folder' );
		$imageFiles = JFolder::files( JPATH_SITE.DS.$directory );
		$images 	= array(  JHTML::_('select.option',  '', '- '. JText::_( 'OBRSS_SETTINGS_SELECT_IMAGE' ) .' -' ) );
		foreach ( $imageFiles as $file ) {
			$ext	= substr($file,-3);
			if(in_array($ext,$extensions)){	
				$images[]	= JHTML::_('select.option',  $file );
			}
		}
		$lists['image'] = JHTML::_('select.genericlist',  $images, $name, 'onchange="loadButtonRss(this)" class="inputbox" ', 'value', 'text', $model->getConfigValue('image') );
		
		//$lists['image'] 			= JHTML::_('list.images',  'image', $model->getConfigValue('image') );
		
		$this->assignRef('lists', $lists);
		parent::display($tpl);
	}
} // end class
?>