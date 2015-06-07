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
	$cssClass = ($cnt % 2 == 1) ? "lft column-1" : "rgt column-2";

	if (isset($blog_show_pending_recipes)) {
		$isNotDisplayable = $recipe->published_up != 1 || $recipe->published_down != 0 || $recipe->validated == 0;
		if ($isNotDisplayable) {
			$cssClass .= " greyedout";
		}
	}
	
	if ($cnt % 2 == 1) {
		// Add line container
		$html[] = '<div class="items-row cols-2 row-' . $cnt . ' row-fluid">';
	}
	
	$html[] = '<div id="div_recipe_' . $recipe->id .'" class="yoorecipe-row item span6 row-' . $cnt . ' ' . $cssClass .'">';
	$html[] = '<div class="yoorecipe-row-item">';
	
	$html[] = '<div class="recipe-picture">';
	$html[] = JHtml::_('yoorecipeutils.generateRecipePicture', $recipe->picture, $recipe->title, $blog_is_picture_clickable, $url, $blog_thumbnail_width);
	
	if ($blog_show_rating) {
	
		$html[] = '<div class="recipe-rating"><center>';
		if ($recipe->note != null)
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
		} else { $html[] ='&nbsp;';}
		$html[] = '</center></div>';
	}
	$html[] = '</div>';
	
	$html[] = '<div class="recipe-container">';
	
	if ($blog_show_title) {
		$html[] = '<div class="recipe-title">';
		$html[] = '<a href="' . $url . '" title="'. htmlspecialchars($recipe->title) . '" target="_self">' . htmlspecialchars($recipe->title) . '</a>';
		$html[] = '</div>';
	}
	
	if (isset($blog_show_pending_recipes)) {
	
		if ($recipe->published_up != 1 || $recipe->published_down != 0) {
			$html[] = '<img src="media/com_yoorecipe/images/pending.png" alt="'.htmlspecialchars($recipe->title).'" title="'.JText::_('COM_YOORECIPE_EXPIRED').'"/>';
		} else if (!$recipe->validated) {
			$html[] = '<img src="media/com_yoorecipe/images/pending.png" alt="'.htmlspecialchars($recipe->title).'" title="'.JText::_('COM_YOORECIPE_PENDING_APPROVAL').'"/>';
		}
	}
	
	if (!$user->guest && $yooRecipeparams->get('use_favourites', 1) == 1 ) {
		$html[] = '<div class="fav-icon" id="fav_' . $recipe->id . '">' . JHtml::_('yoorecipeicon.favourites',  $recipe, $yooRecipeparams) . '</div>';
	}
	
	if ($blog_show_creation_date) {
		$html[] = '<div class="recipe-creation-date">';
		$html[] = JText::_('COM_YOORECIPE_RECIPES_ADDED_ON') . ' ' . JHTML::_('date', $recipe->creation_date);
		$html[] = '</div>';
	}
	
	if ($blog_show_author) {

		$authorUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getuserroute', $recipe->created_by) , false);
		$html[] = '<div class="recipe-author">';
		$html[] = JText::_('COM_YOORECIPE_BY').' '.'<a href="'.$authorUrl.'">'.$recipe->author_name.'</a>';
		$html[] = '</div>';
	}
	
	if ($blog_show_nb_views) {
		$html[] = '<div class="recipe-nbviews">';
		$html[] = $recipe->nb_views . ' ' . JText::_('COM_YOORECIPE_RECIPES_READ_TIMES');
		$html[] = '</div>';
	}
	
	if ($blog_show_description) {
		$html[] = '<div class="recipe-desc">'.$recipe->description.'</div>';
	}
	
	if ($blog_show_category_title) {
		$html[] = '<div class="recipe-categories">';
		$html[] = JHtml::_('yoorecipeutils.generateCrossCategories', $recipe);
		$html[] = '</div>';
	}
	
	if ($blog_show_seasons) {
		$html[] = '<div class="clear">'.JHtml::_('yoorecipeutils.generateRecipeSeason', $recipe->season_id).'</div>';
	}
	
	if ($blog_show_ingredients) {
		$html[] = JHtml::_('yoorecipeutils.generateIngredientsList', $recipe->ingredients);
	}
	
	if ($blog_show_preparation_time && $recipe->preparation_time != 0) {
		$html[] = '<div class="recipe-preptime">'.JText::_('COM_YOORECIPE_YOORECIPE_PREPARATION_TIME_LABEL').': '.JHtml::_('yoorecipeutils.formattime', $recipe->preparation_time).'</div>';
	}
	
	if ($blog_show_cook_time && $recipe->cook_time != 0) {
		$html[] = '<div class="recipe-cooktime">'.JText::_('COM_YOORECIPE_RECIPES_COOK_TIME').': '.JHtml::_('yoorecipeutils.formattime', $recipe->cook_time).'</div>';
	}
	
	if ($blog_show_wait_time && $recipe->wait_time != 0) {
		$html[] = '<div class="recipe-waittime">'.JText::_('COM_YOORECIPE_RECIPES_WAIT_TIME').': '.JHtml::_('yoorecipeutils.formattime', $recipe->wait_time).'</div>';		
	}
	
	if ($blog_show_difficulty) {
		
		$html[] = '<div class="recipe-difficulty">' . JText::_('COM_YOORECIPE_RECIPES_DIFFICULTY') . ': ';
		for ($j = 1 ; $j <= 4; $j++) {
				
			if ($recipe->difficulty >= $j) {
				$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
			}
			else {
				$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
			}
		}
		$html[] = '</div>';
	}
	
	if ($blog_show_cost) {
		
		$html[] = '<div class="recipe-cost">' . JText::_('COM_YOORECIPE_RECIPES_COST') . ': ';
		for ($j = 1 ; $j <= 3 ; $j++) {
			if ($recipe->cost >= $j) {
				$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
			}
			else {
				$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
			}
		}
		$html[] = '</div>';
	}
	
	if ($blog_show_readmore) {
	
		$html[] = '<div class="recipe-readmore">';
		$html[] = '<a href="'.$url.'" title="'.JText::_('COM_YOORECIPE_VIEW_DETAILS').'">'.JText::_('COM_YOORECIPE_VIEW_DETAILS').'</a>&nbsp;|&nbsp;';
		$html[] = '<span><a href="'.$url.'#comments'.'" title="'.JText::_('COM_YOORECIPE_COMMENT_RECIPE').'">'.JText::_('COM_YOORECIPE_COMMENT_RECIPE').'</a></span>';
		$html[] = '</div>';
	}
	
	$html[] = '<div class="recipe-btns">';
	$html[] = JHtml::_('yoorecipeutils.generateManagementPanel', $recipe);
	$html[] = '</div>';
	
	$html[] = '</div>';
	$html[] = '</div>';
	$html[] = '</div>';
	
	$html[] = ($cnt % 2 == 0) ? '<div class="clear"></div>' : '';
	
	if ($cnt % 2 == 0) {
		// Close line container
		$html[] = '</div>';
	}
	
	$cnt++;
			
} // End foreach($this->items as $recipe) {

// Support odd number of recipes
$html[] = ($cnt % 2 == 0) ? '<div class="clear"></div>' : '';
$html[] = ($cnt % 2 == 0) ? '</div>' : '';

echo implode("\n", $html);