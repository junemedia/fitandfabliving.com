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

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Hello Table class
 */
class YooRecipeTableYooRecipe extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__yoorecipe', 'id', $db);
	}
	
	function check()
	{
		// Make recipe alias safe
		jimport( 'joomla.filter.output' );
		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		
		// Get data
		$input 	= JFactory::getApplication()->input;
		$post 	= $input->get('post', '', 'ARRAY');
		$jform 	= $input->get('jform', '', 'ARRAY');
		
		$prep_days 		= $input->get('prep_days', '0', 'INT');
		$prep_hours 	= $input->get('prep_hours', '0', 'INT');
		$prep_minutes 	= $input->get('prep_minutes', '0', 'INT');
		$cook_days 		= $input->get('cook_days', '0', 'INT');
		$cook_hours 	= $input->get('cook_hours', '0', 'INT');
		$cook_minutes 	= $input->get('cook_minutes', '0', 'INT');
		$wait_days 		= $input->get('wait_days', '0', 'INT');
		$wait_hours 	= $input->get('wait_hours', '0', 'INT');
		$wait_minutes 	= $input->get('wait_minutes', '0', 'INT');
		
		// Turn times into minutes
		$this->preparation_time = $prep_days * 1440 + $prep_hours * 60 + $prep_minutes;
		$this->cook_time 		= $cook_days * 1440 + $cook_hours * 60 + $cook_minutes;
		$this->wait_time 		= $wait_days * 1440 + $wait_hours * 60 + $wait_minutes;
		
		// Manage decimal values
		$this->price			= str_replace(",", ".", $jform['price']);
		$this->carbs			= str_replace(",", ".", $jform['carbs']);
		$this->fat				= str_replace(",", ".", $jform['fat']);
		$this->saturated_fat	= str_replace(",", ".", $jform['saturated_fat']);
		$this->proteins			= str_replace(",", ".", $jform['proteins']);
		$this->fibers			= str_replace(",", ".", $jform['fibers']);
		$this->salt				= str_replace(",", ".", $jform['salt']);
		
		return true;
	}
	
	/**
	 * Method to set the validation state for a row or list of rows in the database
	 * table.  The method DOES NOT respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param	mixed	An optional array of primary key values to update.  If not
	 *					set the instance property value is used.
	 * @param	integer The validation state. eg. [0 = unvalidated, 1 = validated]
	 * @param	integer The user id of the user performing the operation.
	 * @return	boolean	True on success.
	 * @since	2.1.0
	 */
	public function validate($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set validation state on, return false.
			else {
				$e = new JException(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				$this->setError($e);

				return false;
			}
		}

		// Update the validation state for rows with the given primary keys.
		$query = $this->_db->getQuery(true);
		$query->update($this->_tbl);
		$query->set('validated = '.(int) $state);

		// Build the WHERE clause for the primary keys.
		$query->where($k.' = '.implode(' OR '.$k.' = ', $pks));

		$this->_db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->execute()) {
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_PUBLISH_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);

			return false;
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->validated = $state;
		}
		
		// If notify user
		$yooRecipeparams = JComponentHelper::getParams('com_yoorecipe');
		if ($state && $yooRecipeparams->get('notify_on_validate', 0)) {
			
			foreach ($pks as $pk):

				$query = $this->_db->getQuery(true);
				
				// From the recipe table
				$query->from('#__yoorecipe as r');
				$query->join('LEFT', '#__categories c on r.category_id = c.id');
				
				// Select some fields
				$query->select('r.id, r.title, r.alias, r.description, r.created_by, r.category_id, c.title as category, r.preparation, r.nb_persons, r.difficulty, r.cost, r.creation_date, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.published, r.validated, r.featured, r.nb_views, r.note');				
				$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
				$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');

				// Join over the users for the author.
				$query->select('ua.name AS author_name');
				$query->select('ua.email AS author_email');
				$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
						
				$query->where('r.id = ' .  $this->_db->quote($pk));
				
				$this->_db->setQuery($query);
				$recipe = $this->_db->loadObject();

				JHtml::_('yoorecipeadminutils.sendMailToUserOnValidation', $recipe);
			endforeach;
		}
	
		$this->setError('');
		return true;
	}
}