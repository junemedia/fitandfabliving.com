<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Counter Module
# ----------------------------------------------------------------------
# Copyright (C) 2012 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe_counter/styles/mod_yoorecipe_counter'.$params->get('moduleclass_sfx').'.css');

JHtmlBehavior::framework();
?>
<div class="mod_yoorecipe_counter">

<?php
	if (strlen($params->get('intro_text')) > 0) :
?>
<div class="intro_text">
	<?php echo $params->get('intro_text'); ?>
</div>
<?php
	endif;
?>

<div class="compteur">
<?php 
	$nbRecipesAsString = (string) $nb_recipes; 
	for ($i = 0 ; $i < strlen($nbRecipesAsString); $i++) :
		echo '<span class="num num' . $nbRecipesAsString[$i] . '"></span>';
	endfor;
?><span class="counter_text">&nbsp;<?php echo JText::_('MOD_YOORECIPE_COUNTER_TEXT'); ?></span>
</div>

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