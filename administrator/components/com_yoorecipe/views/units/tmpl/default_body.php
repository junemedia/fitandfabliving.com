<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe componentJHT
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php echo $item->lang; ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=unit.edit&id=' . $item->id); ?>"><?php echo $item->code; ?></a>
		</td>
		<td>
			<?php echo $item->label; ?>
		</td>
		<td>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'units.'); ?>
		</td>
		<td>
			<?php echo $item->creation_date; ?>
		</td>
	</tr>
<?php endforeach; ?>