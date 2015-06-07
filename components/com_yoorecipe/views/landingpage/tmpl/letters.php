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
$yooRecipeparams 		= JComponentHelper::getParams('com_yoorecipe');
$use_watermark			= $yooRecipeparams->get('use_watermarks' , 1);
$canShowPrice			= $yooRecipeparams->get('show_price', 0);
$currency				= $yooRecipeparams->get('currency', '&euro;');
$yoorecipe_layout 		= $yooRecipeparams->get('yoorecipe_layout', 'twocols');

// Landing page parameters
$canShowTitle					= (isset($this->menuParams)) ? $this->menuParams->get('show_title', 1) : 1;
$frontpageLbl					= (isset($this->menuParams)) ? $this->menuParams->get('frontpage_lbl', '') : '';
$canBrowseByLetter				= (isset($this->menuParams)) ? $this->menuParams->get('browse_by_letter', 1) : 1;
$canBrowseByCategories			= (isset($this->menuParams)) ? $this->menuParams->get('browse_by_categories', 1) : 1;
$show_sub_categories_picture	= (isset($this->menuParams)) ? $this->menuParams->get('show_sub_categories_picture', 1) : 1;

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

<h1 class="yoorecipe-h1"><?php echo JText::_('COM_YOORECIPE_LANDING_PAGE'); ?></h1>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">

<div class="alpha-index-container">
<div class="alpha-index">
<?php
	$lang = JFactory::getLanguage();
	$alphabet = JHtml::_('yoorecipeutils.generateAlphabet', $lang->getTag());
	
	function array_ereg($pattern, $haystack)
	{
	   for($i = 0; $i < count($haystack); $i++)
	   {
		   if (preg_match($pattern, $haystack[$i]))
			   return true;
	   }

	   return false;
	} 

	if (array_ereg('[0-9]' , $this->recipeStartLetters)) {
		echo '<a title="#" href="index.php?option=com_yoorecipe&task=getRecipesByLetter&l=dash">#</a>';
	} else {
		echo '<span title="#">#</span>';
	}
	foreach ($alphabet as $tmpLetter) :
		
		if (in_array($tmpLetter , $this->recipeStartLetters)) {
			echo '<a title="' . $tmpLetter . '" href="index.php?option=com_yoorecipe&task=getRecipesByLetter&l='. urlencode($tmpLetter). '">' . $tmpLetter . '</a>';
		} else {
			echo '<span title="' . $tmpLetter . '">' . $tmpLetter . '</span>';
		}
	endforeach;
?>
</div>
</div>
<div class="yoorecipe-hr">&nbsp;</div>

<h2><?php echo JText::sprintf('COM_YOORECIPE_YOORECIPE_START_WITH', $this->crtLetter); ?></h2>

<?php 
	if ($this->items) {
	
		echo '<div class="yoorecipe-cont-results">';
		include JPATH_SITE.'/components/com_yoorecipe/templates/recipes_'.$yoorecipe_layout.'.php';
		echo'</div>';
		
	} // End if ($this->items) {
?>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="returnPage" value="<?php echo JUri::current(); ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>