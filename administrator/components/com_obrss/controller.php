<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Modules manager master display controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 * @since       1.6
 */
class obRSSController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view 	= $this->input->get('controller','cpanel');
		$layout = $this->input->get('layout', 'default');
		if(!$this->input->get('view')){
			$this->input->set('view',$view);
		}
		$task = $this->input->get('task','');
		if($task=='add'){
			$this->input->set('layout','form');
		}elseif($task=='apply'){
			$this->input->set('task','feed.save');
		}
		$id     = $this->input->getInt('id');
		
		parent::display();
	}
}