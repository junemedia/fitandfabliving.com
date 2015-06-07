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
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
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

// Get config parameters
$use_fractions		= $params->get('use_fractions', 0);
$canShowPrice		= $params->get('show_price', 0);
$currency		= $params->get('currency', '&euro;');

$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getJ3_IngredientsManagementScript', $this->units, $currency));
$document->addScriptDeclaration(JHtml::_('yoorecipecommonjsutils.getFractionsScript'));
$document->addStyleSheet(Juri::root().'media/com_yoorecipe/styles/yoorecipe-backend.css');

?>

<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('asset_id'); ?>
	
	<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span10 form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_DETAILS');?></a></li>
				<li><a href="#ingredients" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_INGREDIENTS');?></a></li>
				<li><a href="#publication" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_PUBLICATION');?></a></li>
				<li><a href="#nutritionfacts" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_NUTRITION_FACTS');?></a></li>
				<li><a href="#seo" data-toggle="tab"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_SEO');?></a></li>
			</ul>

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
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('published'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('published'); ?>
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
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('price'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('price'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('season_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('season_id'); ?>
						</div>
					</div>
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
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('description'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('description'); ?>
						</div>
					</div>
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
					</div>
	<?php			} // End if ($use_tags) { ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('difficulty'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('difficulty'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('cost'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('cost'); ?>
						</div>
					</div>
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
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('picture'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('picture'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('video'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('video'); ?>
						</div>
					</div>
				</div>
				<!-- End tab general -->
				<div class="tab-pane" id="ingredients">
					<fieldset>
						<?php echo $this->loadTemplate('j3_ingredients'); ?>
					</fieldset>
				</div>
				<div class="tab-pane" id="publication">
					<fieldset>
						<?php echo $this->loadTemplate('j3_publication'); ?>
					</fieldset>
				</div>
				<div class="tab-pane" id="nutritionfacts">
					<fieldset>
						<?php echo $this->loadTemplate('j3_nutritionfacts'); ?>
					</fieldset>
				</div>
				<div class="tab-pane" id="seo">
					<fieldset>
						<?php echo $this->loadTemplate('j3_seo'); ?>
					</fieldset>
				</div>
				<!-- End Tabs -->
			</div>
			
			<input type="hidden" name="task" value="yoorecipe.edit" />
			<?php echo JHtml::_('form.token'); ?>
	
		</div>
		<!-- End Content -->
	</div>
</form>