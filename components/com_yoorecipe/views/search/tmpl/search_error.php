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

// no direct access
defined('_JEXEC') or die;
?>
<?php if($this->error): ?>
<div class="error">
	<?php echo $this->escape($this->error); ?>
</div>
<?php endif; ?>

<?php if ($this->searchPerformed && count($this->items)== 0) : ?>
<div class="error">
	<?php echo JText::_('COM_YOORECIPE_SEARCH_NO_RESULTS_FOUND'); ?>
</div>
<?php endif; ?>