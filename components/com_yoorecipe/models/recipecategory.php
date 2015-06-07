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
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * YooRecipe Model
 */
class YooRecipeModelYooRecipe extends JModelItem
{
	/**
	 * @var string msg
	 */
	protected $msg;
	
	protected $recipes;
	
	protected $recipe;
 
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
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	public function getMsg() 
	{
		if (!isset($this->msg)) 
		{
			$input 	= JFactory::getApplication()->input;
			$id = $input->get('id');
			
			// Get a TableYooRecipe instance
			$table = $this->getTable();
 
			// Load the message
			$table->load($id);
 
			// Assign the message
			$this->msg = $table->greeting;
		}
		return $this->msg;
	}
	
	/**
	 * Get the list of recipes
	 *
	 */
	public function getRecipes()
	{
		if (!isset($this->recipes))
		{
			// Create a new query object.		
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// From the recipe table
			$query->from('#__yoorecipe as r');
			$query->join('LEFT', '#__yoorecipe_category c on r.category_id = c.id');
			
			// Select some fields
			$query->select('r.id, r.title, c.title as category, r.preparation, r.nb_persons, r.difficulty, r.cost, r.creation_date, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.published, r.featured, r.nb_views');
			
			$db->setQuery($query);
			
			// Assign recipes
			$this->recipes = $db->loadObjectList();
			
			foreach ($this->recipes as $recipe)
			{
				// Get recipe's ingredients
				$query->clear();
				
				$query->from('#__yoorecipe_ingredients');
				$query->select('id, recipe_id, quantity, unit, description, group_id');
				$query->where('recipe_id = ' . $db->quote($recipe->id));
				
				$db->setQuery($query);
				$recipe->ingredients = $db->loadObjectList();
			}
		}
		
		return $this->recipes;
	}
	
	/**
	 * Get recipe by identifier
	 */
	public function getRecipeById($recipeId)
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// From the recipe table
		$query->from('#__yoorecipe as r');
		$query->join('LEFT', '#__yoorecipe_category c on r.category_id = c.id');
		
		// Select some fields
		$query->select('r.id, r.title, c.title as category, r.preparation, r.nb_persons, r.difficulty, r.cost, r.creation_date, r.preparation_time, r.picture, r.published, r.nb_views');
		
		$db->setQuery($query);
		
		// Get recipe
		$recipe = $db->loadObject();
		
		// Get recipe's ingredients
		$query->clear();
		
		$query->from('#__yoorecipe_ingredients');
		$query->select('id, recipe_id, quantity, unit, description, group_id');
		$query->where('recipe_id = ' . $db->quote($recipe->id));
		
		$db->setQuery($query);
		$recipe->ingredients = $db->loadObjectList();
		
		return $recipe;
	}
}