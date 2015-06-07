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
		<?php echo $this->form->getLabel('metakey'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('metakey'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo $this->form->getLabel('metadata'); ?>
	</div>
	<div class="controls">
		<?php echo $this->form->getInput('metadata'); ?>
	</div>
</div>