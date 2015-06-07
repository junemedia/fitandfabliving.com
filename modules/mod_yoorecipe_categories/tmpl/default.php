<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Categories Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe_categories/styles/mod_yoorecipe_categories'. $params->get('moduleclass_sfx').'.css');

JHtmlBehavior::framework();

?>

<div class="mod_yoorecipe_categories">

<?php
	if (strlen($params->get('intro_text')) > 0) :
?>
<div class="intro_text">
	<?php echo $params->get('intro_text'); ?>
</div>
<?php
	endif;
?>
<?php 

	if ($params->get('use_dropdown', 0)) {

		echo '<select onchange="window.location.href = this.value">';
		foreach ($categories as $category) {
		
			$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getcategoryroute', $category->slug) , false);	
			$html = '<option value="'.$url.'">'.str_repeat('&nbsp;&nbsp;&nbsp;', $category->level-1).htmlspecialchars($category->title);
			if ($params->get('show_nb_recipes', 1)){
				$html .= ' (' . $category->nb_recipes . ')';
			}
			$html .= '</option>';
			echo $html;
		}
		echo '</select>';
	}
	
	else {
	
?>
	<ul>
<?php
		foreach ($categories as $category) {
		
			$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getcategoryroute', $category->slug) , false);
			if ($params->get('show_empty_categories', 0)) { ?>
				
				<li>
				<?php echo str_repeat('<span>&nbsp;&nbsp;&nbsp;</span>', $category->level-1)  ?>
					<a href="<?php echo $url; ?>">
				<?php echo str_repeat('', $category->level-1) . htmlspecialchars($category->title) ?> 
				<?php 
					if ($params->get('show_nb_recipes', 1)) { 
						echo ' (' . $category->nb_recipes . ')';
					} 
				?>
					</a>
				</li>		
<?php		}
		
			else { 
		
				if ($category->nb_recipes != 0) { ?>
				
				<li>
				<?php echo str_repeat('<span>&nbsp;&nbsp;&nbsp;</span>', $category->level-1) ?>
					<a href="<?php echo $url; ?>">
				<?php echo str_repeat('', $category->level-1) . htmlspecialchars($category->title) ?> 
				<?php 
					if ($params->get('show_nb_recipes', 1)) { 
							echo ' (' . $category->nb_recipes . ')';
					} 
				?>
					</a>
				</li>			
<?php	
				}
			}
			
		} // End foreach ($categories as $category)
		
		echo '</ul>';
	}
?>

<?php
	if (strlen($params->get('outro_text')) > 0) :
?>
<div class="outro_text">
	<?php echo $params->get('outro_text'); ?>
</div>
<?php
	endif;
?>
</div>