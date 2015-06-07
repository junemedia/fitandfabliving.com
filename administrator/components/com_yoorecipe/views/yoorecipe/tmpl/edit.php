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

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtmlBehavior::framework();

$document = JFactory::getDocument();
 
// Load content language file
$lang 		= JFactory::getLanguage();
$extension 	= 'com_categories';
$base_dir 	= JPATH_ADMINISTRATOR;

// Get config parameters
$params 	= JComponentHelper::getParams('com_yoorecipe');
$use_tags 	= $params->get('use_tags', 1);

$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);
	
if ($use_tags) { 
	$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getTagManagementScript'));
}

$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getIngredientsManagementScript', $this->units, $this->groups));
$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getFractionsScript'));
$document->addStyleSheet(Juri::root() . 'media/com_yoorecipe/styles/yoorecipe-backend.css');

// Get config parameters
$params 			= JComponentHelper::getParams('com_yoorecipe');
$use_fractions		= $params->get('use_fractions', 0);
$canShowPrice		= $params->get('show_price', 0);
$currency			= $params->get('currency');
?>

<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="yoorecipe-form">
	<div class="width-60 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('id');echo $this->form->getInput('id'); ?></li>
			<li><?php echo $this->form->getLabel('asset_id');echo $this->form->getInput('asset_id'); ?></li>
			<li><?php echo $this->form->getLabel('title');echo $this->form->getInput('title'); ?></li>
			<li><?php echo $this->form->getLabel('alias');echo $this->form->getInput('alias'); ?></li>
			<li><?php echo $this->form->getLabel('published');echo $this->form->getInput('published'); ?></li>
			<li><?php echo $this->form->getLabel('validated');echo $this->form->getInput('validated'); ?></li>
			<li><?php echo $this->form->getLabel('featured');echo $this->form->getInput('featured'); ?></li>
			<li><?php echo $this->form->getLabel('language');echo $this->form->getInput('language'); ?></li>
			<li><?php echo $this->form->getLabel('price');echo $this->form->getInput('price'); ?><span style="float: left; padding-top: 7px;"><?php echo $currency;?></span></li>
			<li><?php echo $this->form->getLabel('category_id');echo $this->form->getInput('category_id'); ?></li>
			<li><?php echo $this->form->getLabel('season_id');echo $this->form->getInput('season_id'); ?></li>
			<li><?php echo $this->form->getLabel('spacer1'); echo $this->form->getInput('spacer1'); ?></li>
			<li><?php echo $this->form->getLabel('description');echo $this->form->getInput('description'); ?></li>
			<li><?php echo $this->form->getLabel('spacer2');echo $this->form->getInput('spacer2'); ?></li>
			<li><?php echo $this->form->getLabel('preparation');echo $this->form->getInput('preparation'); ?></li>
<?php 		if ($use_tags) { ?>			
			<li>
			<label><?php echo JText::_('COM_YOORECIPE_TAG_LBL'); ?></label>
			<div class="formelm">
				<input type="text" id="currentTag" class="inputbox"/>
				<input type="button" onclick="addTag();" value="<?php echo JText::_('COM_YOORECIPE_TAG_ADD'); ?>"/>
				<div id="includedTags">
				<?php
					if ($this->tags) {
						foreach ($this->tags as $tag) :
				?>
							<span class='withTg'>
								<?php echo $tag->tag_value; ?>
								<span class='TgRemove' onclick='disposeElt(this)'>x</span>
								<input type='hidden' name='withTags[]'value=<?php echo $tag->tag_value; ?> />
							</span>
				<?php endforeach;
					}
				?>
				</div>
			</div>
			</li>
<?php 		} ?>
			<li><?php echo $this->form->getLabel('difficulty');echo $this->form->getInput('difficulty'); ?></li>
			<li><?php echo $this->form->getLabel('cost');echo $this->form->getInput('cost'); ?></li>
			
			<li>
				<?php echo $this->form->getLabel('preparation_time'); ?>
				<fieldset class="radio">
				<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'prep', $this->form->getValue('preparation_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration', 'prep', $this->form->getValue('preparation_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','prep', $this->form->getValue('preparation_time')); ?>
				</fieldset>
			</li>

			<li>
				<?php echo $this->form->getLabel('cook_time'); ?>
				<fieldset class="radio">
				<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'cook', $this->form->getValue('cook_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration','cook', $this->form->getValue('cook_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','cook', $this->form->getValue('cook_time')); ?>
				</fieldset>
			</li>

			<li>
				<?php echo $this->form->getLabel('wait_time'); ?>
				<fieldset class="radio">
				<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'wait', $this->form->getValue('wait_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration','wait', $this->form->getValue('wait_time')); ?>
				<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','wait', $this->form->getValue('wait_time')); ?>
				</fieldset>
			</li>
			
			<li><?php echo $this->form->getLabel('picture');echo $this->form->getInput('picture'); ?></li>
			<li><?php echo $this->form->getLabel('video');echo $this->form->getInput('video'); ?></li>
		</ul>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_INGREDIENTS' ); ?></legend>
		
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('nb_persons'); echo $this->form->getInput('nb_persons'); echo $this->form->getInput('servings_type'); ?></li>
			<li><?php echo $this->form->getLabel('spacer1'); echo $this->form->getInput('spacer1'); ?></li>
		</ul>
		
		<div style="clear:both">&nbsp;</div>
		
		<div id="ajax_container">
			<table>
				<thead>
					<tr>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ORDER'); ?></th>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_GROUP'); ?></th>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_QUANTITY'); ?></th>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_UNIT'); ?></th>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_DESCRIPTION'); ?></th>
						<th><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_PRICE') ; ?>&nbsp;(<?php echo $currency;?>)</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody id="tBodyIngredients">
				<?php
				if ($this->ingredients) {
					foreach ($this->ingredients as $crtIngredient) :
				?>
					<tr id="row_ingredient_id_<?php echo $crtIngredient->id; ?>">
						<td><input type="text" size="3" name="ordering[]" id="order_<?php echo $crtIngredient->id; ?>" value="<?php echo $crtIngredient->ordering; ?>"/></td>
						<td>
							<?php 
							echo '<select name="group[]" id="group_' . $crtIngredient->id . '">';
							foreach($this->groups as $group) {
								if ( $crtIngredient->group_id == $group->id ) {
									echo '<option value="' . $group->id. '" selected="selected">'.JText::_($group->text).'</option>';
								} else {
									echo '<option value="' . $group->id. '">'.JText::_($group->text).'</option>';
								}
							}
							echo '</select>';
							?>
						</td>
						<td>
							<input type="hidden" name="ingredientId[]" id="ingredientId" value="<?php echo $crtIngredient->id; ?>"/>
							
							<?php $qtyValue = ($use_fractions) ? JHtmlYooRecipeAdminUtils::decimalToFraction($crtIngredient->quantity) : round($crtIngredient->quantity, 2); ?>
							<input type="text" name="quantity[]" id="quantity_<?php echo $crtIngredient->id; ?>" value="<?php echo $qtyValue; ?>"/>
						</td>
						<td>
							<select name="unit[]" id="unit_<?php echo $crtIngredient->id; ?>">
								<option value=""></option>
							<?php
								foreach ($this->units as $crtUnit)
								{
									if ( $crtIngredient->unit == $crtUnit->code ) {
										echo '<option value="'.$crtUnit->code.'" selected="selected">'.JText::_($crtUnit->label).'</option>';
									} else {
										echo '<option value=\''.$crtUnit->code.'\'>'.JText::_($crtUnit->label).'</option>';
									}
								}
							?>
							</select>
						</td>
						<td><input type="text" name="ingr_description[]" id="ingr_description_<?php echo $crtIngredient->id; ?>" value="<?php echo $crtIngredient->description; ?>"/></td>
						<td><input type="text" name="price[]" id="price_<?php echo $crtIngredient->id; ?>" value="<?php echo $crtIngredient->price; ?>"/></td>
						<td><input type="button" onclick="deleteIngredient(this)" value="<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_DELETE'); ?>"/></td>
						<td id="updMessage_<?php echo $crtIngredient->id; ?>"></td>
					</tr>
<?php
					endforeach;
				} // end if
?>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<input type="text" size="3" name="ordering[]" id="order" value=""/>
						</td>
						<td>
							<select name="group[]" id="group">
							<?php
							foreach($this->groups as $group) {
								echo '<option value="' . $group->id. '">'.JText::_($group->text).'</option>';
							} ?>
							</select>
						<td>
							<input type="hidden" name="recipe_id[]" id="recipe_id" value="<?php echo (int) $this->item->id ?>"/>
							<input type="text" name="quantity[]" id="quantity"/>
						</td>
						<td>
							<select name="unit[]" id="unit">
								<?php
								foreach ($this->units as $crtUnit) {
									echo '<option value=\''.$crtUnit->code.'\'>'.JText::_($crtUnit->label).'</option>';
								} ?>
							</select>
						</td>
						<td><input type="text" name="ingr_description[]" id="ingr_description"/></td>
						<td><input type="text" name="price[]" id="price"/></td>
						<td><input type="button" onclick="com_yoorecipe_addIngredient();" value="<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ADD'); ?>"/></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</fieldset>
	</div>
	
	<div class="width-40 fltrt">

			<div class="clr"></div>

			<fieldset class="panelform">
				<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_PUBLICATION' ); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('access');echo $this->form->getInput('access'); ?></li>
					<li><?php echo $this->form->getLabel('created_by');echo $this->form->getInput('created_by'); ?></li>
					<li><?php echo $this->form->getLabel('creation_date');echo $this->form->getInput('creation_date'); ?></li>
					<li><?php echo $this->form->getLabel('publish_up');echo $this->form->getInput('publish_up'); ?></li>
					<li><?php echo $this->form->getLabel('publish_down');echo $this->form->getInput('publish_down'); ?></li>
					<li><?php echo $this->form->getLabel('nb_views');echo $this->form->getInput('nb_views'); ?></li>
				</ul>
			</fieldset>
			
			<fieldset class="panelform">
				<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_NUTRITION_FACTS' ); ?></legend>
				<div>
					<ul>
						<li><?php echo $this->form->getLabel('diet');echo $this->form->getInput('diet'); ?></li>
						<li><?php echo $this->form->getLabel('veggie');echo $this->form->getInput('veggie'); ?></li>
						<li><?php echo $this->form->getLabel('gluten_free');echo $this->form->getInput('gluten_free'); ?></li>
						<li><?php echo $this->form->getLabel('lactose_free');echo $this->form->getInput('lactose_free'); ?></li>
					</ul>
					<br/>
					<ul>
						<li><?php echo $this->form->getLabel('carbs'); echo $this->form->getInput('carbs'); ?></li>
						<li><?php echo $this->form->getLabel('fat');echo $this->form->getInput('fat'); ?></li>
						<li><?php echo $this->form->getLabel('saturated_fat');echo $this->form->getInput('saturated_fat'); ?></li>
						<li><?php echo $this->form->getLabel('proteins');echo $this->form->getInput('proteins'); ?></li>
						<li><?php echo $this->form->getLabel('fibers');echo $this->form->getInput('fibers'); ?></li>
						<li><?php echo $this->form->getLabel('salt');echo $this->form->getInput('salt'); ?></li>
						<li><?php echo $this->form->getLabel('kcal');echo $this->form->getInput('kcal'); ?></li>
					</ul>
				</div>
			</fieldset>
			
			<fieldset class="panelform">
				<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_SEO' ); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('metakey');echo $this->form->getInput('metakey'); ?></li>
					<li><?php echo $this->form->getLabel('metadata');echo $this->form->getInput('metadata'); ?></li>
				</ul>	
			</fieldset>
	</div>
	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="task" value="yoorecipe.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>