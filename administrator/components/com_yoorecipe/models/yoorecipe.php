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
 * YooRecipe Model
 */
class YooRecipeModelYooRecipe extends JModelAdmin
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
	public function getTable($type = 'YooRecipe', $prefix = 'YooRecipeTable', $config = array()) 
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
		$form = $this->loadForm('com_yoorecipe.yoorecipe', 'yoorecipe', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_yoorecipe.edit.yoorecipe.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			$data->category_id = self::getRecipeCategoriesIds($data->id);
			$data->season_id = self::getRecipeSeasonsIds($data->id);
		}
		return $data;
	}
	
	/**
	 * Method to retrieve the list of ingredients from a given recipe
	 * @return The object list of ingredients
	 *
	 */
	public function getRecipeIngredients($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe table
		$query->select('id, recipe_id, ordering, quantity, unit, description, price, group_id');
		$query->from('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($pRecipeId));
		$query->order('ordering asc');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * Method to cross categories for a given recipe
	 * @return The object list of categories
	 *
	 */
	public function getRecipeCategoriesIds($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe table
		$query->from('#__yoorecipe_categories');
		$query->select('cat_id');
		$query->where('recipe_id = ' . $db->quote($pRecipeId));
		
		$db->setQuery($query);
		return $db->loadColumn();
	}
	
	/**
	 * Method to cross categories for a given recipe
	 * @return The object list of categories
	 *
	 */
	public function getRecipeSeasonsIds($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe table
		$query->from('#__yoorecipe_seasons');
		$query->select('month_id');
		$query->where('recipe_id = ' . $db->quote($pRecipeId));
		
		$db->setQuery($query);
		return $db->loadColumn();
	}
	
	/**
	 * Method to cross categories for a given recipe
	 * @return The object list of categories
	 *
	 */
	public function getRecipeCategories($pRecipeId) {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// From the recipe categories table
		$query->select('c.id, c.asset_id, c.parent_id, c.lft, c.rgt, c.level, c.path, c.extension, c.title, c.alias, c.note, c.description, c.published, c.access, c.checked_out, c.checked_out_time, c.access, c.params, c.metadesc, c.metakey, c.metadata , c.created_user_id, c.created_time, c.modified_user_id, c.modified_time, c.hits, c.language');
		$query->from('#__categories c');
		
		// Join over categories
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.cat_id = c.id');
		
		// Where clause
		$query->where('cc.recipe_id = ' . $db->quote($pRecipeId));
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	* Method to save a recipe
	*/
	public function save($data)
	{
		// If user has not been set, automatically fill in missing fields
		$user 		= JFactory::getUser();
		
		if ($data['created_by'] == 0) : $data['created_by'] = $user->id; endif;
		if (intval($data['creation_date'])) {
			$date = new JDate($data['creation_date']);
			$data['creation_date'] = $date->toSQL(true);
		}
		else {
			$data['creation_date'] = null;
		}

		// Initialise variables;
		$params 	= JComponentHelper::getParams('com_yoorecipe');
		$use_tags 	= $params->get('use_tags', 1);
		
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$key		= $table->getKeyName();
		$pk			= (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Allow an exception to be thrown.
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
			
			// Get ingredients
			$ingredients = JHtmlYooRecipeAdminUtils::buildIngredientsFromRequest();

			// Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}
			if (count($ingredients) == 0) {
				$this->setError(JText::_('COM_YOORECIPE_NO_INGREDIENTS'));
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

			// Save multiple categories
			$catids = $data['category_id'];
			$db 	= JFactory::getDBO();
			$query	= $db->getQuery(true);

			// Remove categories affectation
			$query->delete('#__yoorecipe_categories');
			$query->where('recipe_id = ' . $db->quote($data['id']));
			$db->setQuery($query);
			$db->execute();
				
			// Insert cross categories
			foreach($catids as $catid) :
				self::insertCrossCategory($table->id, $catid);
			endforeach;
			
			
			// Save multiple seasons
			$seasonids = $data['season_id'];
			$db 	= JFactory::getDBO();
			$query	= $db->getQuery(true);

			// Remove categories affectation
			$query->delete('#__yoorecipe_seasons');
			$query->where('recipe_id = ' . $db->quote($data['id']));
			$db->setQuery($query);
			$db->execute();
				
			// Insert cross categories
			foreach($seasonids as $seasonid) :
				self::insertSeason($table->id, $seasonid);
			endforeach;
			
			// Remove ingredients
			$modelIngredients = JModelLegacy::getInstance( 'ingredients', 'YooRecipeModel' );
			$modelIngredients->deleteIngredientsByRecipeId($data['id']);
			
			// Insert ingredients
			foreach ($ingredients as $ingredient) {
				$modelIngredients->insertIngredient($table->id, $ingredient->ordering, $ingredient->quantity, $ingredient->unit, $ingredient->description, $ingredient->price, $ingredient->group_id);
			}
			
			if (!$storeResultOK) {
				$this->setError($table->getError());
				return false;
			}
			
			if ($use_tags) {
			
				// Delete tags by recipe Id
				$modelTags = JModelLegacy::getInstance( 'tags', 'YooRecipeModel' );
				$modelTags->deleteTagsByRecipeId($table->id);
							
				// Insert tags by recipeId
				$tags = JHtmlYooRecipeAdminUtils::buildTagsFromRequest();
				foreach ($tags as $tag) {
					$modelTags->insertTag($table->id, $tag->tag_value);
				}
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
	
	public function insertCrossCategory($recipe_id, $cat_id)
	{
		// Prepare query
		$db = JFactory::getDBO();	
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->insert('#__yoorecipe_categories');
		$query->set('recipe_id = '. $db->quote($recipe_id));
		$query->set('cat_id = '. $db->quote($cat_id));
				
		$db->setQuery((string)$query);
		if (!$db->execute()) {
			$this->setError($db->getErrorMsg());
			echo $db->getErrorMsg();
			return false;
		}
	}
	
	public function insertSeason($recipe_id, $month_id) {
	
		// Prepare query
		$db = JFactory::getDBO();	
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->insert('#__yoorecipe_seasons');
		$query->set('recipe_id = '. $db->quote($recipe_id));
		$query->set('month_id = '. $db->quote($month_id));
				
		$db->setQuery((string)$query);
		if (!$db->execute()) {
			$this->setError($db->getErrorMsg());
			echo $db->getErrorMsg();
			return false;
		}
	}
	
	/**
	 * Get ratings for a given recipe
	 */
	public function getRatingsByRecipeId($recipeId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('r.id, r.recipe_id, r.note, r.author, r.user_id, r.comment, r.creation_date');

		// From the recipe rating table
		$query->from('#__yoorecipe_rating r');
		
		// Join over users
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = r.user_id');
		
		// Where
		$query->where('recipe_id = ' . $db->quote($recipeId));
		$query->order('creation_date desc');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function deleteRecipeById($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete ingredients
		$model = JModelLegacy::getInstance( 'ingredients', 'YooRecipeModel' );
		$model->deleteIngredientsByRecipeId($recipeId);
		
		// Delete dependencies
		$this->deleteCrossCategoriesByRecipeId($recipeId);
		$this->deleteRatingsByRecipeId($recipeId);
		$this->deleteAssetsOfRecipeId($recipeId);
		$this->deleteFavoritesByRecipeId($recipeId);
		
		// Delete recipe
		$query->delete('#__yoorecipe');
		$query->where('id = ' . $db->quote($recipeId));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	private function deleteCrossCategoriesByRecipeId($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete cross categories
		$query->delete('#__yoorecipe_categories');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	private function deleteRatingsByRecipeId($recipeId) {
		
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete cross categories
		$query->delete('#__yoorecipe_rating');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	private function deleteAssetsOfRecipeId($recipeId) {
		
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete cross categories
		$query->delete('#__assets');
		$query->where('name = ' . $db->quote('#__yoorecipe.' . $recipeId));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	private function deleteFavoritesByRecipeId($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->delete('#__yoorecipe_favourites');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		if ($user != null) {
			$query->where('user_id = ' . $db->quote($user->id));
		}
		$db->setQuery($query);
		$db->execute();
	}
}