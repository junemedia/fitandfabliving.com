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

$cnt = 1;
$html = array();

foreach($this->items as $recipe) {
	
	$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false);

	$cssClass = "";
	$isNotDisplayable = false;
	if (isset($blog_show_pending_recipes)) {
		$isNotDisplayable = $recipe->published_up != 1 || $recipe->published_down != 0 || $recipe->validated == 0;
		if ($isNotDisplayable) {
			$cssClass = "greyedout";
		}
	}
	
	$html[] = '<div id="div_recipe_'.$recipe->id.'" class="yoorecipe-row row-'.$cnt++.' '.$cssClass.'">';
	$html[] = '<div class="yoorecipe-row-item">';
	
	$html[] = '<h2>';
	if ($blog_show_title) {
		$html[] = '<a href="'.$url.'" title="'.htmlspecialchars($recipe->title).'">'.htmlspecialchars($recipe->title);
		if($canShowPrice==1 && $recipe->price!=null && $recipe->price > 0){ 
			$html[] = $recipe->price . $currency;
		}
		$html[] = '</a>';
	}
	
	if ($blog_show_rating && $recipe->note != null)
	{
		if ($blog_rating_style == 'grade') {
			$html[] = '<strong>' . JText::_('COM_YOORECIPE_RECIPE_NOTE') . ': </strong><span> ' . $recipe->note . '/5</span>'; 
		}
		else if ($blog_rating_style == 'stars') {
			$rating = round($recipe->note);
			for ($j = 1 ; $j <= 5 ; $j++) {
				if ($rating >= $j) {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
				}
				else {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
				}
			}
		}
	}
	
	if (!$user->guest && $yooRecipeparams->get('use_favourites', 1) == 1 ) {
		$html[] = '<div id="fav_' . $recipe->id . '">' . JHtml::_('yoorecipeicon.favourites',  $recipe, $yooRecipeparams) . '</div>';
	}
				
	$html[] = '</h2>';
	
	if (isset($blog_show_pending_recipes)) {
	
		if ($recipe->published_up != 1 || $recipe->published_down != 0) {
			$html[] = '<img src="media/com_yoorecipe/images/pending.png" alt="'.htmlspecialchars($recipe->title).'" title="'.JText::_('COM_YOORECIPE_EXPIRED').'"/>';
		} else if (!$recipe->validated) {
			$html[] = '<img src="media/com_yoorecipe/images/pending.png" alt="'.htmlspecialchars($recipe->title).'" title="'.JText::_('COM_YOORECIPE_PENDING_APPROVAL').'"/>';
		}
	}
	
	$html[] = JHtml::_('yoorecipeutils.generateRecipePicture', $recipe->picture, $recipe->title, $blog_is_picture_clickable, $url, $blog_thumbnail_width);

	$html[] = JHtml::_('yoorecipeutils.generateRecipeActions', 
		$recipe,
		$yooRecipeparams,
		$blog_show_category_title,
		$blog_show_difficulty,
		$blog_show_cost,
		$blog_show_preparation_time,
		$blog_show_cook_time,
		$blog_show_wait_time);
	
	if ($blog_show_seasons) {
		$html[] = '<div class="clear">'.JHtml::_('yoorecipeutils.generateRecipeSeason', $recipe->season_id).'</div>';
	}
	
	if ($blog_show_description) {
		$html[] = '<div class="recipe-description">'.JHtml::_('content.prepare', $recipe->description).'</div>';
	}
	
	if ($blog_show_creation_date || $blog_show_author) {
			
		$html[] = '<p id="div-recipe-added-on">';
		if ($blog_show_creation_date) {
			$html[] = JText::_('COM_YOORECIPE_RECIPES_ADDED_ON').' '.JHTML::_('date', $recipe->creation_date).' '; 
		}
		if ($blog_show_author) {
			$authorUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getuserroute', $recipe->created_by) , false);
			$html[] = JText::_('COM_YOORECIPE_BY') . ' ';
			$html[] = '<a href="'.$authorUrl.'">'.$recipe->author_name.'</a>';
		}
		$html[] = '</p>';
	}
	
	$html[] = '<div id="div-recipe-result-info">';
		
	$html[] = JHtml::_('yoorecipeutils.generateManagementPanel', $recipe);
	
	if ($blog_show_nb_views) {
		$html[] = '<p id="div-recipe-views">'.$recipe->nb_views.' '.JText::_('COM_YOORECIPE_RECIPES_READ_TIMES').'</p>';
	}
			
	if ($blog_show_ingredients && count($recipe->ingredients) > 0) {
		$html[] = JHtml::_('yoorecipeutils.generateIngredientsList', $recipe->ingredients);
	}
	
	/*if ($canShowPreparation) {
		$html[] = '<p>';
		$html[] = '<span class="span-recipe-label">'.JText::_('COM_YOORECIPE_RECIPES_PREPARATION').'</span><br/>';
		$html[] = '<span class="span-recipe-directions">'.htmlspecialchars(substr(strip_tags($recipe->preparation), 0, $directions_length)).'...</span>';
		$html[] = '</p>';
	}*/
	
	if ($blog_show_readmore) {
		
		$html[] = '<p>';
		$html[] = '<span><a href="'.$url.'" title="'.JText::_('COM_YOORECIPE_VIEW_DETAILS').'">'.JText::_('COM_YOORECIPE_VIEW_DETAILS').'</a></span>&nbsp;|&nbsp;';
		$html[] = '<span><a href="'.$url .'#comments" title="<'.JText::_('COM_YOORECIPE_COMMENT_RECIPE').'">'.JText::_('COM_YOORECIPE_COMMENT_RECIPE').'</a></span>';
		$html[] = '</p>';
	}
	
	$html[] = '</div>';
	$html[] = '</div>';
	$html[] = '</div>';
	
} // End foreach($this->items as $recipe) {

echo implode("\n", $html);