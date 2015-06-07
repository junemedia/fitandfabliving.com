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
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
JHtml::_('script','system/multiselect.js',false,true);
		
// Load the modal behavior script.
JHtml::_('behavior.modal');

// Build the script.
$script = array();

$script[] = '	function jMediaRefreshPreviewTip(tip)';
$script[] = '	{';
$script[] = '		tip.setStyle("display", "block");';
$script[] = '		var img = tip.getElement("img.media-preview");';
$script[] = '		var id = img.getProperty("id");';
$script[] = '		id = id.substring(0, id.length - "_preview".length);';
$script[] = '	}';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
?>
<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe'); ?>" method="post" name="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_yoorecipe'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
			
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('yoorecipeadminutils.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			
			<select name="filter_validated" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_YOORECIPE_SELECT_VALIDATED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('yoorecipeadminutils.validatedOptions'), 'value', 'text', $this->state->get('filter.validated'), true);?>
			</select>
			
			<select name="filter_created_by" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_YOORECIPE_YOORECIPE_HEADING_CREATED_BY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('yoorecipeadminutils.createdByOptions', 'com_yoorecipe'), 'value', 'text', $this->state->get('filter.created_by'));?>
			</select>
			
			 <select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>
			
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>
		</div>
		
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>