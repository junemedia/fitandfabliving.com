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
JHtml::_('formbehavior.chosen', 'select');

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


// Add style and JS
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_single.css');
$document->addStyleSheet('media/com_yoorecipe/styles/ajax-upload.css');
//$document->addStyleSheet('media/com_yoorecipe/styles/nicetable.css');

$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getJ3_IngredientsManagementScript', $this->units, $currency));
$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.validateRecipeScript'));


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
<form action="<?php echo $uploadURL; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate form-horizontal">
	
	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('asset_id'); ?>
	
	<div class="btn-toolbar">
		<div class="btn-group">
		<button onclick="Joomla.submitbutton('insertRecipe');" class="btn btn-primary" type="button">
			<i class="icon-ok"></i><?php echo JText::_('JSAVE') ?></button>
		</div>
		<div class="btn-group">
		<button onclick="history.back();" class="btn" type="button">
		<i class="icon-cancel"></i><?php echo JText::_('JCANCEL') ?></button>
		</div>
	</div>

<div class="row-fluid">
	<!-- Begin Content -->
	<div class="span10 form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_DETAILS');?></a></li>
				<li><a href="#ingredients" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_INGREDIENTS');?></a></li>
			<?php if ($isAdmin) : ?>				
				<li><a href="#publication" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_PUBLICATION');?></a></li>
			<?php endif; ?>
				<li><a href="#nutritionfacts" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_NUTRITION_FACTS');?></a></li>
			<?php if ($isAdmin) : ?>
				<li><a href="#seo" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_SEO');?></a></li>
			<?php endif; ?>
			</ul>

			<input type="hidden" name="formUrl" value="<?php echo $uploadURL; ?>"/>
			<input type="hidden" name="created_by" value="<?php echo $this->form->getValue('created_by'); ?>"/>
			
			<div class="tab-content">
				<!-- Begin Tabs -->	
				<div class="tab-pane active" id="general">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('title'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('title'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('alias'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('alias'); ?>
						</div>
					</div>
					
				<?php if ($isAdmin) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('published'); ?>
						</div>
						<div class="controls">
							<?php $publishedValue = isset($this->item->published) ? $this->item->published : 0; ?>
							<?php echo $this->form->getInput('published', null, $auto_publish == 0 ? 0 : $publishedValue); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('validated'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('validated'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('featured'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('featured'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('language'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('language'); ?>
						</div>
					</div>
				<?php endif; ?>
					
				<?php if ($show_price) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('price'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('price').$currency; ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($show_seasons) : ?>	
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('season_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('season_id'); ?>
						</div>
					</div>
				<?php endif; ?>
				
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('category_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('category_id'); ?>
						</div>
					</div>

					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('spacer1'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('spacer1'); ?>
						</div>
					</div>
					
				<?php if ($show_description) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('description'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('description'); ?>
						</div>
					</div>
				<?php endif; ?>
				
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('spacer2'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('spacer2'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('preparation'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('preparation'); ?>
						</div>
					</div>
					
<?php			if ($use_tags) { ?>
					<div class="control-group">
						<div class="control-label">
							<label><?php echo JText::_('COM_YOORECIPE_TAG_LBL'); ?></label>
						</div>
						<div class="controls">
							<input type="text" id="currentTag" class="inputbox"/>
							<input type="button" class="btn" onclick="addTag();" value="<?php echo JText::_('COM_YOORECIPE_TAG_ADD'); ?>"/>
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
					</div>
		<?php	} // End if ($use_tags) { ?>
	
				<?php if ($show_difficulty) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('difficulty'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('difficulty'); ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($show_cost) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('cost'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('cost'); ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($show_preparation_time) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('preparation_time'); ?>
						</div>
						<div class="controls">
							<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'prep', $this->form->getValue('preparation_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration', 'prep', $this->form->getValue('preparation_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','prep', $this->form->getValue('preparation_time')); ?>
						</div>
					</div>
				<?php endif; ?>
	
				<?php if ($show_cook_time) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('cook_time'); ?>
						</div>
						<div class="controls">
							<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'cook', $this->form->getValue('cook_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration','cook', $this->form->getValue('cook_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','cook', $this->form->getValue('cook_time')); ?>
						</div>
					</div>
				<?php endif; ?>
	
				<?php if ($show_wait_time) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('wait_time'); ?>
						</div>
						<div class="controls">
							<?php echo JHtml::_('yoorecipeadminutils.selectdaysfromduration', 'wait', $this->form->getValue('wait_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectHoursFromDuration','wait', $this->form->getValue('wait_time')); ?>
							<?php echo JHtml::_('yoorecipeadminutils.selectMinutesFromDuration','wait', $this->form->getValue('wait_time')); ?>
						</div>
					</div>
				<?php endif; ?>

					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('picture'); ?>
						</div>
						<div class="controls">
						<?php 
							// echo $this->form->getInput('picture');
							if ($this->form->getValue('picture') != '') {
								echo '<img id="yoorecipe_picture" class="recipe-picture-single" src="'.$this->form->getValue('picture').'" alt="'.$this->form->getValue('title').'" style="width:'.$thumbnail_edit_width.'px"/>';
							} else {
								echo '<img id="yoorecipe_picture" class="recipe-picture-single" style="border:none" alt="" />';
							}
							
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
					</div>
					
				<?php if ($use_video == 1) { ?>	
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('video'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('video'); ?>
						</div>
					</div>
				<?php } ?>
				</div>	
				<!-- End tab general -->
				
				<div class="tab-pane" id="ingredients">
					<fieldset>
						<?php echo $this->loadTemplate('j3_ingredients'); ?>
					</fieldset>
				</div>
				
			<?php if ($isAdmin) { ?>
				<div class="tab-pane" id="publication">
					<fieldset>
						<?php echo $this->loadTemplate('j3_publication'); ?>
					</fieldset>
				</div>
			<?php } ?>
			
			<?php if ($use_nutrition_facts) { ?>
				<div class="tab-pane" id="nutritionfacts">
					<fieldset>
						<?php echo $this->loadTemplate('j3_nutritionfacts'); ?>
					</fieldset>
				</div>
			<?php } ?>
			
			<?php if ($isAdmin) { ?>
				<div class="tab-pane" id="seo">
					<fieldset>
						<?php echo $this->loadTemplate('j3_seo'); ?>
					</fieldset>
				</div>
			<?php } ?>
				<!-- End Tabs -->
			</div>
			
			<!--input type="hidden" name="task" value="yoorecipe.edit" /-->
			<input type="hidden" name="task" id="task" value="mootoolsUploadRecipePicture" />
			<input type="hidden" id="yoorecipe_js_msg_upload" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_UPLOAD'); ?>"/>
			<input type="hidden" id="yoorecipe_js_msg_too_big" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_FILE_TOO_BIG'); ?>"/>
			<input type="hidden" id="yoorecipe_js_msg_empty" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_EMPTY_FILE'); ?>"/>
			<input type="hidden" id="yoorecipe_js_msg_not_allowed" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_NON_SUPPORTED_EXTENSION'); ?>"/>
			<input type="hidden" id="yoorecipe_js_msg_error" value="<?php echo JText::_('COM_YOORECIPE_JS_MSG_ERROR_OCCURED'); ?>"/>
			<?php echo JHtml::_('form.token'); ?>
	
		</div>
		<!-- End Content -->
	</div>
</form>