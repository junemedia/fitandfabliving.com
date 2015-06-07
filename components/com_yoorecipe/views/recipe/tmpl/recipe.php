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

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtmlBehavior::framework();

JHtml::addIncludePath(JPATH_COMPONENT.'/lib');

$document = JFactory::getDocument();
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_single.css');
$document->addStyleSheet('media/com_yoorecipe/styles/bluecurve/bluecurve.css');
$document->addScript('media/com_yoorecipe/js/range.js');
$document->addScript('media/com_yoorecipe/js/timer.js');
$document->addScript('media/com_yoorecipe/js/slider.js');

// Init variables
$input	 	= JFactory::getApplication()->input;
$user 		= JFactory::getUser();
$recipe 	= $this->recipe;
$isPrinting = $input->get('print', '0', 'INT');

// Add scripts
$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getSmoothScrollScript'));
$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getReportAbusiveCommentScript'));
if (!$user->guest)
{
	$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getAddToFavouritesScript'));
	$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.removeFromFavouritesScript'));
}
if (isset($this->canManageComments) && $this->canManageComments) {
	$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getDeleteCommentScript'));
}

// Anti spam code generation
$int1 = rand ( 0 , 5 );
$int2 = rand ( 0 , 4 );

// Component Parameters
$yooRecipeparams 			= JComponentHelper::getParams('com_yoorecipe');
$thumbnail_width			= $yooRecipeparams->get('thumbnail_width', 250);

$canShowPrice				= $yooRecipeparams->get('show_price', 0);
$currency					= $yooRecipeparams->get('currency', '&euro;');

// Menu Parameters also defined in Component Settings
$enable_comments			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'enable_comments', 1);
$use_automatic_numbering	= $yooRecipeparams->get('use_automatic_numbering', 1);
$use_quantity_converter		= $yooRecipeparams->get('use_quantity_converter', 1);
$show_author				= $yooRecipeparams->get('show_author', 1);
$use_default_picture		= $yooRecipeparams->get('use_default_picture', 1);
$use_watermark				= $yooRecipeparams->get('use_watermark', 1);

$register_to_comment		= $yooRecipeparams->get('register_to_comment', 0);
$show_recaptch				= $yooRecipeparams->get('show_recaptch', 'std');
$show_email					= $yooRecipeparams->get('show_email', 1) && $user->guest;
$use_google_recipe			= $yooRecipeparams->get('use_google_recipe', 1);
$show_people_icons			= $yooRecipeparams->get('show_people_icons', 1);
$use_fractions				= $yooRecipeparams->get('use_fractions', 0);
$use_video					= $yooRecipeparams->get('use_video', 1);
$use_nutrition_facts		= $yooRecipeparams->get('use_nutrition_facts', 1);
$show_slider_tooltip		= $yooRecipeparams->get('show_slider_tooltip', 0);
$use_prices					= $yooRecipeparams->get('use_prices', 0);
$currency					= $yooRecipeparams->get('currency', '&euro;');
$max_servings				= $yooRecipeparams->get('max_servings', 10);
$publickey 					= $yooRecipeparams->get('recaptcha_public_key');
$use_tags					= $yooRecipeparams->get('use_tags', 1);
$show_seasons				= $yooRecipeparams->get('show_seasons', 1);

$canShowCategory			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_category', 1);

$canShowDescription			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_description', 1);
$canShowDifficulty			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_difficulty', 1);
$canShowCost				= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_cost', 1);
$canShowRatings				= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_rating', 1);
$ratingStyle				= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'rating_style', 'stars');

$canShowPreparationTime		= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_preparation_time', 1);
$canShowCookTime			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_cook_time', 1);
$canShowWaitTime			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_wait_time', 1);

$canShowPrintIcon			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_print_icon', 1);
$show_email_icon			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_email_icon', 1);

$useSocialSharing			= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'use_social_sharing', 1);
$showSocialBookmarksOnTop	= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_on_top', 1);
$showSocialBookmarksOnBottom	= JHtml::_('yoorecipeutils.getParamValue', $this->menuParams, $yooRecipeparams, 'show_on_bottom', 0);

/*<script type="text/javascript"><!--
google_ad_client = "pub-9531998316889952";
google_ad_slot = "3958620213";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<!--script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script-->*/
 ?>
 
<div id="div-yoorecipe">
 <?php
 if (isset($recipe) && $recipe->published && $recipe->validated) {
 
	 // Add FB opengraph tags
	$openGraphTags = array();
	$uri	= JURI::getInstance();
	$lang 	= JFactory::getLanguage();
	$config = JFactory::getConfig();
	$openGraphTags[] = '<meta property="og:url" content="'.$uri->toString().'"/>';
	$openGraphTags[] = '<meta property="og:title" content="'.htmlspecialchars($this->recipe->title).'"/>';
	$openGraphTags[] = '<meta property="og:description" content="'.strip_tags($this->recipe->description).'"/>';
	$openGraphTags[] = '<meta property="og:type" content="recipebox:recipe"/>';
	$openGraphTags[] = '<meta property="og:locale" content="'.$lang->getTag().'"/>';
	$openGraphTags[] = '<meta property="og:site_name" content="'.$config->get('config.sitename').'"/>';

	$document->addCustomTag(implode("\n", $openGraphTags));
 ?>

<a id="top" name="top"></a>
<?php if ($use_google_recipe) : ?> <div class="hrecipe"> <?php endif; ?>
<div id="div-recipe-title">
	<?php 
		$editUrl = JRoute::_('index.php?option=com_yoorecipe&view=form&layout=edit&id=' . $recipe->slug); 
	?>
	<span class="item">
	<h1 class="recipe-title <?php if ($use_google_recipe) : echo 'fn'; endif; ?>" >
		<?php echo htmlspecialchars($recipe->title); if($canShowPrice==1 && $recipe->price!=null && $recipe->price > 0){ echo ' '.$recipe->price . $currency;} ?>
	</h1>
	</span>
	<?php if ($canShowDescription) : ?>
	<div id="div-recipe-description">
		<?php
		if ($use_google_recipe) {
			echo '<span class="summary">' . $recipe->description . '</span>';
		} else { 
			echo $recipe->description; 
		}?>
	</div>
<?php endif; ?>
	<?php 
		if ($this->canEdit && !$isPrinting) : ?>
		<input type="button" class="btn" onclick="window.location='<?php echo $editUrl; ?>'" value="<?php echo JText::_('COM_YOORECIPE_EDIT') ?>" />
		<?php endif; 
		if ($isPrinting) :
			echo '<input type="button" class="btn" onclick="javascript:window.print()" value="' . JText::_('COM_YOORECIPE_PRINT') . '"/>';
		endif;
		?>
</div>

<div id="div-recipe-difficulty">
	<?php 
	
	if ($canShowDifficulty):
		
		echo JText::_('COM_YOORECIPE_RECIPES_DIFFICULTY'); 
		for ($j = 1 ; $j <= 4; $j++) {
		
			if ($recipe->difficulty >= $j) {
				echo '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
			}
			else {
				echo '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
			}
		}
	endif;
	
	if ($canShowCost):

		echo '  ' . JText::_('COM_YOORECIPE_RECIPES_COST');
		for ($j = 1 ; $j <= 3 ; $j++) {
			if ($recipe->cost >= $j) {
				echo '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
			}
			else {
				echo '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
			}
		}
	endif;
	?>
</div>

<?php if ($useSocialSharing && $showSocialBookmarksOnTop) : ?>
<div>
	<?php echo JHtml::_('yoorecipeutils.socialSharing', $yooRecipeparams, $recipe); ?>
</div>
<?php endif; ?>

<div id="div-recipe-information">

<?php if ($canShowCategory) : ?>
	<?php echo JHtml::_('yoorecipeutils.generateCrossCategories', $recipe); ?>
	<span>&nbsp;-&nbsp;</span>
<?php endif; ?>

<?php if ($show_author) : ?>
	<span>
	<?php echo JText::_('COM_YOORECIPE_AUTHOR') . ': '; ?>
		<?php $authorUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getuserroute', $recipe->created_by) , false); ?>
		<a href="<?php echo $authorUrl; ?>">
			<span <?php if ($use_google_recipe) : echo 'class="author"'; endif; ?>><?php echo $recipe->author_name; ?></span>
		</a>
	</span>
<?php endif; ?>

<?php
	if ($use_tags) { 
		echo '<div class="clear">'.JHtml::_('yoorecipeutils.generateRecipeTags', $recipe).'</div>';
	}
?>

<?php
	if ($show_seasons) { 
		echo '<div class="clear">'.JHtml::_('yoorecipeutils.generateRecipeSeason', $recipe->season_id).'</div>';
	}
?>
	
<?php if ($canShowPreparationTime && $recipe->preparation_time != 0) : ?>
		<span><?php echo JText::_('COM_YOORECIPE_RECIPES_PREPARATION_TIME') . ': '; ?>
		<?php if ($use_google_recipe) : ?>
		<span class="preptime"><span class="value-title" title="PT<?php echo JHtml::_('yoorecipeutils.formattime', $recipe->preparation_time, "D", "H", "M");?>"></span></span>
		<?php endif; ?>
		<?php echo JHtml::_('yoorecipeutils.formattime', $recipe->preparation_time); ?>
		</span>	
	<span>&nbsp;-&nbsp;</span>
<?php endif; ?>

<?php if ($canShowCookTime && $recipe->cook_time != 0) : ?>
		<span><?php echo JText::_('COM_YOORECIPE_RECIPES_COOK_TIME') . ': '; ?>
		<?php if ($use_google_recipe) : ?>
		<span class="cooktime"><span class="value-title" title="PT<?php echo JHtml::_('yoorecipeutils.formattime', $recipe->cook_time, "D", "H", "M");?>"></span></span>
		<?php endif; ?>
		<?php echo JHtml::_('yoorecipeutils.formattime', $recipe->cook_time); ?>
		</span>	
	<span>&nbsp;-&nbsp;</span>
<?php endif; ?>

<?php if ($canShowWaitTime && $recipe->wait_time != 0) : ?>
	<span>
		<?php echo JText::_('COM_YOORECIPE_RECIPES_WAIT_TIME') . ': ' . JHtml::_('yoorecipeutils.formattime', $recipe->wait_time); ?>
	</span>
<?php endif; ?>

<?php
	if ($use_google_recipe && $this->recipe->total_ratings > 0) : 
?>
	<span class="review hreview-aggregate">		
<?php 	
	endif;
	
	if ($enable_comments) {
		echo '<div id="div-nb-comments">';
?>	
			<?php $url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false); ?>
			<a href="<?php echo $url . '#comments'; ?>">
			<?php 
				if ($this->recipe->total_ratings == 0) {
					echo JText::_('COM_YOORECIPE_POST_FIRST_COMMENT');
				}
				else if ($this->recipe->total_ratings == 1) {
					echo '(<span class="count" id="com_yoorecipe_nb_comments1">' . $this->recipe->total_ratings . '</span>' . ' ' . JText::_('COM_YOORECIPE_COMMENT').')';
				}
				else {
					echo '(<span class="count" id="com_yoorecipe_nb_comments1">' . $this->recipe->total_ratings . '</span>' . ' ' .  JText::_('COM_YOORECIPE_COMMENTS').')';
				}
			?>
			</a>
<?php
		echo '</div>';
	}
	
		echo '<div id="div-recipe-rating">';
		if ($canShowRatings){
			echo JHtml::_('yoorecipeutils.generateRecipeRatings', $recipe, $use_google_recipe, $ratingStyle);
		}
		echo '</div>';
	
if ($use_google_recipe && $this->recipe->total_ratings > 0) : 
?>
	</span> 
<?php 
endif;
	
?>	
	<div>
	<ul class="actions">
	<?php if (!$isPrinting) : ?>
		<?php if ($canShowPrintIcon) : ?>
			<li class="print-icon">
			<?php echo JHtml::_('yoorecipeicon.print_popup',  $this->recipe, $yooRecipeparams); ?>
			</li>
		<?php endif; ?>

		<?php if ($show_email_icon) : ?>
			<li class="email-icon">
			<?php echo JHtml::_('yoorecipeicon.email',  $this->recipe, $yooRecipeparams); ?>
			</li>
		<?php endif; ?>
		
		<?php if (!$user->guest && $yooRecipeparams->get('use_favourites', 1) == 1 ) : ?>
		<li class="favourites-icon">
			<div id="fav_<?php echo $recipe->id; ?>" style="float:right"><?php echo JHtml::_('yoorecipeicon.favourites',  $this->recipe, $yooRecipeparams); ?></div>
		</li>
		<?php endif; ?>
	<?php endif; ?>
	</ul>
	</div>
</div>

<div class="clear"></div>

<div class="div-recipe-container-ingredients">
	
	<div id="div-recipe-picture">
	<?php
		$picturePath = '';
		if ($recipe->picture != '') {
			$picturePath = $recipe->picture;
		} else if ($use_default_picture) {
			$picturePath = 'media/com_yoorecipe/images/no-image.jpg';
		}
		
		if ($use_watermark) {
			$picturePath = JHtml::_('yoorecipeutils.watermarkImage', $picturePath, 'Copyright ' . juri::base());
		}
		
		if ($picturePath != '') { ?>
		<a href="<?php echo $picturePath; ?>">
			<img class="recipe-picture-single photo" src="<?php echo $picturePath; ?>" alt="<?php echo htmlspecialchars($recipe->title); ?>" style="width:<?php echo $thumbnail_width; ?>px"/>
		</a>
	<?php } ?>
	</div>

	<div id="div-recipe-ingredients-single">
		<h3><?php echo JText::_('COM_YOORECIPE_RECIPES_INGREDIENTS'); ?></h3>
		<p>
			<?php
				echo JText::_('COM_YOORECIPE_FOR') . ' ' ;
			echo '<span id="spanNbPersons"';
			if ($use_google_recipe) :
				echo ' class="servingsize"';
			endif;
			echo '>' . $recipe->nb_persons . '</span>';
			
			$imgIcon;
			if ($recipe->servings_type == 'P') {
				echo ' ' . JText::_('COM_YOORECIPE_PERSONS');
				$imgIcon = 'person-icon.png';
			}
			else if ($recipe->servings_type == 'B') {
				echo ' ' . JText::_('COM_YOORECIPE_YOORECIPE_BATCHES_LABEL');
				$imgIcon = 'batch-icon.png';
			}
			else if ($recipe->servings_type == 'S') {
				echo ' ' . JText::_('COM_YOORECIPE_YOORECIPE_SERVINGS_LABEL');
				$imgIcon = 'batch-icon.png';
			}
			else if ($recipe->servings_type == 'D') {
				echo ' ' . JText::_('COM_YOORECIPE_YOORECIPE_DOZENS_LABEL');
				$imgIcon = 'batch-icon.png';
			}
			
			if ($show_people_icons) : 
				echo ' (<span id="spanShowPersons">';
				for ($j = 1 ; $j <= $recipe->nb_persons; $j++) {
					echo '<img src="'.JUri::root().'media/com_yoorecipe/images/' . $imgIcon . '" alt=""/>';
				}
				echo '</span>)';
			endif;
			?>
		</p>
		<?php 
		if ($recipe->ingredients) { 
		
			$totalAmount = 0;
			if ($use_google_recipe) {
		
				$crtGroup = '';
				$first = true;
				foreach ($recipe->ingredients as $ingredient) : 
				
					if ($ingredient->ingr_group != $crtGroup) {
						
						if (!$first) { echo '</ul>'; } 
						
						$crtGroup = $ingredient->ingr_group;
						echo '<h4>' . JText::_($ingredient->ingr_group) . '</h4>';
						echo '<ul>';
						$first = false;
					}

					echo '<li><span class="ingredient">';
					echo '<input type="hidden" name="ingredientId" value="'. $ingredient->id . '"/>';
					echo '<input type="hidden" id="initialQuantity_' . $ingredient->id . '" value="'. round($ingredient->quantity, 2) . '"/>';
					echo '<input type="hidden" id="initialPrice_' . $ingredient->id . '" value="' . $ingredient->price . '"/>';
					
					$qty = (abs($ingredient->quantity) < 0.00001) ? '' :  round($ingredient->quantity, 2);
					echo '	<span id="spanQuantity_' . $ingredient->id . '" class="amount">'. $qty . '</span> ' . JText::_($ingredient->unit) . ' <span class="name">' . htmlspecialchars($ingredient->description) . '</span>';
					if ($use_prices) {
						echo ',&nbsp;<span id="spanPrice_' . $ingredient->id . '">' . $ingredient->price . '</span>&nbsp;' . $currency;
					}
					echo '</span></li>';
					$totalAmount += $ingredient->price;
					
				endforeach;
				echo '</ul>';
			}
		
			else  {
			
				$crtGroup = '';
				$first = true;
				foreach ($recipe->ingredients as $ingredient) : 
		
					if ($ingredient->ingr_group != $crtGroup) {
						
						if (!$first) { echo '</ul>'; } 
						$crtGroup = $ingredient->ingr_group;
						echo '<h4>' . JText::_($ingredient->ingr_group) . '</h4>';
						echo '<ul>';
						$first = false;
					}
					
					echo '<li>';
					echo '	<input type="hidden" name="ingredientId" value="'. $ingredient->id . '"/>';
					echo '	<input type="hidden" id="initialQuantity_' . $ingredient->id . '" value="'. $ingredient->quantity . '"/>';
					echo '  <input type="hidden" id="initialPrice_' . $ingredient->id . '" value="' . $ingredient->price . '"/>';
					
					$qty = (abs($ingredient->quantity) < 0.00001) ? '' :  round($ingredient->quantity, 2);
					echo '	<span id="spanQuantity_' . $ingredient->id . '" >'. $qty . '</span> ' . JText::_($ingredient->unit) . ' <span>' . htmlspecialchars($ingredient->description) . '</span>';
					if ($use_prices) {
						echo ',&nbsp;<span id="spanPrice_' . $ingredient->id . '">' . $ingredient->price. '</span>&nbsp;' . $currency;
					}
					echo '</li>';
					$totalAmount += $ingredient->price;
					
				endforeach;
				echo '</ul>';
			} 
		}
		
		if ($use_prices) { ?>
			<div><?php echo JText::_('COM_YOORECIPE_TOTAL_AMOUNT'); ?><span id="totalPrice"><?php echo $totalAmount; ?></span><?php echo $currency; ?></div>
<?php 	} ?>
		
		<br/>
		<?php if ($use_quantity_converter) : ?>
		<?php if ($show_slider_tooltip) { ?>
		<div tabindex="1" id="slider-1" class="horizontal dynamic-slider-control slider hasTip"
			title="<?php echo JText::_('COM_YOORECIPE_SLIDER') . '::' . JText::_('COM_YOORECIPE_SLIDER_TOOLTIP') ?>">
		<?php } else { ?>
		<div tabindex="1" id="slider-1" class="horizontal dynamic-slider-control slider">
		<?php }?>
		
			<input id="slider-input-1" class="slider-input"/>
			<div class="line" style="top: 11px; left: 15.5px; width: 150px;">
				<div style="width: 150px;"></div>
			</div>
			<?php $document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getQuantityConverterScripts', $use_fractions, $show_people_icons, $imgIcon, $recipe->nb_persons)); ?>
			<script type="text/javascript">
 //<![CDATA[
		var s = new YooSlider($("slider-1"), $("slider-input-1"));
		s.onchange = function () {
		
			$('spanNbPersons').set('html',s.getValue());
			
		<?php if ($show_people_icons) : ?>
			$('spanShowPersons').set('html','');
			
			for (i = 0 ; i < s.getValue(); i++) {
				var img = new Element('img', {'src': '<?php echo Juri::root(); ?>media/com_yoorecipe/images/<?php echo $imgIcon; ?>', 'alt':''});
				img.inject($('spanShowPersons'));
			}
		<?php endif; ?>
		
			var inputs = document.getElementsByTagName('input');
			var totalPrice = 0;
			for (i = 0 ; i < inputs.length ; i++) {
				if (inputs[i].name == 'ingredientId') {
					
					var newValue = $('initialQuantity_' + inputs[i].value).value * s.getValue()/ <?php echo $recipe->nb_persons; ?>;
					<?php if ($use_fractions) { ?>
					$('spanQuantity_' + inputs[i].value).set('html', turnIntoFraction(Math.round(newValue*100)/100));
					<?php } else { ?>
					var newQty = Math.round(newValue*100)/100;
					if (newQty != 0) {
						$('spanQuantity_' + inputs[i].value).set('html',newQty);
					} else {
						$('spanQuantity_' + inputs[i].value).set('html','');
					}
					<?php } 
					
					if ($use_prices) { ?>
					var newPrice = $('initialPrice_' + inputs[i].value).value * s.getValue() / <?php echo $recipe->nb_persons; ?>;
					$('spanPrice_' + inputs[i].value).set('html', Math.round(newPrice*100)/100);
					totalPrice += newPrice;
					<?php } ?>
				}
			}
			
			<?php if ($use_prices) { ?>
			$('totalPrice').set('html',Math.round(totalPrice*100)/100);
			<?php } ?>
		};
		s.setValue(<?php echo $recipe->nb_persons; ?>);
		s.setMinimum(1);
		s.setMaximum(<?php echo $max_servings; ?>);
/* ]]> */
			</script>
		</div>
		<?php endif; ?>
		
	</div>	
</div>

<?php if ($use_nutrition_facts) { ?>
<div>
	<?php
		echo JHtml::_('yoorecipeutils.generateRecipeNutritionalInfo', $recipe);
	?>
</div>
<?php } ?>

<div id="div-recipe-preparation-single">
	<h3><?php echo JText::sprintf('COM_YOORECIPE_RECIPES_PREPARATION_SEO_OPTIMISED', $recipe->title); ?></h3>
	<?php
		echo '<div> ';
		if ($use_automatic_numbering) {
			if ($use_google_recipe){
				echo '<span class="instructions">';
				echo JHtml::_('yoorecipeutils.formatParagraphs', $recipe->preparation);
				echo '</span>';
			} else {
				echo JHtml::_('yoorecipeutils.formatParagraphs', $recipe->preparation);
			}
		}
		else {
			if ($use_google_recipe) {
				echo '<span class="instructions">';
				echo $recipe->preparation;
				echo '</span>';
			} else {
				echo $recipe->preparation;
			}
		}
		echo '</div>';
	?>
</div>
<?php if ($use_google_recipe) : ?>
<span style="display:none" property="v:published" content="<?php echo JFactory::getDate($recipe->creation_date)->Format('%Y-%m-%d'); ?>"></span>
<?php endif; ?>

<?php
if ($use_video && $recipe->video != '') {
	echo '<div>' . JHtml::_('yoorecipeutils.generateVideoPlayer', $recipe->video) . '</div>';
}
?>
<div id="div-recipe-back">
	<input type="button" class="btn" value="<?php echo JText::_('COM_YOORECIPE_BACK'); ?>" onclick="history.go(-1);return true;"/>
</div>

<?php if ($useSocialSharing && $showSocialBookmarksOnBottom) : ?>
<div>
	<?php echo JHtml::_('yoorecipeutils.socialSharing', $yooRecipeparams, $recipe); ?>
</div>
<?php endif; ?>

<?php 
	if ($enable_comments && !$isPrinting) { // We don't want to print useless stuff
?>
<div id="div-recipe-new-comment">
	<a id="comments" name="comments"></a>
	<h3><?php echo ($this->recipe->total_ratings == 0) ? JText::_('COM_YOORECIPE_POST_FIRST_COMMENT') : JText::_('COM_YOORECIPE_ADD_COMMENT'); ?></h3>
	
	<?php
		require_once JPATH_COMPONENT.'/lib/recaptchalib.php';
		if ($register_to_comment && $user->guest) {
		
			$returnUrl		= base64_encode(JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false) . '#comments'); 
			$redirectUrl 	= JRoute::_('index.php?option=com_users&view=login&return='.$returnUrl);
?>
			<a href="<?php echo $redirectUrl; ?>" ><?php echo JText::_('COM_YOORECIPE_REGISTER_TO_COMMENT'); ?></a>
<?php	
		}
		else if ($show_recaptch == 'recaptcha' && $publickey == '') {
			echo recaptcha_get_html($publickey);
		}
		else {
		
			$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getAddRecipeRatingScripts', $show_email, $show_recaptch, $int1, $int2));
?>
	<div class="add-comment-container">
		
		<form action="<?php echo 'index.php?option=com_yoorecipe&task=addRecipeRating'; ?>" method="post" id="yoorecipe-rating-form">
			<div>
				<input type="hidden" name="recipeId" id="yoorecipe-rating-form-recipe-id" value="<?php echo $recipe->id; ?>"/>
				<input type="hidden" name="userId" id="yoorecipe-rating-form-user-id" value="<?php if (!$user->guest) { echo $user->id; } ?>"/>
				<input type="hidden" name="currentUrl" value="<?php echo JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) . '#comments' , false); ?>"/>
				<input type="hidden" id="yoorecipe-rating-form-comment-msg" value="<?php echo JText::_('COM_YOORECIPE_COMMENT_ERROR'); ?>"/>
				<input type="hidden" id="yoorecipe-rating-form-author-msg" value="<?php echo JText::_('COM_YOORECIPE_NAME_ERROR'); ?>"/>
				<input type="hidden" id="yoorecipe-rating-form-email-msg" value="<?php echo JText::_('COM_YOORECIPE_EMAIL_ERROR'); ?>"/>
				<input type="hidden" id="yoorecipe-rating-form-enigma-msg" value="<?php echo JText::_('COM_YOORECIPE_ENIGMA_ERROR'); ?>"/>
			</div>
			
			<div>
				<img id="star-icon-1" src="media/com_yoorecipe/images/star-icon.png" onmouseover="setRatingValue(1);" alt=""/>
				<img id="star-icon-2" src="media/com_yoorecipe/images/star-icon.png" onmouseover="setRatingValue(2);" alt=""/>
				<img id="star-icon-3" src="media/com_yoorecipe/images/star-icon.png" onmouseover="setRatingValue(3);" alt=""/>
				<img id="star-icon-4" src="media/com_yoorecipe/images/star-icon.png" onmouseover="setRatingValue(4);" alt=""/>
				<img id="star-icon-5" src="media/com_yoorecipe/images/star-icon.png" onmouseover="setRatingValue(5);" alt=""/>
				<div>&nbsp;(<span id="span-rating">5</span>/<span>5</span>)</div>
			</div>

			<input type="hidden" id="rating" name="rating" value="5"/>
			
			<textarea onfocus=""
				name="comment" 
				id="yoorecipe-rating-form-comment" 
				title="<?php echo JText::_('COM_YOORECIPE_ADD_COMMENT'); ?>" 
				rows="20" cols="40"
				class="inputtextarea"
				placeholder="<?php echo JText::_('COM_YOORECIPE_ADD_COMMENT'); ?>"
			></textarea>
			<div id="yoorecipe-rating-form-comment-err" class="shareerr"></div>
<?php
		if ($user->guest) {
			echo '<span>'.JText::_('COM_YOORECIPE_NAME').'</span>';
			echo '<input type="text" name="author" id="yoorecipe-rating-form-author" class="inputtext" placeholder="'.JText::_('COM_YOORECIPE_NAME').'"/>';
			echo '<div id="yoorecipe-rating-form-author-err" class="shareerr"></div>';
		} 
		else {
			echo '<span>'.JText::_('COM_YOORECIPE_LOGGEDIN_AS').' <strong>'.$user->name.'</strong>'.'</span>';
			echo '<input type="hidden" name="author" id="yoorecipe-rating-form-author" value="'.$user->name.'"/>';
			echo '<div id="yoorecipe-rating-form-author-err" class="shareerr"></div>';
		}
			
		if ($show_email) {
			
			echo '<span>'.JText::_('COM_YOORECIPE_EMAIL').'</span>';
			echo '<input type="text" name="email" id="yoorecipe-rating-form-email" class="inputtext" placeholder="'.JText::_('COM_YOORECIPE_EMAIL').'"/>';
			echo '<div id="yoorecipe-rating-form-email-err" class="shareerr"></div>';
			echo '<div class="small-text">'.JText::_('COM_YOORECIPE_EMAIL_NOT_USED').'</div>';
			echo '<br/>';
		}
		else if ($user->guest == 0) {
			echo '<input type="hidden" name="email" id="yoorecipe-rating-form-email" value="'.$user->email.'"/>';
		}
		
		if ($show_recaptch == 'std') {
			echo '<span>'.JText::_('COM_YOORECIPE_HOW_MANY_ARE').' '.$int1.' + '.$int2.'? '.'</span>';
			echo '<input type="text" name="enigma" id="yoorecipe-rating-form-enigma" class="inputtext" />';
			echo '<div id="yoorecipe-rating-form-enigma-err" class="shareerr"></div>';
		}
		else if ($show_recaptch == 'recaptcha') {
			echo recaptcha_get_html($publickey);			
		}
?>
		<input type="button" class="btn" value="<?php echo JText::_('COM_YOORECIPE_SUBMIT_COMMENT'); ?>" onclick="validateCommentForm();"/>
		<div id="ajax-loading"></div>
		</form>
	</div>
<?php	} ?>
</div>

<div id="div-recipe-comments">
	<h3><?php echo JText::_('COM_YOORECIPE_YOUR_COMMENTS'); ?>&nbsp;(<span id="com_yoorecipe_nb_comments2"><?php echo $recipe->total_ratings; ?></span>)</h3>
	<?php
		$canManageComments = JHtml::_('yoorecipeutils.canManageComments', $user, $recipe->created_by);
		$canReportComments = JHtml::_('yoorecipeutils.canReportComments', $user);
		
		echo '<div id="yoorecipe-ajax-container">';
		if ($recipe->total_ratings > 0) {
			echo JHtml::_('yoorecipeutils.generateRatings', $recipe->ratings, $canManageComments, $canReportComments);
		}
		echo '</div>';
	
	if ($recipe->total_ratings > count($recipe->ratings)) {
	
		$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getMoreCommentsScript', $recipe->id));
		echo '<input type="button" class="btn" id="yoorecipeGetMoreCommentsBtn" onclick="getMoreComments();" value="' . JText::_('COM_YOORECIPE_MORE_COMMENTS'). '"/>';
	}
	?>
	
</div>
<?php } // if ($enable_comments && !$isPrinting) { 
?>

<?php if ($use_google_recipe) : ?> </div> <?php endif; ?>

<div id="div-goto-top">
	<?php $url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false); ?>
	<a href="<?php echo $url . '#top'; ?>"><?php echo JText::_('COM_YOORECIPE_GO_TOP'); ?></a>
</div>

<div class="clear"></div>

<?php
} // End if (isset($recipe) && isset($recipe->published) && isset($recipe->validated)) {
else {

	if (isset($recipe) && ($recipe->published == 0 || $recipe->validated == 0)) {
?>
		<div class="yoorecipe-ok-message"><?php echo JText::_('COM_YOORECIPE_AWAITING_VALIDATION'); ?></div>
<?php
	}

	else {
		echo JHtml::_('yoorecipeutils.generateCategoriesList', $this->categories);
	}
}
?>
</div>