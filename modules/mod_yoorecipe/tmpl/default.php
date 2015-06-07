<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Random Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access

require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php';

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe/styles/mod_yoorecipe'.$params->get('moduleclass_sfx').'.css');

JHtmlBehavior::framework();

$displayType 	= $params->get('display', 'block');
$use_watermark	= $params->get('use_watermark', 1);
$canShowPrice	= $params->get('show_price', 0);

$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
$currency			= $yooRecipeparams->get('currency');

$random = rand (999, 999999999 ); // used to avoid js conflicts if module used more than once 
?>

<?php
	if (strlen($params->get('intro_text')) > 0) :
?>
<div class="intro_text">
	<?php echo $params->get('intro_text'); ?>
</div>
<?php
	endif;
?>
<ul class="ul_recipes">
<?php
	foreach ($items as $item) {
	
		if ($displayType == 'accordion') {
			// Take care of slider
			$script = 'window.addEvent(\'domready\', function(){ ';
			$script .= 'var mySlide'.$random . '_' .$item->id. '_' .$item->cat_id.' = new Fx.Slide(\'slide'.$random . '_' .$item->id.'_'.$item->cat_id.'\'); ';
			$script .= 'mySlide'. $random . '_' .$item->id . '_'. $item->cat_id . '.hide(); ';
			$script .= ' $(\'toggle'.$random . '_' .$item->id .'_'. $item->cat_id . '\').addEvent(\'mousedown\', function(e){ e = new Event(e); mySlide'.$random . '_' .$item->id .'_'. $item->cat_id .'.toggle(); return false; }); }); ';
			$document->addScriptDeclaration( $script );
		}
		
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
		
		// Format title tag
		$chunkedItemTitle;
		if (strlen($item->title) > $params->get('recipe_title_max_length', 20)) {
			$chunkedItemTitle = substr (htmlspecialchars($item->title), 0, $params->get('recipe_title_max_length', 20)) . '...';
		}
		else {
			$chunkedItemTitle = htmlspecialchars($item->title);
		}
		
		$formattedTitle;
		if ($params->get('recipe_title_tag') == 'h1') {
			$formattedTitle = '<h1>' . $chunkedItemTitle . '</h1>';
		} else if ($params->get('recipe_title_tag') == 'h2') {
			$formattedTitle = '<h2>' . $chunkedItemTitle . '</h2>';
		} else if ($params->get('recipe_title_tag') == 'h3') {
			$formattedTitle = '<h3>' . $chunkedItemTitle . '</h3>';
		} else if ($params->get('recipe_title_tag') == 'h4') {
			$formattedTitle = '<h4>' . $chunkedItemTitle . '</h4>';
		} else if ($params->get('recipe_title_tag') == 'h5') {
			$formattedTitle = '<h5>' . $chunkedItemTitle . '</h5>';
		} else {
			$formattedTitle = '<strong>' . $chunkedItemTitle . '</strong>';
		}
	?>
    <li>
<?php	if ($displayType == 'accordion') { ?>
		<div id="toggle<?php echo $random . '_' . $item->id .'_'. $item->cat_id; ?>" name="toggle<?php echo $item->id .'_'. $item->cat_id; ?>"><a style="cursor:pointer"><?php echo $formattedTitle ?> <?php if($canShowPrice==1 && $item->price!=null){echo $item->price . $currency;} ?> </a></div>
		<div id="slide<?php echo $random . '_' . $item->id .'_'. $item->cat_id; ?>" class="recipe_container_<?php echo $params->get('text_align_class_sfx'); ?>">
<?php	} else { ?>
		<div><a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=viewRecipe&id=' . $item->slug); ?>">  <?php if($canShowPrice==1 && $item->price!=null){ echo $item->price . $currency;} ?>	<!--AFFICHAGE PRIX--> <?php echo $formattedTitle ?></a></div>
		<div class="recipe_container_<?php echo $params->get('text_align_class_sfx'); ?>">
<?php 	} ?>
			<a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=viewRecipe&id=' . $item->slug); ?>">
				<img class="recipe-picture-thumb" src="<?php echo $picturePath; ?>" width="<?php echo $params->get('thumbnail_size', 150); ?>px"
					title="<?php echo htmlspecialchars($item->title); ?>"
					alt="<?php echo htmlspecialchars($item->title);  ?>"
				/>
			</a>
		<?php
	 
			if ($params->get('show_difficulty', 1)) {
		
				echo '<br/><span class="difficulty">' . JText::_('MOD_YOORECIPE_RECIPES_DIFFICULTY') . ' ';
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
		
			if ($params->get('show_cost', 1)) {
			
				echo '<br/><span class="cost">' . JText::_('MOD_YOORECIPE_RECIPES_COST') . ' ';
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
		
			if ($params->get('show_rating', 1)) {
				if ($item->note != null)  {
					
					echo '<br/>';
					if ($params->get('rating_style', 'stars') == 'grade') {
						echo '<strong>' . JText::_('MOD_YOORECIPE_RECIPE_NOTE') . ': </strong><span> ' . $item->note . '/5</span>'; 
					}
					else if ($params->get('rating_style', 'stars') == 'stars') {
						echo '<strong>' . JText::_('MOD_YOORECIPE_RECIPE_NOTE') . ': </strong>';
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
			if ($params->get('show_ingredients', 1)) {
				if (count($item->ingredients) > 0) :
					echo '<br/><span class="ingredientsTitle">' . JText::_('MOD_YOORECIPE_RECIPES_INGREDIENTS') . ': </span><br/>';
					echo '<span class="ingredientsList">';
					for ($i = 0; $i < count($item->ingredients)-1; $i++) {
						echo htmlspecialchars($item->ingredients[$i]->description) . ', ';
					}
					echo htmlspecialchars($item->ingredients[count($item->ingredients)-1]->description) . '.';
					echo '</span>';
				endif;
			}
			
			if ($params->get('show_description', 1)) {
				if ($item->description != '') :
					echo '<br/><span class="ingredientsTitle">' . JText::_('MOD_YOORECIPE_RECIPES_DESCRIPTION') . ': </span><br/>';
					echo '<div>' . $item->description . '</div>';
				endif;
			}
			
			if ($params->get('show_preparation_time', 1)) {
				echo '<br/><span class="preparation_time">' . JText::_('MOD_YOORECIPE_RECIPES_PREPARATION') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->preparation_time) . '</span>';
			}
			if ($params->get('show_cook_time', 1)) {
				echo '<br/><span class="cook_time">' . JText::_('MOD_YOORECIPE_RECIPES_COOK_TIME') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->cook_time) . '</span>';
			}
			if ($params->get('show_wait_time', 1)) {
				echo '<br/><span class="wait_time">' . JText::_('MOD_YOORECIPE_RECIPES_WAIT_TIME') . ': ' . JHtml::_('yoorecipeutils.formattime', $item->wait_time) . '</span>';
			}
			
			if ($params->get('show_readmore', 1)) {				
				
				echo '<p class="mod_yoorecipe_readmore">';
				echo '<a href="' .JRoute::_(JHtml::_('yoorecipehelperroute.getreciperoute', $item->slug)) . '">';
				echo JText::_('MOD_YOORECIPE_READ_MORE');
				echo '</a>';
				echo '</p>';
			}
		?>
		</div>
    </li>
<?php
	}
?>
</ul>