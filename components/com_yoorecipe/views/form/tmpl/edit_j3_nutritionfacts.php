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

defined('_JEXEC') or die;

// Get config parameters
$params 				= JComponentHelper::getParams('com_yoorecipe');
$use_recipe_settings 	= $params->get('use_recipe_settings', 1);

$use_nutrition_facts	= $params->get('use_nutrition_facts', 1);
$show_kcal	 			= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_kcal', 1) : $params->get('show_kcal_fe', 1));
$show_diet	 			= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_diet', 1) : $params->get('show_diet_fe', 1));
$show_veggie	 		= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_veggie', 1) : $params->get('show_veggie_fe', 1));
$show_gluten_free	 	= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_gluten_free', 1) : $params->get('show_gluten_free_fe', 1));
$show_lactose_free	 	= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_lactose_free', 1) : $params->get('show_lactose_free_fe', 1));
$show_carbs				= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_carbs', 1) : $params->get('show_carbs_fe', 1));
$show_fat	 			= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_fat', 1) : $params->get('show_fat_fe', 1));
$show_sfat	 			= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_sfat', 1) : $params->get('show_sfat_fe', 1));
$show_proteins	 		= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_proteins', 1) : $params->get('show_proteins_fe', 1));
$show_fibers	 		= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_fibers', 1) : $params->get('show_fibers_fe', 1));
$show_salt	 			= $use_nutrition_facts == 0 ? 0 : ($use_recipe_settings ? $params->get('show_salt', 1) : $params->get('show_salt_fe', 1));

?>
<?php if ($show_diet) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('diet'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('diet'); ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($show_veggie) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('veggie'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('veggie'); ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($show_gluten_free) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('gluten_free'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('gluten_free'); ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($show_lactose_free) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('lactose_free'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('lactose_free'); ?>
		</div>
	</div>
<?php endif; ?>
	
<?php if ($show_carbs) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('carbs'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('carbs'); ?>
		</div>
	</div>
<?php endif; ?>	
	
<?php if ($show_fat) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('fat'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('fat'); ?>
		</div>
	</div>
<?php endif; ?>	
	
<?php if ($show_sfat) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('saturated_fat'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('saturated_fat'); ?>
		</div>
	</div>
<?php endif; ?>	
	
<?php if ($show_proteins) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('proteins'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('proteins'); ?>
		</div>
	</div>
<?php endif; ?>	
	
<?php if ($show_fibers) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('fibers'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('fibers'); ?>
		</div>
	</div>
<?php endif; ?>	
	
<?php if ($show_salt) : ?>	
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('salt'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('salt'); ?>
		</div>
	</div>
<?php endif; ?>	

<?php if ($show_kcal) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('kcal'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('kcal'); ?>
		</div>
	</div>
<?php endif; ?>