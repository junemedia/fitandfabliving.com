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
?>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('access'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('access'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('created_by'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('created_by'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('creation_date'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('creation_date'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('publish_up'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('publish_up'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('publish_down'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('publish_down'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('nb_views'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('nb_views'); ?>
	</div>
</div>