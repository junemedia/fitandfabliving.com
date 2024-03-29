<?php
/**
 * Joomla! component sexypolling
 *
 * @version $Id: view.html.php 2012-04-05 14:30:25 svn $
 * @author 2GLux.com
 * @package Sexy Polling
 * @subpackage com_sexypolling
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');


class SexypollingViewSexypolls extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 *
	 * @return	void
	 */
    public function display($tpl = null) {
    	
    	$this->items		= $this->get('Items');
    	$this->pagination	= $this->get('Pagination');
    	$this->state		= $this->get('State');
    	$category_options	= $this->get('category_options');
 
    	//get category options
    	$options        = array();
    	foreach($category_options AS $category) {
    		$options[]      = JHtml::_('select.option', $category->id, $category->name);
    	}
       	if(JV == 'j2') {
	    	$this->assignRef( 'category_options', $options );
       	}
       	else {
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_PUBLISHED'),
       				'filter_published',
       				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
       		);
       		
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_CATEGORY'),
       				'filter_category_id',
       				JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.category_id'))
       		);
       		
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_ACCESS'),
       				'filter_access',
       				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
       		);
       	}
       	$this->addToolbar();
       	if(JV == 'j3')
       		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
	protected function addToolbar()
	{
		JToolBarHelper::addNew('sexypoll.add');
		JToolBarHelper::editList('sexypoll.edit');
		    	
		JToolBarHelper::divider();
		JToolBarHelper::publish('sexypolls.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('sexypolls.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::archiveList('sexypolls.archive');
		JToolBarHelper::checkin('sexypolls.checkin');
	    
    	if ($this->state->get('filter.published') == -2) {
    		JToolBarHelper::deleteList('', 'sexypolls.delete', 'JTOOLBAR_EMPTY_TRASH');
    		JToolBarHelper::divider();
    	}
    	else {
    		JToolBarHelper::trash('sexypolls.trash');
    		JToolBarHelper::divider();
    	}
	    
		JToolBarHelper::divider();
	}
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'sp.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'sp.name' => JText::_('COM_SEXYPOLLING_NAME'),
				'sp.question' => JText::_('COM_SEXYPOLLING_QUESTION'),
				'sp.published' => JText::_('JSTATUS'),
				'category_title' => JText::_('JCATEGORY'),
				'template_title' => JText::_('COM_SEXYPOLLING_TEMPLATE'),
				'sp.featured' => JText::_('JFEATURED'),
				'sp.access' => JText::_('JGRID_HEADING_ACCESS'),
				'num_answers' => JText::_('COM_SEXYPOLLING_NUM_ANSWERS'),
				'sp.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}