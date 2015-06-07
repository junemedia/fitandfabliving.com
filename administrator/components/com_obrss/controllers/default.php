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

// jimport('joomla.application.component.controller');

class obrssControllerCpanel extends obController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display($cachable = false, $urlparams = false)
	{
		$document 	= JFactory::getDocument();
		$vType		= $document->getType();
		$vName 		= JRequest::getCmd('view', 'cpanel');
		$mName		= 'cpanel';
		$vLayout	= JRequest::getCmd('layout', 'default');
		
		// Get/Create the view
		$view = $this->getView( $vName, $vType);
		$view->setLayout($vLayout);
		//$this->input->set('view','cpanel');
		if ($model = $this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		// Display the view
		$view->display();
	}
} // end class
?>