<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Fvourite Recipes Module
# ----------------------------------------------------------------------
# Copyright (C) 2012 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access

require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php';

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe_favourites/styles/mod_yoorecipe_favourites' .  $params->get('moduleclass_sfx') . '.css');

JHtmlBehavior::framework();

$use_watermark	= $params->get('use_watermark', 1);
?>

<?php
	if (strlen($params->get('intro_text')) > 0) :
?>
<div class="intro_text">
	<?php echo $params->get('intro_text'); ?>
</div>
<?php
	endif;

	if (count($items) == 0) {
		echo JText::_('MOD_YOORECIPE_FAVOURITES_NO_FAVOURITES_YET');
	} else {
?>
<ul class="ul_favourite_recipes">
<?php
	foreach ($items as $item) {
		
		// Take care of picture
		$picturePath = '';
		if ($item->picture != '') {
			$picturePath = $item->picture;
		} else {
			$picturePath = 'media/com_yoorecipe/images/no-image.jpg';
		}
		
		if ($use_watermark) {
			$picturePath = JHtml::_('yoorecipeutils.watermarkImage', $picturePath, 'Copyright ' . juri::base());
		}
		
	?>
    <li>
<?php
	if ($params->get('show_title', 0)) {
	
		// Format title tag
		$chunkedItemTitle;
		if (strlen($item->title) > $params->get('recipe_title_max_length', 20)) {
			$chunkedItemTitle = substr (htmlspecialchars($item->title), 0, $params->get('recipe_title_max_length', 20)) . '...';
		}
		else {
			$chunkedItemTitle = htmlspecialchars($item->title);
		}
		
		echo '<div><a href="' . JRoute::_('index.php?option=com_yoorecipe&task=viewRecipe&id=' . $item->slug) . '"><strong>' . $chunkedItemTitle . '</strong></a></div>';
	}
	
	if 	($params->get('show_picture',1)) {
	?>
		<a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=viewRecipe&id=' . $item->slug); ?>">
			<img class="recipe-picture-thumb" src="<?php echo $picturePath; ?>" width="<?php echo $params->get('thumbnail_size', 60); ?>px"
				title="<?php echo htmlspecialchars($item->title); ?>"
				alt="<?php echo htmlspecialchars($item->title);  ?>"
			/>
		</a>
		<?php
	 }
		if ($params->get('show_difficulty', 0)) {
	
			echo '<br/><span class="difficulty">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_DIFFICULTY') . ' ';
			for ($j = 1 ; $j <= 4; $j++) {
			
				if ($item->difficulty >= $j) {
					echo '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
				}
				else {
					echo '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
				}
			}
			echo '</span>';
		}
	
		if ($params->get('show_cost', 0)) {
		
			echo '<br/><span class="cost">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_COST') . ' ';
			for ($j = 1 ; $j <= 3 ; $j++) {
				if ($item->cost >= $j) {
					echo '<img src="media/com_yoorecipe/images/star-icon.png"/>';
				}
				else {
					echo '<img src="media/com_yoorecipe/images/star-icon-empty.png"/>';
				}
			}
			echo '</span>';
		}
	
		if ($params->get('show_rating', 0)) {
			if ($item->note != null)  {
				
				echo '<br/>';
				if ($params->get('rating_style', 'stars') == 'grade') {
					echo '<strong>' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPE_NOTE') . ': </strong><span> ' . $item->note . '/5</span>'; 
				}
				else if ($params->get('rating_style', 'stars') == 'stars') {
					echo '<strong>' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPE_NOTE') . ': </strong>';
					$rating = round($item->note);
					for ($j = 1 ; $j <= 5 ; $j++) {
						if ($rating >= $j) {
							echo '<img src="media/com_yoorecipe/images/star-icon.png" title="' . $item->note . '/5" alt=""/>';
						}
						else {
							echo '<img src="media/com_yoorecipe/images/star-icon-empty.png" title="' . $item->note . '/5" alt=""/>';
						}
					}
				}
			}
		}
		if ($params->get('show_ingredients', 0)) {
			if (count($item->ingredients) > 0) :
				echo '<br/><span class="ingredientsTitle">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_INGREDIENTS') . ': </span><br/>';
				echo '<span class="ingredientsList">';
				for ($i = 0; $i < count($item->ingredients)-1; $i++) {
					echo htmlspecialchars($item->ingredients[$i]->description) . ', ';
				}
				echo htmlspecialchars($item->ingredients[count($item->ingredients)-1]->description) . '.';
				echo '</span>';
			endif;
		}
		
		if ($params->get('show_preparation_time', 0)) {
			echo '<br/><span class="preparation_time">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_PREPARATION') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->preparation_time) . '</span>';
		}
		if ($params->get('show_cook_time', 0)) {
			echo '<br/><span class="cook_time">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_COOK_TIME') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->cook_time) . '</span>';
		}
		if ($params->get('show_wait_time', 0)) {
			echo '<br/><span class="wait_time">' . JText::_('MOD_YOORECIPE_FAVOURITES_RECIPES_WAIT_TIME') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->wait_time) . '</span>';
		}
		
		if ($params->get('show_readmore', 0)) {				
			
			echo '<p class="mod_yoorecipe_readmore">';
			echo '<a href="' .JRoute::_(JHtml::_('yoorecipehelperroute.getreciperoute', $item->slug)) . '">' . JText::_('MOD_YOORECIPE_FAVOURITES_READ_MORE') . '</a>';
			echo '</p>';
		}
		?>
    </li>
<?php
	}
?>
</ul>
<?php
	echo '<a href="'. JRoute::_('index.php?option=com_yoorecipe&view=favourites&layout=favourites') .'">'. JText::_('MOD_YOORECIPE_FAVOURITES_VIEW_ALL_FAVOURITES') . '</a>';
 } // End if (count($items) > 0) { 