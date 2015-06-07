<?php
/**
 * @version		$Id: view.html.php 732 2013-07-22 08:53:07Z tsvn $
 * @package	foobla RSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author: foobla.com
 * @license: GNU/GPL, see LICENSE
 */
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
jimport( 'joomla.application.component.view');
class obRSSViewFeed extends obView
{
	function display($tpl = null)
	{
		global $isJ25;
		if($isJ25){
			require_once(JPATH_COMPONENT.'/helpers/html/sidebar.php');
		}
		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$option = 'com_obrss';
		$controller = JRequest::getVar('controller');
		//JHTML::stylesheet( 'jlord_core.css', 'administrator/components/'.$option.'/assets/' );
		$document->addStyleSheet('components/com_obrss/assets/jlord_core.css');
		$task		= JRequest::getVar('task');
//		var_dump($task);
		
		if ($task == 'edit' or $task == 'add') {
			$edit	= $task == 'edit'?true:false;
			
			$feed_id = JRequest::getVar('cid', 0);
			$feed_id = $feed_id[0];
			$text	= ( $edit ? JText::_( 'EDIT' ) : JText::_( 'NEW' ) );
			JToolBarHelper::title(  JText::_( 'OBRSS_FEED_MANAGER' ).': <small><small>[ ' . $text.' ]</small></small>','jlord-addfeed.php' );
			
			JToolBarHelper::apply( 'apply', 'OBRSS_FEED_SAVE');
			JToolBarHelper::save( 'save', 'OBRSS_FEED_SAVE_CLOSE');
			
			if ($edit) {#http://www.foobla.com/joomla25/index.php?option=com_obrss&task=feed&id=1:joomla-2-5
				if ($isJ25) {
					JToolBarHelper::custom('preview', 'preview', 'previewover', 'PREVIEW', false, false);
				} else {
					JToolBarHelper::custom('preview', 'play-2', 'previewover', 'PREVIEW', false, false);
				}
			}
			
			//JToolBarHelper::custom( 'save2new', 'save2new', 'save2newover','OBRSS_FEED_SAVE_NEW',false,false);
			JToolBarHelper::save2new('save2new');
			if ($task=='edit') {
				//JToolBarHelper::custom( 'save2copy', 'save2copy', 'save2copyover','OBRSS_FEED_SAVE_COPY',false,false);save2copy
				JToolBarHelper::save2copy( 'save2copy');
			}
			if ($task == 'edit') {
				#JToolBarHelper::custom( 'burn', 'burn', 'burn', 'BURN_THIS_FEED', false, false);
				$bar = JToolBar::getInstance('toolbar');
				#$bar->appendButton('Popup', 'burn', 'BURN_THIS_FEED', 'index.php?option=com_obrss&controller=feed&task=add_fb&cid='.$cid[0].'&tmpl=component', 700, 300);
			}
			JToolBarHelper::cancel( 'cancel', ($edit? 'CLOSE':'CANCEL'));
			$edit 	= JRequest::getVar( 'edit', true );
			$rows   = $this->get('DataJlord_rss1');
			$ascopy	= JRequest::getCmd('ascopy',0);
			if( $ascopy==1 ) {
				$rows->id 	= 0;
				$rows->name =  $rows->name.' (copy)';
				$rows->alias	= '';
			}
			$rows->feed_button =  $edit ?$rows->feed_button : 'rss_2.0.png';
			$params	= $this->get('Params');

			$lists1	= $this->get('ListsJlord_rss1');
			
			$addons	= $this->get('ListAddOn');
			
			$this->assignRef('button'	, $rows->feed_button);
			$this->assignRef('jlord_rss', $rows);
			$this->assignRef('params'	, $params);
			$this->assignRef('lists1'	, $lists1);
			$this->assignRef('addons', $addons);
		} else {
			JToolBarHelper::title(  JText::_( 'OBRSS_FEED_MANAGER'), 'jlord-core.png' );
			//JToolBarHelper::addnew('feedautomake','Feed Auto Make');
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::custom( 'copy', 'copy', 'copy','OBRSS_FEED_COPY');
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::deleteList('Are you sure?');
			
			$items 		= $this->get( 'Data');
			$pagination = $this->get( 'Pagination' );
			$lists   	= $this->get( 'Lists' );
			
			if (1==1 OR !$isJ25) :
			#SLIDEBAR
			
			JHtmlSidebar::addEntry(
			JText::_('OBRSS_DASHBOARD'),
				'index.php?option=com_obrss&controller=cpanel',
				(!$controller || $controller=='cpanel')
			);
			
			JHtmlSidebar::addEntry(
				JText::_('OBRSS_FEED_MANAGER'),
				'index.php?option=com_obrss&controller=feed',
				($controller=='feed')
			);
			
			$manager_link = '';
			if($isJ25){
				$manager_link = 'index.php?option=com_installer&view=manage&filters[type]=plugin&filters[group]=obrss';
			} else {
				$manager_link = 'index.php?option=com_installer&view=manage&filter_type=plugin&filter_group=obrss';
			}
			JHtmlSidebar::addEntry(
				JText::_('OBRSS_ADDONS'),
				$manager_link
				);
			if(!$isJ25):
				$uri = (string) JUri::getInstance();
				$return = urlencode(base64_encode($uri));
				JHtmlSidebar::addEntry(
					JText::_('COM_OBRSS_SUBMENU_CONFIGURATION'),
					'index.php?option=com_config&view=component&component=com_obrss&return='.$return
				);
				endif;
			endif;
			
			/**
			 * FILTER SLIDEBAR
			 */
			$option					='com_obrss';
			$filter_order			= $mainframe->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'j.id', 'cmd' );
			$filter_order_Dir		= $mainframe->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'DESC',		'word' );
			$filter_state			= $mainframe->getUserStateFromRequest( "$option.filter_state",		'filter_state',		'',		'word' );
			$filter_addon			= $mainframe->getUserStateFromRequest( "$option.filter_addon",		'filter_addon',		'',		'cmd' );
			$filter_state_feed		= $mainframe->getUserStateFromRequest( "$option.filter_state_feed",	'filter_state_feed', '',	'word' );
			$filter_state_display	= $mainframe->getUserStateFromRequest( "$option.filter_state_display",	'filter_state_display', '',	'word' );
			$search					= $mainframe->getUserStateFromRequest( 'search', 'search', '',	'string' );
			
			JHtmlSidebar::setAction('index.php?option=com_obrss&controller=feed');
			JHtmlSidebar::addFilter(
				JText::_('OBRSS_SELECT_ADDON'),
				'filter_addon',
				JHTML::_('select.options',$this->get('AddonOptions'),'value','text',$filter_addon)
			);
			
			$states = array(
						array('value'=>'P', 'text'=>JText::_('JPUBLISHED')),
						array('value'=>'U', 'text'=>JText::_('JUNPUBLISHED'))
					);
			JHtmlSidebar::addFilter(
				JText::_('OBRSS_SELECT_STATE'),
				'filter_state',
				JHTML::_( 'select.options',$states,'value','text',$filter_state )
			);
			
			$state_feed = array(
							array('value'=>'F', 'text'=>JText::_('OBRSS_FEED')),
							array('value'=>'UF', 'text'=>JText::_('OBRSS_UNFEED'))
						);
			JHtmlSidebar::addFilter(
				JText::_('OBRSS_SELECT_STATEFEED'),
				'filter_state_feed',
				JHTML::_('select.options',$state_feed,'value','text',$filter_state_feed )
			);
			
			$state_display = array(
							array('value'=>'D', 'text'=>JText::_('OBRSS_DISPLAY')),
							array('value'=>'UD', 'text'=>JText::_('OBRSS_UNDISPLAY'))
						);
			JHtmlSidebar::addFilter(
				JText::_('OBRSS_SELECT_STATEDISPLAY'),
				'filter_state_display',
				JHTML::_('select.options',$state_display,'value','text',$filter_state_display )
			);
			$this->sidebar = JHtmlSidebar::render();
			
			$this->assignRef('pagination'	,$pagination);
			$this->assignRef('items'	,$items );
			$user = JFactory::getUser();
			$this->assignRef('user'		,$user);
			$this->assignRef('lists'	,$lists);
		}
		parent::display($tpl);
	} //end display
	function display_feedburner($tpl = null)
	{
		$data = $this->get('feedburnerData');
		//var_dump($data);
		$this->assign('option', $data->option);
		$this->assign('cid', $data->cid);
		$this->assign('controller', $data->controller);
		$this->assign('uri', $data->uri);
		$this->assign('task', $data->task);
		parent::display($tpl);
	}
	function display_feedburner_stats($tpl=null)
	{
		global $option;
		JHTML::stylesheet( 'jlord_core.css', 'administrator/components/'.$option.'/assets/' );
		JToolBarHelper::title( 'OBRSS_STATS_TITLE', 'jlord-core.png'  );
		JToolBarHelper::cancel( 'cancel', 'CLOSE' );
		$data = $this->get('statisticsFeedburner');
		$this->assign('data',$data);
		parent::display($tpl);
	}
}//end class