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
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');
class JLordRssViewLangs extends obView
{
	function display($tpl = null)
	{
		global $option;
		JHTML::stylesheet( 'jlord_core.css', 'administrator/components/'.$option.'/assets/');
		if(JRequest::getVar('task','showlanguage') == 'showlanguage') {
			JToolBarHelper::title( JText::_('OBRSS_LANGS'), 'jlord-langs.png' );
			JToolBarHelper::editList('getrwlanguage');
			$res = $this->get('language');
			$this->assignRef('res',$res);
		} elseif(JRequest::getVar('task','showlanguage') == 'search_keyword') {
			JToolBarHelper::title( JText::_('Language Text Manager: <small><small>[ Search and Update] </small></small>'), 'jlord-langs.png' );
			JToolBarHelper::save('save_into_file');
			JToolBarHelper::cancel('cancelSearch');
			$search_keyword = $this->get('search_keyword');
			$this->assignRef('search_keyword',$search_keyword);
		} elseif (JRequest::getVar('task','showlanguage') == 'newline') { 
			JToolBarHelper::title( JText::_('Language Text Manager: <small><small>[ Edit] </small></small>'), 'jlord-langs.png' );
			JToolBarHelper::save('insert_newline');
			JToolBarHelper::cancel('cancelAddLine');
		} else {
			JToolBarHelper::title( JText::_('Language Text Manager: <small><small>[ Edit] </small></small>'), 'jlord-langs.png' );
			JToolBarHelper::addNew('newline','Add Line');
			JToolBarHelper::save('save_language');
			JToolBarHelper::cancel('cancelLangs');
			$display = $this->get( 'rwlanguage');
			if(isset($display->total)) {
				$total = $display->total;
			}
			$this->assignRef( 'rwlanguage',	$display);
			$this->assignRef( 'totalObject',$total);
		}
		parent::display($tpl);
	}
} // end class
?>
