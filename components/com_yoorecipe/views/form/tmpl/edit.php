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

JHtml::_('behavior.formvalidation');
JHtmlBehavior::framework();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

jimport('joomla.environment.uri' );
jimport('joomla.environment.browser');

$browser 	= JBrowser::getInstance();
$document 	= JFactory::getDocument();
$host 		= JURI::root();

// Load content language file
$lang = JFactory::getLanguage();
$extension = 'com_categories';
$base_dir = JPATH_ADMINISTRATOR;

$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);


// Add style and JS
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_single.css');
$document->addStyleSheet('media/com_yoorecipe/styles/ajax-upload.css');
//$document->addStyleSheet('media/com_yoorecipe/styles/nicetable.css');

$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getManageIngredientsScript', $this->units, $this->groups));
$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.validateRecipeScript'));

// Get config parameters
$params 				= JComponentHelper::getParams('com_yoorecipe');
$use_recipe_settings 	= $params->get('use_recipe_settings', 1);
$use_tags				= $params->get('use_tags', 1);

$auto_publish 			= $params->get('auto_publish', 1);
$currency				= $params->get('currency', '&euro;');
$auto_validate 			= $params->get('auto_validate', 0);
$thumbnail_edit_width	= $params->get('thumbnail_edit_width', 250);

// Make upload work for ipads
$upload_system			= $browser->getBrowser() == 'safari' ? 'dragndrop' : $params->get('upload_system', 'dragndrop');

$use_fractions			= $params->get('use_fractions', 0);
$use_video				= $use_recipe_settings ? $params->get('use_video', 1) : $params->get('show_video_fe', 1);
$show_price				= $use_recipe_settings ? $params->get('show_price', 0) : $params->get('show_price_fe', 0);

// Get menu parameters
$show_description		= $use_recipe_settings ? $params->get('show_description', 1) : $params->get('show_description_fe', 1);
$show_difficulty		= $use_recipe_settings ? $params->get('show_difficulty', 1) : $params->get('show_difficulty_fe', 1);
$show_cost				= $use_recipe_settings ? $params->get('show_cost', 1) : $params->get('show_cost_fe', 1);
$show_preparation_time	= $use_recipe_settings ? $params->get('show_preparation_time', 1) : $params->get('show_preparation_time_fe', 1);
$show_cook_time			= $use_recipe_settings ? $params->get('show_cook_time', 1) : $params->get('show_cook_time_fe', 1);
$show_wait_time 		= $use_recipe_settings ? $params->get('show_wait_time', 1) : $params->get('show_wait_time_fe', 1);
$show_video 			= $use_recipe_settings ? $params->get('show_video', 1) : $params->get('show_video_fe', 1);
$show_seasons 			= $use_recipe_settings ? $params->get('show_seasons', 1) : $params->get('show_seasons_fe', 1);

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

// Get user
$user			= JFactory::getUser();
$isAdmin		= $user->authorise('core.admin', 'com_yoorecipe');

if ($use_tags) { $document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getTagManagementScript')); }

/*$js="function validateEditor(){
	var content = ".$this->form->getField('preparation')->getContent('editorName').";
	return content;                 
}";
$document->addScriptDeclaration($js);*/

$urlForm = JRoute::_('index.php?option=com_yoorecipe&task=editRecipe&layout=edit'); 
$uploadURL = JRoute::_('index.php?option=com_yoorecipe&task=mootoolsUploadRecipePicture&format=raw');
?>

<script type="text/javascript">

 //<![CDATA[
	Joomla.submitbutton = function(task) {
		if (task == 'insertRecipe') {
			
			// title
			var result = com_yoorecipe_checkTitle();
			if (!result) {
				SqueezeBox.open('index.php?option=com_yoorecipe&view=form&tmpl=component&layout=squeezebox_msg&msg=COM_YOORECIPE_TITLE_MANDATORY', {handler: 'iframe', size: {x: 200, y: 100}});
			}
			else { 
				if (com_yoorecipe_checkIngredients() == false) {
					// ingredients
					SqueezeBox.open('index.php?option=com_yoorecipe&view=form&tmpl=component&layout=squeezebox_msg&msg=COM_YOORECIPE_INGREDIENTS_MANDATORY', {handler: 'iframe', size: {x: 200, y: 100}});
				} else if (document.id('adminForm').elements["jform[category_id][]"].selectedIndex == -1) {
					
					// categories
					SqueezeBox.open('index.php?option=com_yoorecipe&view=form&tmpl=component&layout=squeezebox_msg&msg=COM_YOORECIPE_CATEGORIES_MANDATORY', {handler: 'iframe', size: {x: 200, y: 100}});
				} else {
					<?php echo $this->form->getField('preparation')->save(); ?>
					<?php echo $this->form->getField('description')->save(); ?>
					$('adminForm').set('action', '<?php echo $urlForm; ?>');
					$('task').value = 'insertRecipe';
					if (window['iffr'] != undefined) { iffr.detach()};
					Joomla.submitform(task);
				}
			}
		} else if (task == 'mootoolsUploadRecipePicture') {
			$('adminForm').set('action', '<?php echo $uploadURL; ?>');
			$('task').value = 'mootoolsUploadRecipePicture';
			Joomla.submitform(task);
		}
	}
 /* ]]> */
</script>

<form action="<?php echo $uploadURL; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<fieldset class="adminform">
		
		<input type="hidden" name="formUrl" value="<?php echo $uploadURL; ?>"/>
		<input type="hidden" name="created_by" value="<?php echo $this->form->getValue('created_by'); ?>"/>
		
		<?php echo $this->form->getInput('id'); ?>
		
		<legend><h2><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_DETAILS' ); ?></h2></legend>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('category_id'); ?>
			<?php echo $this->form->getInput('category_id'); ?>
		</div>
		
	<?php if ($show_seasons) : ?>	
		<div class="formelm">
			<?php echo $this->form->getLabel('season_id'); ?>
			<?php echo $this->form->getInput('season_id'); ?>
		</div>
	<?php endif; ?>
		
	<?php if ($show_description) : ?>
	<div>
			<?php echo $this->form->getLabel('description'); ?>
			<?php echo $this->form->getInput('description'); ?>
		</div>
	<?php endif; ?>
		
		<div class="clear"></div>
		
	<?php if ($show_difficulty) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('difficulty'); ?>
			<?php echo $this->form->getInput('difficulty'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_cost) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('cost'); ?>
			<?php echo $this->form->getInput('cost'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_price) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('price'); ?>
			<?php echo $this->form->getInput('price') . $currency;?>
		</div>
	<?php endif; ?>
	
	<?php if ($use_tags) : ?>
	<div class="formelm">
		<label><?php echo JText::_('COM_YOORECIPE_TAG_LBL'); ?></label>
		<input type="text" id="currentTag" class="inputbox"/>
		<input type="button" class="btn" onclick="addTag();" value="<?php echo JText::_('COM_YOORECIPE_TAG_ADD'); ?>"/>
		<div id="includedTags">
		<?php
	if ($this->tags) {
		foreach ($this->tags as $tag) :	?>
			<span class='withTg'>
				<?php echo $tag->tag_value; ?>
				<span class='TgRemove' onclick='disposeElt(this)'>x</span>
				<input type='hidden' name='withTags[]'value=<?php echo $tag->tag_value; ?> />
			</span>
<?php	endforeach;
	} ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php endif; ?>

	<?php if ($show_preparation_time) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('preparation_time'); ?>
			<?php echo JHtmlYooRecipeUtils::selectDaysFromDuration('prep', $this->form->getValue('preparation_time')) . JText::_('COM_YOORECIPE_DAY'); ?>
			<?php echo JHtmlYooRecipeUtils::selectHoursFromDuration('prep', $this->form->getValue('preparation_time')) . JText::_('COM_YOORECIPE_HOUR'); ?>
			<?php echo JHtmlYooRecipeUtils::selectMinutesFromDuration('prep', $this->form->getValue('preparation_time')) . JText::_('COM_YOORECIPE_MIN'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_cook_time) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('cook_time'); ?>
			<?php echo JHtmlYooRecipeUtils::selectDaysFromDuration('cook', $this->form->getValue('cook_time')) . JText::_('COM_YOORECIPE_DAY'); ?>
			<?php echo JHtmlYooRecipeUtils::selectHoursFromDuration('cook', $this->form->getValue('cook_time')) . JText::_('COM_YOORECIPE_HOUR'); ?>
			<?php echo JHtmlYooRecipeUtils::selectMinutesFromDuration('cook', $this->form->getValue('cook_time')) . JText::_('COM_YOORECIPE_MIN'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_wait_time) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('wait_time'); ?>
			<?php echo JHtmlYooRecipeUtils::selectDaysFromDuration('wait', $this->form->getValue('wait_time')) . JText::_('COM_YOORECIPE_DAY'); ?>
			<?php echo JHtmlYooRecipeUtils::selectHoursFromDuration('wait', $this->form->getValue('wait_time')) . JText::_('COM_YOORECIPE_HOUR'); ?>
			<?php echo JHtmlYooRecipeUtils::selectMinutesFromDuration('wait', $this->form->getValue('wait_time')) . JText::_('COM_YOORECIPE_MIN'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($isAdmin) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('nb_views'); ?>
			<?php echo $this->form->getInput('nb_views'); ?>
		</div>
	<?php endif; ?>
		
	<?php if ($isAdmin) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('published'); ?>
			<?php $publishedValue = isset($this->item->published) ? $this->item->published : 0; ?>
			<?php echo $this->form->getInput('published', null, $auto_publish == 0 ? 0 : $publishedValue); ?>
		</div>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('validated'); ?>
			<?php $validatedValue = isset($this->item->validated) ? $this->item->validated : 0; ?>
			<?php echo $this->form->getInput('validated', null, $auto_validate == 0 ? 0 : $validatedValue); ?>
		</div>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('featured'); ?>
			<?php $featuredValue = isset($this->item->featured) ? $this->item->featured : 0; ?>
			<?php echo $this->form->getInput('featured', null, $featuredValue); ?>
		</div>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('language'); ?>
			<?php $languageValue = isset($this->item->language) ? $this->item->language : 0; ?>
			<?php echo $this->form->getInput('language'); ?>
		</div>
	<?php endif; ?>
		
	<?php if ($show_kcal || $show_diet || $show_veggie || $show_gluten_free || $show_lactose_free || $show_carbs  || $show_fat || $show_sfat || $show_proteins || $show_fibers || $show_salt) { ?>
		<h2><?php echo JText::_('COM_YOORECIPE_RECIPES_NUTRITION_FACTS'); ?></h2>
	<?php } ?>
	
	<?php if ($show_diet) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('diet');echo $this->form->getInput('diet'); ?>
		</div>
	<?php endif; ?>
	<?php if ($show_veggie) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('veggie');echo $this->form->getInput('veggie'); ?>
		</div>
	<?php endif; ?>
	<?php if ($show_gluten_free) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('gluten_free');echo $this->form->getInput('gluten_free'); ?>
		</div>
	<?php endif; ?>
	<?php if ($show_lactose_free) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('lactose_free');echo $this->form->getInput('lactose_free'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_kcal) : ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('kcal');?>
			<?php echo $this->form->getInput('kcal'); ?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_carbs) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('carbs') . $this->form->getInput('carbs') . JText::_('COM_YOORECIPE_GRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>	
	
	<?php if ($show_fat) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('fat') . $this->form->getInput('fat') . JText::_('COM_YOORECIPE_GRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>	
	
	<?php if ($show_sfat) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('saturated_fat') . $this->form->getInput('saturated_fat') . JText::_('COM_YOORECIPE_GRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>	
	
	<?php if ($show_proteins) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('proteins') . $this->form->getInput('proteins') . JText::_('COM_YOORECIPE_GRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>	
	
	<?php if ($show_fibers) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('fibers') . $this->form->getInput('fibers') . JText::_('COM_YOORECIPE_GRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>	
	
	<?php if ($show_salt) : ?>	
		<div class="formelm">
		<?php echo $this->form->getLabel('salt') . $this->form->getInput('salt') . JText::_('COM_YOORECIPE_MILLIGRAMS_SYMBOL'); ?>
		</div>
	<?php endif; ?>

		<br />
		<h2><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_INGREDIENTS' ); ?></h2>
		
		<div class="formelm">
			<?php echo $this->form->getLabel('nb_persons'); ?>
			<?php echo $this->form->getInput('nb_persons'); ?>
			<?php echo $this->form->getInput('servings_type'); ?>
		</div>
		
		<div class="formelm">
		</div>
		
		<div id="ajax_container">
			<table id="rounded-corner">
				<thead>
					<tr>
						<th class="rounded-company"><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_GROUP'); ?></th>
						<th class="rounded-1"><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_QUANTITY'); ?></th>
						<th class="rounded-2"><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_UNIT'); ?></th>
						<th class="rounded-3"><?php echo JText::_('COM_YOORECIPE_INGREDIENTS_DESCRIPTION'); ?></th>
						<th class="rounded-4"></th>
					</tr>
				</thead>
				<tbody id="tBodyIngredients">
				<?php
			if (isset($this->ingredients)) {
				foreach ($this->ingredients as $crtIngredient) 
				{
				?>
					<tr id="row_ingredient_id_<?php echo $crtIngredient->id; ?>">
						<td>
							<select name="group[]" id="group_id">
							<?php foreach($this->groups as $group) {
								if ($crtIngredient->group_id == $group->id) {
									echo '<option value="' . $group->id . '" selected="selected">' . JText::_($group->text) . '</option>';
								} else {
									echo '<option value="' . $group->id . '">' . JText::_($group->text) . '</option>';
								}
							} ?>
							</select>
						</td>
						<td>
							<input type="hidden" name="ingrId[]" id="ingredientId" value="<?php echo $crtIngredient->id; ?>"/>
							<?php 
							$qtyValue = '';
							if ($use_fractions) { 
								$qtyValue = JHtmlYooRecipeUtils::decimalToFraction($crtIngredient->quantity);
							} else {
								$qtyValue = (abs($crtIngredient->quantity) < 0.00001) ? '' : round($crtIngredient->quantity, 2);
							}
							?>
							<input type="text" name="quantity[]" id="quantity_<?php echo $crtIngredient->id; ?>" value="<?php echo $qtyValue; ?>"/>
						</td>
						<td>
							<select name="unit[]" id="unit_<?php echo $crtIngredient->id; ?>">
							<?php
								foreach ($this->units as $unit) {
									if ( $crtIngredient->unit == $unit->code ) {
										echo '<option value="'.$unit->code.'" selected="selected">'.JText::_($unit->label).'</option>';
									} else {
										echo '<option value=\''.$unit->code.'\'>'.JText::_($unit->label).'</option>';
									}
								}
							?>
							</select>
						</td>
						<td><input type="text" name="ingr_description[]" id="description_<?php echo $crtIngredient->id; ?>" value="<?php echo $crtIngredient->description; ?>"/></td>
						<td><input type="button" onclick="com_yoorecipe_deleteIngredient(this.parentNode)" class="btn" value="<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_DELETE'); ?>"/></td>
					</tr>
		<?php
				}
			}
		?>
				</tbody>
				<tfoot>
					<tr>
						<td class="rounded-foot-left">
							<select name="group[]" id="add_group">
							<?php foreach($this->groups as $group) {
									echo '<option value="' . $group->id . '">' . JText::_($group->text) . '</option>';
							} ?>
							</select>
						</td>
						<td>
							<input type="hidden" name="add_recipe_id" id="add_recipe_id" value="<?php if (isset($this->item)) : echo (int) $this->item->id; endif;?>"/>
							<input type="hidden" name="ingrId[]">
							<input type="text" name="quantity[]" id="add_quantity"/>
						</td>
						<td>
							<select name="unit[]" id="add_unit">
							<?php foreach ($this->units as $unit) {
								echo '<option value=\''.$unit->code.'\'>'.JText::_($unit->label).'</option>';
							}  ?>
							</select>
						</td>
						<td><input type="text" name="ingr_description[]" id="add_description"/></td>
						<td class="rounded-foot-right"><input type="button" class="btn" onclick="com_yoorecipe_addRecipeIngredient();" value="<?php echo JText::_('COM_YOORECIPE_INGREDIENTS_ADD'); ?>"/></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<br />
		<h2><?php echo $this->form->getLabel('preparation'); ?></h2>
		<div class="formelm">
			<?php echo $this->form->getInput('preparation'); ?>
		</div>
		<br /><br />
		<h2><?php echo $this->form->getLabel('picture'); ?></h2>
	<?php if ($this->form->getValue('picture') != '') { ?>
		<img id="yoorecipe_picture" class="recipe-picture-single" src="<?php echo $this->form->getValue('picture'); ?>" alt="<?php echo $this->form->getValue('title'); ?>" style="width:<?php echo $thumbnail_edit_width; ?>px"/>
	<?php } else { ?>
		<img id="yoorecipe_picture" class="recipe-picture-single" style="border:none" alt="" />
	<?php }?>
		<div class="formelm">
<?php
		if ($upload_system == 'flash') {
			echo JHtml::_('yoorecipeutils.generateSWFUpload', $this->form->getValue('picture'));
		}
		else if ($upload_system == 'std') {
			echo $this->form->getInput('picture');
			echo '<div class="clear"></div>';
		}
		else if ($upload_system == 'dragndrop') {
			echo JHtml::_('yoorecipeutils.generateDragNDropUpload', $this->form->getValue('picture'));
		}
?>
		</div>
	
<?php if ($use_video == 1) { ?>	
		<div class="formelm">
			<?php echo $this->form->getLabel('video'); ?>
			<?php echo $this->form->getInput('video'); ?>
		</div>
<?php } ?>
	</fieldset>

	<fieldset class="adminform">
		<div>
			<input type="hidden" name="task" id="task" value="mootoolsUploadRecipePicture" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</div>
		<div class="formelm-buttons">
			<input type="button" class="btn" id="btnSubmit" onclick="Joomla.submitbutton('insertRecipe')" value="<?php echo JText::_('JSAVE') ?>"/>
			<input type="button" class="btn" onclick="history.back();" value ="<?php echo JText::_('JCANCEL') ?>" />
		</div>
	</fieldset>
	
	<input type="hidden" id="yoorecipe_js_msg_upload" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_UPLOAD'); ?>"/>
	<input type="hidden" id="yoorecipe_js_msg_too_big" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_FILE_TOO_BIG'); ?>"/>
	<input type="hidden" id="yoorecipe_js_msg_empty" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_EMPTY_FILE'); ?>"/>
	<input type="hidden" id="yoorecipe_js_msg_not_allowed" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_NON_SUPPORTED_EXTENSION'); ?>"/>
	<input type="hidden" id="yoorecipe_js_msg_error" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_ERROR_OCCURED'); ?>"/>
</form>	