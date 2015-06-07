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

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_YOORECIPE_YOORECIPE_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_COMMENT', 'rat.comment', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_TITLE', 'r.title', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_NOTE', 'rat.note', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_AUTHOR', 'rat.author', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_EMAIL', 'rat.email', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_PUBLISHED', 'rat.published', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_OFFENSIVE', 'rat.abuse', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_CREATION_DATE', 'rat.creation_date', $listDirn, $listOrder); ?>
	</th>
</tr>