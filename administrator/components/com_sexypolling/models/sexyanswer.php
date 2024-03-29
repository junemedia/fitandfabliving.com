<?php
/**
 * Joomla! component sexypolling
 *
 * @version $Id: sexyanser.php 2012-04-05 14:30:25 svn $
 * @author 2GLux.com
 * @package Sexy Polling
 * @subpackage com_sexypolling
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class SexypollingModelSexyAnswer extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'SexyAnswer', $prefix = 'SexyPollTable', $config = array()) 
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
		$form = $this->loadForm('com_sexypolling.sexyanswer', 'sexyanswer', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_sexypolling.edit.sexyanswer.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	
	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}
	
	/**
	 * Method to toggle the featured setting of contacts.
	 *
	 * @param	array	$pks	The ids of the items to toggle.
	 * @param	int		$value	The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);
	
		if (empty($pks)) {
			$this->setError(JText::_('COM_SEXYPOLLING_NO_ITEM_SELECTED'));
			return false;
		}
	
		$table = $this->getTable();
	
		try
		{
			$db = $this->getDbo();
	
			$db->setQuery(
					'UPDATE #__sexy_answers' .
					' SET featured = '.(int) $value.
					' WHERE id IN ('.implode(',', $pks).')'
			);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
	
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
	
		$table->reorder();
	
		// Clean component's cache
		$this->cleanCache();
	
		return true;
	}
	
	/**
	 * Method to save answer
	 */
	function saveAnswer()
	{
		$date = new JDate();
		$id = JRequest::getInt('id',0);
	
	
		$req = new JObject();
		$req->name =  str_replace('\\','', htmlspecialchars($_REQUEST['jform']['name'], ENT_QUOTES) );
	
		$req->id_poll = (int)$_REQUEST['jform']['id_poll'];
		$req->published = (int)$_REQUEST['jform']['published'];
	
		if($req->id_poll == 0 || $req->name == "") {
			return false;
		}
		elseif($id == 0) {//if id ==0, we add the record
			$req->id = NULL;
			if(JV == 'j2')
				$req->created = $date->toMySQL();
			else
				$req->created = $date->toSql();
	
			if (!$this->_db->insertObject( '#__sexy_answers', $req, 'id' )) {
				return false;
			}
		}
		else { //else update the record
			$req->id = $id;
			$res = (int)$_REQUEST['jform']['reset_votes'];
			if($res == 1) {
				$sql = 'DELETE FROM `#__sexy_votes` '
				. ' WHERE `id_answer` = '.$id;
				$this->_db->setQuery($sql);
				$this->_db->query();
			}
	
			if (!$this->_db->updateObject( '#__sexy_answers', $req, 'id' )) {
				return false;
			}
		}
	
		return true;
	}
}