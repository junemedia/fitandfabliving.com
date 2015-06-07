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
JHtmlBehavior::framework();

$document		= $document = JFactory::getDocument();
$params 		= JComponentHelper::getParams('com_yoorecipe');
$currency		= $params->get('currency', '&euro;');
$use_fractions	= $params->get('use_fractions', 0);
$use_prices		= $params->get('use_prices', 0);
?>

<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('nb_persons'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('nb_persons'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('servings_type'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('servings_type');  ?>
	</div>
</div>

<div id="ingr_container">
	<div class="control-group">
		<div class="control-label"><strong><?php echo JText::_('COM_YOORECIPE_YOORECIPE_INGREDIENTS'); ?></strong></div>
		<div style="clear:both"></div>
		<?php foreach ($this->groups as $group) {
			echo '<div id="cont_group_'.$group->id.'" style="display:none">';
			echo '<h4>'.JText::_($group->text).'</h4>';
			echo '<ul id="group_'.$group->id.'"></ul>';
			echo '</div>';
		} ?>
	</div>
<?php

// Loop over ingredietns
if ($this->ingredients) { 

	foreach ($this->ingredients as $crtIngredient) {
	
		$qtyValue = ($use_fractions) ? JHtmlYooRecipeAdminUtils::decimalToFraction($crtIngredient->quantity) : round($crtIngredient->quantity, 2);
		$label = $qtyValue.' '.addslashes(JText::_($crtIngredient->unit)).' '.addslashes($crtIngredient->description);
		$label .= $use_prices && !empty($crtIngredient->price) ? ' ('.$crtIngredient->price.' '.$currency.')' : '';
		$script = "window.addEvent('domready', function () {
	
		var ingrContainer = new Element('li');
		var inputOrder = new Element('input', {'type':'hidden', 'size':'3', 'name':'ordering[]', 'value':'".$crtIngredient->ordering."'});
		var inputGroup = new Element('input', {'type':'hidden', 'size':'3', 'name':'group[]', 'value':'".$crtIngredient->group_id."'});
		var inputIngredientId = new Element('input', {'id': 'ingredientId', 'type':'hidden', 'size':'3', 'name':'ingredientId[]', 'value':'".$crtIngredient->id."'});
		var inputQuantity = new Element('input', {'type':'hidden', 'size':'3', 'name':'quantity[]', 'value':'".$qtyValue."'});
		var inputUnit = new Element('input', {'type':'hidden', 'size':'3', 'name':'unit[]', 'value':'".$crtIngredient->unit."'});
		var inputDescriptionElt = new Element('input', {'type':'hidden', 'size':'3', 'name':'ingr_description[]', 'value':'".addslashes($crtIngredient->description)."'});
		var inputPriceElt = new Element('input', {'type':'hidden', 'size':'3', 'name':'price[]', 'value':'".$crtIngredient->price."'});
		
		var spanText = new Element('span', {'html':'".$label."','class':'withTg'});
		
		var deleteElt = new Element('span', {'onclick': 'j3_deleteIngredient(this)', 'html': ' x'});
		deleteElt.setStyle('cursor','pointer');
		var brElt = new Element('br');
		
		// inject
		spanText.grab(deleteElt);
		ingrContainer.grab(spanText);
		
		ingrContainer.grab(inputOrder);
		ingrContainer.grab(inputGroup);
		ingrContainer.grab(inputIngredientId);
		ingrContainer.grab(inputQuantity);
		ingrContainer.grab(inputUnit);
		ingrContainer.grab(inputDescriptionElt);
		ingrContainer.grab(inputPriceElt);
		ingrContainer.grab(brElt);
		
		$('group_".$crtIngredient->group_id."').grab(ingrContainer);
		$('cont_group_".$crtIngredient->group_id."').setStyle('display', 'block');
	});";
		
		$document->addScriptDeclaration($script);
	
	} // End foreach ($this->ingredients as $crtIngredient) 
	
} // End if
?>
</div>

<div id="add_ingredient">
	<div class="control-group">
		<div class="control-label"><strong><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ADD'); ?></strong></div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_GROUP'); ?>
		</div>
		<div class="controls">
			<select name="group[]" id="group">
				<?php
				foreach($this->groups as $group) {
					echo '<option value="' . $group->id. '">'.JText::_($group->text).'</option>';
				} ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_QUANTITY'); ?>
		</div>
		<div class="controls">
			<input type="hidden" name="recipe_id[]" id="recipe_id" value="<?php echo (int) $this->item->id ?>"/>
			<input type="text" name="quantity[]" id="quantity"/>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_UNIT'); ?>
		</div>
		<div class="controls">
			<select name="unit[]" id="unit">
				<?php
				foreach ($this->units as $crtUnit) {
					echo '<option value=\''.$crtUnit->code.'\'>'.JText::_($crtUnit->label).'</option>';
				} ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_DESCRIPTION'); ?>
		</div>
		<div class="controls">
			<input type="text" name="ingr_description[]" id="ingr_description"/>
		</div>
	</div>
	
	<?php if ($use_prices) { ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_PRICE') .'&nbsp;('.$currency.')'; ?>
		</div>
		<div class="controls">
			<input type="text" name="price[]" id="price"/>
		</div>
	</div>
	<?php } ?>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ORDER'); ?>
		</div>
		<div class="controls">
			<input type="text" size="3" name="ordering[]" id="order" value=""/>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<input type="button" class="btn" onclick="com_yoorecipe_addIngredient();" value="<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ADD'); ?>"/>
		</div>
	</div>
</div>