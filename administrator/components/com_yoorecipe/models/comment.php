<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * YooRecipe Comment Model
 */
class YooRecipeModelComment extends JModelAdmin
{

private $checked_out;
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Comment', $prefix = 'YooRecipeTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_yoorecipe.comment', 'comment', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_yoorecipe.edit.comment.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			$data->title = self::getRecipeTitle($data->recipe_id);
		}
		return $data;
	}
	
	/**
	 * Method to get a recipe title by recipe id
	 * @return The recipe title
	 */
	public function getRecipeTitle($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe table
		$query->select('title');
		$query->from('#__yoorecipe');
		$query->where('id = ' . $db->quote($pRecipeId));
		
		$db->setQuery($query);
		return $db->loadResult();
		
	}
	
	public function save($data)
	{
		// If user has not been set, automatically fill in missing fields
		$user 		= JFactory::getUser();
		
		//if ($data['created_by'] == 0) : $data['created_by'] = $user->id; endif;
		//if ($data['creation_date'] == 0) : $data['creation_date'] = $user->id; endif;
		if (intval($data['creation_date'])) {
				$date = new JDate($data['creation_date']);
				$date->setTimezone($tz);
				$data['creation_date'] = $date->toSQL(true);
			}
			else {
				$data['creation_date'] = null;
			}

		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$key		= $table->getKeyName();
		$pk			= (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Allow an exception to be throw.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0) {
				$table->load($pk);
				$isNew = false;
			}

			// Bind data.
			if (!$table->bind($data)) {
				$this->setError($table->getError());
				return false;
			}

			// Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}

			// Trigger the onContentBeforeSave event.
			$result = $dispatcher->trigger($this->event_before_save, array($this->option.'.'.$this->name, &$table, $isNew));
			if (in_array(false, $result, true)) {
				$this->setError($table->getError());
				return false;
			}

			// Store the data.
			$storeResultOK = $table->store();
			$session = JFactory::getSession();
			$session->set('data', $data);
			
			if (!$storeResultOK) {
				$this->setError($table->getError());
				return false;
			} 

			// Trigger the onContentAfterSave event.
			$dispatcher->trigger($this->event_after_save, array($this->option.'.'.$this->name, &$table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName)) {
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		$this->setState($this->getName().'.new', $isNew);

		return true;
	}
}