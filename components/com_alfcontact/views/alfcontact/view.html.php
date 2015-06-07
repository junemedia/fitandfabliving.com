<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * HTML View class for the ALFContact Component
 */
class AlfcontactViewAlfcontact extends JViewLegacy
{
    protected $params;
	
	// Overwriting JView display method
    function display($tpl = null) 
    {
		// Assign data from the model to the view
        $this->items = $this->get('Items');
				        
		// Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
                
		// Prepare the document
		$this->_prepareDocument();
		
		// Display the view
        parent::display($tpl);

    }
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app  = JFactory::getApplication();
		$menu = $app->getMenu()->getActive();
		$this->pagetitle = $menu->params->get('page_title');
		$title = null;

		$title = $this->pagetitle;
		
		//Check Session variables
		$this->name 		= $app->getUserState('com_alfcontact.name', '');
		$this->email 		= $app->getUserState('com_alfcontact.email', '');
		$this->emailto_id 	= $app->getUserState('com_alfcontact.emailto_id', 0);
		$this->subject 		= $app->getUserState('com_alfcontact.subject', '');
		$this->message 		= $app->getUserState('com_alfcontact.message', '');
		$this->copy 		= $app->getUserState('com_alfcontact.copy', 0);
				
		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		
		$this->document->setTitle($title);
		
	}
	
}
