<?php
	/*------------------------------------------------------------------------
	# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
	# ------------------------------------------------------------------------
	# author    YooRock!
	# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
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
	class YooRecipeModelTags extends JModelList
	{
		/**
		* Method to delete all tags of a given recipe
		*/
		public function deleteTagsByRecipeId($recipeId) {
		
			// Create a new query object.		
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// Delete tag
			$query->clear();
			$query->delete('#__yoorecipe_tags');
			$query->where('recipe_id = ' . $db->quote($recipeId));
			
			$db->setQuery((string)$query);
			$db->execute();
			
			return true;
		}
		
		/**
		* Method to delete a tag given his tag_id  and his recipe_id
		*/
		public function deleteTagByTagIdAndRecipeId($tagId, $recipeId)
		{
			// Prepare query
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// Delete tag
			$query->clear();
			$query->delete('#__yoorecipe_tags');
			$query->where('recipe_id = ' . $db->quote($recipeId));
			$query->where('tag_id = '. $db->quote($tagId));
			
			$db->setQuery((string)$query);
			$db->execute();
		}
		
		/**
		* Method to update a tag by a tag_id, his recipe_id and a tag_value
		*/
		public function updateTag($tagId, $recipeId, $value)
		{
			// Prepare query
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			
			// Update tag
			$query->clear();
			$query->update('#__yoorecipe_tags');
			$query->set('tag_value = ' . $db->quote($value));
			$query->where('tag_id = '. $db->quote($tagId));
			$query->where('recipe_id = ' . $db->quote($recipeId));
			
			$db->setQuery((string)$query);
			$db->execute();
		}
		
		/**
		* Method to insert a new tag given a recipe_id and a tag_value
		*/
		public function insertTag($recipeId, $value)
		{
			// Prepare query
			$db = JFactory::getDBO();	
			$query = $db->getQuery(true);
			
			// Insert tag
			$query->clear();
			$query->insert('#__yoorecipe_tags');
			$query->set('recipe_id = '.$db->quote($recipeId));
			$query->set('tag_value = '.$db->quote($value));
			
			$db->setQuery((string)$query);
			if (!$db->execute()) {
				$this->setError($db->getErrorMsg());
				echo $db->getErrorMsg();
				return false;
			}
		}
		
		/**
		* Method to get tags by his recipe_id
		*/
		public function getTagsByRecipeId($recipeId)
		{
			// Prepare query
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			
			// From the recipe table
			$query->select('tag_id, recipe_id, tag_value');
			$query->from('#__yoorecipe_tags');
			$query->where('recipe_id = ' . $db->quote($recipeId));
			
			$db->setQuery((string)$query);
			return $db->loadObjectList();
		}
		
		/**
		* Method to get all tags objects
		*/
		public function getAllTags()
		{
			// Prepare query
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			
			// From the recipe table
			$query->select('tag_id, recipe_id, tag_value');
			$query->from('#__yoorecipe_tags');
			
			$db->setQuery((string)$query);
			return $db->loadObjectList();
		}
	}