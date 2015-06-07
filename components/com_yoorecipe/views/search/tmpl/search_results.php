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

// no direct access
defined('_JEXEC') or die;

// Component Parameters
$yooRecipeparams 			= JComponentHelper::getParams('com_yoorecipe');
$pagination_position		= $yooRecipeparams->get('pagination_position', 'bottom');
$canShowPrice				= $yooRecipeparams->get('show_price', 0);
$currency					= $yooRecipeparams->get('currency', '&euro;');
$yoorecipe_layout			= $yooRecipeparams->get('yoorecipe_layout', 'twocols');

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

$currentUrl = JURI::getInstance();
?>
<div>
<h1 class="yoorecipe-h1"><?php echo JText::_('COM_YOORECIPE_SEARCH_RECIPE'); ?></h1>

<form action="<?php echo JFilterOutput::ampReplace($currentUrl); ?>" method="post" name="adminForm" id="adminForm">
<?php
	/*if ($pagination_position == 'top' || $pagination_position == 'both') {
		echo JHtml::_('yoorecipeutils.generatePagination', $this->pagination);
	}*/

	if (count($this->items) == 1) 
	{
		$app = JFactory::getApplication();
		$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $this->items[0]->slug, $this->items[0]->catslug) , false);
		$app->redirect($url);
	}
	else
	{
		echo '<p>' . count($this->items) . ' ' . JText::_('COM_YOORECIPE_SEARCH_MANY_RESULTS_FOUND') . '</p>';
		$searchUrl = JRoute::_('index.php?option=com_yoorecipe&task=initSearch');
		echo '<p><a href="' . $searchUrl . '">' . JText::_('COM_YOORECIPE_NEW_SEARCH') . '</a></p>';
	}

	echo '<div class="yoorecipe-cont-results">';
	include JPATH_SITE.'/components/com_yoorecipe/templates/recipes_'.$yoorecipe_layout.'.php';
	echo'</div>';
?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="returnPage" value="<?php echo JUri::current(); ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
	<div id="div-recipe-back">
		<input type="button" class="btn" onclick="history.back();" value="<?php echo JText::_('COM_YOORECIPE_BACK'); ?>"/>
	</div>

<?php 
	/*if ($pagination_position == 'bottom' || $pagination_position == 'both') { 
		echo JHtml::_('yoorecipeutils.generatePagination', $this->pagination);
	}*/
?>
</form>
</div>