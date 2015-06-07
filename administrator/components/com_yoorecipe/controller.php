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
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
 /**
 * General Controller of YooRecipe component
 */
class YooRecipeController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) 
	{
		// set default view if not set
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'YooRecipes'));
 
		// call parent behavior
		parent::display($cachable, $urlparams);
	}
	
	/**
	* Add an ingredient to a given recipe
	* returns the ingredient id
	*/
	function addIngredient()
	{
		// Retrieve parameters
		$input = JFactory::getApplication()->input;
		$recipe_id	 = $input->get('recipe_id');
		$order 		 = $input->get('order', '0', 'INT');
		$quantity 	 = $input->get('quantity');
		$unit 		 = $input->get('unit');
		$description = $input->get('description');
		$price 		 = $input->get('price');
		$group_id	 = $input->get('group_id');
		
		// Prepare query
		$db = JFactory::getDBO();
        
		// Test if recipe already exists
		if ($recipe_id == '0')
		{
			$query = "SELECT max(id) as id FROM #__yoorecipe";
			$db->setQuery( $query );
			$recipe_id = $db->loadResult() + 1;
		}
		
		// Change decimal separator if needed
		$qty = str_replace(',', '.', $quantity);
		$qtyToNum;

		// Test if fractions were used
		if (strpos($quantity, '/') == false) {
			$qtyToNum = $qty;
		} else {
		
			// Turn fractions into decimal value if applicable
			$fraction = array('whole' => 0);
			
			preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $qty, $fraction);
			if ($fraction['denominator'] != 0) {
				$qtyToNum = $fraction['whole'] + $fraction['numerator']/$fraction['denominator'];
			} else {
				$qtyToNum = 0;
			}
		}
		
		// get ingredients model
		$model = $this->getModel('ingredients') ;
		$model->insertIngredient($recipe_id, $order, $qtyToNum, $unit, $description, $price, $group_id);
		
		$query = "SELECT max(id) as id FROM #__yoorecipe_ingredients";
		$db->setQuery( $query );
		echo $db->loadResult();
	}
	
	/**
	* Delete an ingredient from a given ingredient id
	*/
	function deleteIngredient()
	{
		// Retrieve parameters
		$input = JFactory::getApplication()->input;
		$ingredientId = $input->get('ingredientId');
		
		// get ingredients model
		$model = $this->getModel('ingredients') ;
		$model->deleteIngredientByIngredientId($ingredientId);
		
		echo $ingredientId;
	}
	
	/**
	* Update an ingredient from a given ingredient id
	*/
	function updateIngredient() 
	{
		// Retrieve parameters
		$input = JFactory::getApplication()->input;
		$ingredientId 	= $input->get('ingredientId');
		$order 			= $input->get('order');
		$quantity 		= $input->get('quantity');
		$unit 			= $input->get('unit');
		$description 	= $input->get('description');
		$price			= $input->get('price');
		$group_id		= $input->get('group_id');
		
		// Change decimal separator if needed
		$qty = str_replace(',', '.', $quantity);
		$qtyToNum;

		// Test if fractions were used
		if (strpos($quantity, '/') == false) {
			$qtyToNum = $qty;
		} else {
		
			// Turn fractions into decimal value if applicable
			$fraction = array('whole' => 0);
			
			preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $qty, $fraction);
			if ($fraction['denominator'] != 0) {
				$qtyToNum = $fraction['whole'] + $fraction['numerator']/$fraction['denominator'];
			} else {
				$qtyToNum = 0;
			}
		}
		
		// get ingredients model
		$model = $this->getModel('ingredients') ;
		$model->updateIngredient($ingredientId, $order, $qtyToNum, $unit, $description, $price, $group_id);
	}
}