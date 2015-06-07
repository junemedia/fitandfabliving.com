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

abstract class JHtmlImpexUtils
{
	/**
	 * Generate XML that contains recipe information
	 */
	static function recipes2XML($ids) {

		$xml = '&lt;?xml version="1.0"?&gt;';
		$xml .= '&lt;recipes&gt;';
		
		$modelYooRecipe	= JModelLegacy::getInstance('yoorecipe','YooRecipeModel');
		
		foreach ($ids as $id) :

			// Get full recipe
			$recipe					= $modelYooRecipe->getItem($id);
			$recipe->categories 	= $modelYooRecipe->getRecipeCategories($id);
			$recipe->ingredients 	= $modelYooRecipe->getRecipeIngredients($id);
			$recipe->ratings		= $modelYooRecipe->getRatingsByRecipeId($id);
			
			//echo print_r($recipe, true);
		
			$xml = '&lt;recipe&gt;';
			$xml .= '&lt;id&gt;' . $id. '&lt;/id&gt;';
			$xml .= '&lt;category_id&gt;' . $recipe->category_id . '&lt;/category_id&gt;';
			
			// Cross categories
			$xml .= '&lt;categories&gt;';
			foreach ($recipe->categories as $category) :
			
				$xml .= '&lt;category&gt;';
				$xml .= '&lt;recipe_id&gt;' . $id .'&lt;/recipe_id&gt;';
				$xml .= '&lt;cat_id&gt;' . $category->id . '&lt;/cat_id&gt;';
				$xml .= '&lt;/category&gt;';
				
			endforeach;
			$xml .= '&lt;/categories&gt;';
			
			// Ingredients
			$xml .= '&lt;ingredients&gt;';
			foreach ($recipe->ingredients as $ingredient) :
			
				$xml .= '&lt;ingredient&gt;';
				$xml .= '&lt;id&gt;' . $ingredient->id. '&lt;/id&gt;';
				$xml .= '&lt;recipe_id&gt;' . $ingredient->recipe_id . '&lt;/recipe_id&gt;';
				$xml .= '&lt;ordering&gt;' . $ingredient->ordering . '&lt;/ordering&gt;';
				$xml .= '&lt;quantity&gt;' . $ingredient->quantity . '&lt;/quantity&gt;';
				$xml .= '&lt;unit&gt;' . $ingredient->unit . '&lt;/unit&gt;';
				$xml .= '&lt;description&gt;' . $ingredient->description . '&lt;/description&gt;';
				$xml .= '&lt;/ingredient&gt;';
				
			endforeach;
			$xml .= '&lt;/ingredients&gt;';
			
			// Ratings
			$xml .= '&lt;ratings&gt;';
			foreach ($recipe->ratings as $rating) :
				$xml .= '&lt;id&gt;' . $rating->id . '&lt;/id&gt;';
				$xml .= '&lt;recipe_id&gt;' . $rating->recipe_id . '&lt;/recipe_id&gt;';
				$xml .= '&lt;note&gt;' . $rating->note . '&lt;/note&gt;';
				$xml .= '&lt;author&gt;' . $rating->author . '&lt;/author&gt;';
				$xml .= '&lt;user_id&gt;' . $rating->user_id . '&lt;/user_id&gt;';
				$xml .= '&lt;email&gt;' . $rating->email . '&lt;/email&gt;';
				$xml .= '&lt;comment&gt;' . $rating->comment . '&lt;/comment&gt;';
				$xml .= '&lt;creation_date&gt;' . $rating->creation_date . '&lt;/creation_date&gt;';
			endforeach;
			$xml .= '&lt;/ratings&gt;';
			
			$xml .= '&lt;created_by&gt;' . $recipe->created_by . '&lt;/created_by&gt;';
			$xml .= '&lt;title&gt;' . $recipe->title . '&lt;/title&gt;';
			$xml .= '&lt;alias&gt;' . $recipe->alias . '&lt;/alias&gt;';
			$xml .= '&lt;description&gt;' . $recipe->description . '&lt;/description&gt;';
			$xml .= '&lt;preparation&gt;' . $recipe->preparation . '&lt;/preparation&gt;';
			$xml .= '&lt;servings_type&gt;' . $recipe->servings_type . '&lt;/servings_type&gt;';
			$xml .= '&lt;nb_persons&gt;' . $recipe->nb_persons . '&lt;/nb_persons&gt;';
			$xml .= '&lt;difficulty&gt;' . $recipe->difficulty . '&lt;/difficulty&gt;';
			$xml .= '&lt;cost&gt;' . $recipe->cost . '&lt;/cost&gt;';
			$xml .= '&lt;creation_date&gt;' . $recipe->creation_date . '&lt;/creation_date&gt;';
			$xml .= '&lt;preparation_time&gt;' . $recipe->preparation_time . '&lt;/preparation_time&gt;';
			$xml .= '&lt;cook_time&gt;' . $recipe->cook_time . '&lt;/cook_time&gt;';
			$xml .= '&lt;wait_time&gt;' . $recipe->wait_time . '&lt;/wait_time&gt;';
			$xml .= '&lt;featured&gt;' . $recipe->featured . '&lt;/featured&gt;';
			$xml .= '&lt;picture&gt;' . $recipe->picture . '&lt;/picture&gt;';
			$xml .= '&lt;published&gt;' . $recipe->published . '&lt;/published&gt;';
			$xml .= '&lt;validated&gt;' . $recipe->validated . '&lt;/validated&gt;';
			$xml .= '&lt;nb_views&gt;' . $recipe->nb_views . '&lt;/nb_views&gt;';
			$xml .= '&lt;note&gt;' . $recipe->note . '&lt;/note&gt;';
			$xml .= '&lt;/recipe&gt;';
		endforeach;
		
		$xml .= '&lt;/recipes&gt;';
		echo print_r($xml, true);
		//header('Content-Type: application/$xml;');
		//echo $xml;
	}

	/**
	 * Create recipes from XML
	 */
	static function XML2recipes() {
	
		require JPATH_ADMINISTRATOR.'/components/com_jce/classes/xml.php';
		$file = 'C:/wamp/www/Joomla_2.5.6/administrator/components/com_yoorecipe/helpers/html/recipes.xml';
		if (!method_exists('JFactory', 'getXML')) {
    		$xml = JFactory::getXML('Simple');
		
			if (!$xml->loadFile($file)) {
				unset($xml);
				return false;
			}
    	} else {
    		$xml = WFXMLElement::getXML($file);
    	}

		// Prepare final object to persist
		//print_r($xml);
		//print_r($xml->recipe);
		//print_r($xml);
		// Loop over recipes
	//	$recipes[];
		$recipes = array();
		foreach($xml->recipe as $xmlRecipe) {
		
			$recipe = new stdClass;
			$recipe->id 					= $xmlRecipe->id[0]->data();
			$recipe->category_id 			= $xmlRecipe->category_id[0]->data();
			
			// Build cross categories
			$categories = array();
			foreach($xmlRecipe->categories->category as $xmlCategory) {
			
				$category = new stdClass;
				$category->recipe_id		= $xmlCategory->recipe_id[0]->data();
				$category->cat_id			= $xmlCategory->cat_id[0]->data();
				
				$categories[] = $category;
			}
			$recipe->categories = $categories;
			
			// Build ingredients
			$ingredients = array();
			foreach($xmlRecipe->ingredients->ingredient	as $xmlIngredient) {
			
				$ingredient					= new stdClass;
				$ingredient->id				= $xmlIngredient->id[0]->data();
				$ingredient->recipe_id		= $xmlIngredient->recipe_id[0]->data();
				$ingredient->ordering		= $xmlIngredient->ordering[0]->data();
				$ingredient->quantity		= $xmlIngredient->quantity[0]->data();
				$ingredient->unit			= $xmlIngredient->unit[0]->data();
				$ingredient->description	= $xmlIngredient->description[0]->data();
				
				$ingredients[] = $ingredient;
			}
			$recipe->ingredients = $ingredients;
			
			// Build ratings
			$ratings = array();
			foreach ($xmlRecipe->ratings->rating as $xmlRating) :
			
				$rating					= new stdClass;
				$rating->id 			= $xmlRating->id[0]->data();
				$rating->recipe_id 		= $xmlRating->recipe_id[0]->data();
				$rating->note 			= $xmlRating->note[0]->data();
				$rating->author 		= $xmlRating->author[0]->data();
				$rating->user_id 		= $xmlRating->user_id[0]->data();
				$rating->email 			= $xmlRating->email[0]->data();
				$rating->comment 		= $xmlRating->comment[0]->data();
				$rating->creation_date 	= $xmlRating->creation_date[0]->data();
				
				$ratings[] = $rating;
			endforeach;
			$recipe->ratings = $ratings;
			
			
			$recipe->created_by 		= $xmlRecipe->created_by[0]->data();
			$recipe->title				= $xmlRecipe->title[0]->data();
			$recipe->alias				= $xmlRecipe->alias[0]->data();
			$recipe->description		= $xmlRecipe->description[0]->data();
			$recipe->preparation		= $xmlRecipe->preparation[0]->data();
			$recipe->servings_type		= $xmlRecipe->servings_type[0]->data();
			$recipe->nb_persons			= $xmlRecipe->nb_persons[0]->data();
			$recipe->difficulty			= $xmlRecipe->difficulty[0]->data();
			$recipe->cost				= $xmlRecipe->cost[0]->data();
			$recipe->creation_date		= $xmlRecipe->creation_date[0]->data();
			$recipe->preparation_time	= $xmlRecipe->preparation_time[0]->data();
			$recipe->cook_time			= $xmlRecipe->cook_time[0]->data();
			$recipe->wait_time			= $xmlRecipe->wait_time[0]->data();
			$recipe->featured			= $xmlRecipe->featured[0]->data();
			$recipe->picture			= $xmlRecipe->picture[0]->data();
			$recipe->published			= $xmlRecipe->published[0]->data();
			$recipe->validated			= $xmlRecipe->validated[0]->data();
			$recipe->nb_views			= $xmlRecipe->nb_views[0]->data();
			$recipe->note				= $xmlRecipe->note[0]->data();
			
			$recipes[] = $recipe;
			//print_r ($xmlRecipe->categories->category[0]);
		}
		
		print_r($recipes);
		
		return $recipes;
	
		//$xml = JFactory::getXML('Simple');
		//$xml = new JSimpleXML();
		//$xml->loadFile('C:/wamp/www/Joomla_2.5/administrator/components/com_yoorecipe/helpers/html/simple.xml');
		
		
		
		//print_r($xml);
		//print_r( $xml->document->toString());
		 
		// access a given node's CDATA
		//print $xml->root->node->child[0]->data(); // Tom Foo
		 
		// access attributes
		//$attr = $xml->root->node->child[1]->attributes();
		//print $attr['gender']; // f

		// access children
		/*foreach($xml->root->node->children() as $child) {
			print $child->data();
		}*/
	}
}