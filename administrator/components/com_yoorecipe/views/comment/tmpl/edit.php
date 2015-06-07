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
?>

<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="yoorecipe-form">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_YOORECIPE_YOORECIPE_DETAILS' ); ?></legend>
		<ul class="adminformlist">

			<li><?php echo $this->form->getInput('id'); ?></li>
			<li><?php echo $this->form->getInput('recipe_id'); ?></li>
			
			<li><?php echo $this->form->getLabel('title');echo $this->form->getInput('title'); ?></li>
			<li><?php echo $this->form->getLabel('note');echo $this->form->getInput('note'); ?></li>
			<li style="clear:both"><?php echo $this->form->getLabel('comment');echo $this->form->getInput('comment'); ?></li>
			<li><?php echo $this->form->getLabel('published');echo $this->form->getInput('published'); ?></li>
			<li><?php echo $this->form->getLabel('abuse');echo $this->form->getInput('abuse'); ?></li>
			<li><?php echo $this->form->getLabel('creation_date');echo $this->form->getInput('creation_date'); ?></li>
		</ul>
	</fieldset>
	
	<div>
		<input type="hidden" name="task" value="yoorecipe.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>