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

// Component Parameters
$yooRecipeparams 			= JComponentHelper::getParams('com_yoorecipe');
$pagination_position		= $yooRecipeparams->get('pagination_position', 'bottom');
$canShowPrice				= $yooRecipeparams->get('show_price', 0);
$currency					= $yooRecipeparams->get('currency', '&euro;');
$yoorecipe_layout			= $yooRecipeparams->get('yoorecipe_layout', 'twocols');

// Categories layout parameters
$show_category_title			= $yooRecipeparams->get('show_category_title', 1);
$show_category_description		= $yooRecipeparams->get('show_category_description', 1);
$show_subcategories				= $yooRecipeparams->get('show_subcategories', 1);
$show_sub_categories_picture 	= $yooRecipeparams->get('show_sub_categories_picture', 1);
$show_add_recipe_button			= (isset($this->menuParams)) ? $this->menuParams->get('show_add_recipe_button', 1) : $yooRecipeparams->get('show_add_recipe_button', 1);

// Blog layout parameters
$blog_is_picture_clickable 	= $yooRecipeparams->get('blog_is_picture_clickable', 1);
$blog_thumbnail_width 		= $yooRecipeparams->get('blog_thumbnail_width', 100);
$blog_show_title 			= $yooRecipeparams->get('blog_show_title', 1);
$blog_show_creation_date 	= $yooRecipeparams->get('blog_show_creation_date', 0);
$blog_show_ingredients 		= $yooRecipeparams->get('blog_show_ingredients', 0);
$blog_show_readmore 		= $yooRecipeparams->get('blog_show_readmore', 0);
$blog_show_description		= $yooRecipeparams->get('blog_show_description', 1);
$blog_show_author 			= $yooRecipeparams->get('blog_show_author', 1);
$blog_show_nb_views 		= $yooRecipeparams->get('blog_show_nb_views', 0);
$blog_show_category_title 	= $yooRecipeparams->get('blog_show_category_title', 1);
$blog_show_difficulty 		= $yooRecipeparams->get('blog_show_difficulty', 0);
$blog_show_cost				= $yooRecipeparams->get('blog_show_cost', 0);
$blog_show_rating 			= $yooRecipeparams->get('blog_show_rating', 1);
$blog_rating_style 			= $yooRecipeparams->get('blog_rating_style', 'stars');
$blog_show_preparation_time = $yooRecipeparams->get('blog_show_preparation_time', 1);
$blog_show_cook_time 		= $yooRecipeparams->get('blog_show_cook_time', 1);
$blog_show_wait_time 		= $yooRecipeparams->get('blog_show_wait_time', 1);
$blog_show_seasons	 		= $yooRecipeparams->get('blog_show_seasons', 1);

// Add styles and JS
$document 	= JFactory::getDocument();
$user 		= JFactory::getUser();
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_'.$yoorecipe_layout.'.css');

if (!$user->guest) {
	$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getAddToFavouritesScript'));
	$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.removeFromFavouritesScript'));
}

$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getUpdateLimitBox'));
?>

<?php if ($show_category_title) : ?>
	<h1 class="yoorecipe-h1 general"><?php echo JText::sprintf('COM_YOORECIPE_CATEGORIES_GENERAL', htmlspecialchars($this->category->title));?></h1>
<?php endif; ?>	

<?php if ($show_category_description) : ?>
	<div><?php echo $this->category->description;?></div>
<?php endif; ?>

<?php 
	if ($show_subcategories) { 
		if (count($this->subcategories) > 0) : ?>
	<h2 class="yoorecipe-h1 sub"><?php echo JText::sprintf('COM_YOORECIPE_SUB_CATEGORIES', htmlspecialchars($this->category->title));?></h2>
<?php 	endif; ?>	

<div class="yoorecipe-sub-categories">
	<?php 
		echo JHtml::_('yoorecipeutils.generateSubCategoriesMosaic', $this->subcategories, $show_sub_categories_picture);
	?>
</div>
<?php } ?>	

<div class="clear" > </div>

<?php if ($show_category_title && $show_subcategories && count($this->subcategories) > 0) : ?>
	<h2 class="yoorecipe-h1 recipes"><?php echo JText::sprintf('COM_YOORECIPE_CATEGORIES_RECIPES', htmlspecialchars($this->category->title));?></h2>
<?php endif; ?>	

<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">

<?php
if ($show_add_recipe_button) {
	echo JHtml::_('yoorecipeutils.generateAddRecipeButton');
}

if (count($this->items) == 0 ) {
	echo JHtml::_('yoorecipeutils.generateCategoriesList', $this->categories);
} 
else {
	
	if ($pagination_position == 'top' || $pagination_position == 'both') {
		echo JHtml::_('yoorecipeutils.generatePagination', $this->pagination);
	}

	echo '<div class="yoorecipe-cont-results">';
	include JPATH_SITE.'/components/com_yoorecipe/templates/recipes_'.$yoorecipe_layout.'.php';
	echo'</div>';

	if ($pagination_position == 'bottom' || $pagination_position == 'both') { 
		echo JHtml::_('yoorecipeutils.generatePagination', $this->pagination);
	} 
} // End count(recipes) > 0
?>
	<div>
		<input type="hidden" name="task" value="viewCategory" />
		<input type="hidden" name="returnPage" value="<?php echo JUri::current(); ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>