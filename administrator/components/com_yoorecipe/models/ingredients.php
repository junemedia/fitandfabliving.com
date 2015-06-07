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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * YooRecipeList Model
 */
class YooRecipeModelIngredients extends JModelList
{
	/**
	 * deleteIngredientsByRecipeId
	 */
	public function deleteIngredientsByRecipeId($recipeId) {
	
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Delete recipe ingredients
		$query->delete('#__yoorecipe_ingredients');
		$query->where('recipe_id = ' . $db->quote($recipeId));
		
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	/**
	 * deleteIngredientByIngredientId
	 */
	public function deleteIngredientByIngredientId($ingredientId)
	{
		// Prepare query
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->from('#__yoorecipe_ingredients');
		$query->delete();
		$query->where('id = '.(int)$ingredientId);
		
		$db->setQuery((string)$query);
		$db->execute();
	}
	
	/**
	 * updateIngredient
	 */
	public function updateIngredient($ingredientId, $order, $quantity, $unit, $description, $price, $group_id)
	{
		// Prepare query
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->update('#__yoorecipe_ingredients');
		$query->set('ordering = ' . $db->quote($order));
		$query->set('quantity = ' . $db->quote($quantity));
		$query->set('unit = ' . $db->quote($unit));
		$query->set('description = ' . $db->quote($description));
		$query->set('price = ' . $db->quote($price));
		$query->set('group_id = ' . $db->quote($group_id));
		
		$query->where('id = ' .$db->quote($ingredientId));
		
		$db->setQuery((string)$query);
		$db->execute();
	}
	
	/**
	 * insertIngredient
	 */
	public function insertIngredient($recipe_id, $ordering, $quantity, $unit, $description, $price, $group_id)
	{
		// Prepare query
		$db = JFactory::getDBO();	
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->insert('#__yoorecipe_ingredients');
		$query->set('ordering = '.$db->quote($ordering));
		$query->set('quantity = '.$db->quote($quantity));
		$query->set('recipe_id = '.$db->quote($recipe_id));
		$query->set('unit = '.$db->quote($unit));
		$query->set('description = '.$db->quote($description));
		$query->set('price = ' . $db->quote($price));
		$query->set('group_id = ' . $db->quote($group_id));
		
		$db->setQuery((string)$query);
		if (!$db->execute()) {
			$this->setError($db->getErrorMsg());
			echo $db->getErrorMsg();
			return false;
		}
	}
	
	/**
	 * getListOfIngredientsGroups
	 */
	public function getListOfIngredientsGroups() {
	
		// Prepare query
		$db = JFactory::getDBO();	
		$query = $db->getQuery(true);
		$query->clear();

		$query->select('id, text');
		$query->from('#__yoorecipe_ingredients_groups');
		$db->setQuery((string)$query);
		
		$groups = $db->loadObjectList();
		if (!$groups) {
			$this->setError($db->getErrorMsg());
			echo $db->getErrorMsg();
			return false;
		}
		
		return $groups;
	}
}