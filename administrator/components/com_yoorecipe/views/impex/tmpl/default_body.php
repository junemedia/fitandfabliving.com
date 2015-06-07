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
			<a href="<?php echo JRoute::_( 'index.php?option=com_yoorecipe&task=yoorecipe.edit&id='.$item->id ); ?>"><?php echo $item->title; ?></a>
			<p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
		</td>
		<td><?php echo JHtml::_('yoorecipeadminutils.featured', $item->featured, $i, true); ?></td>
		<td>
			<?php 
			foreach ($item->categories as $category) :
				echo $category->title . ',';
			endforeach; ?>
		</td>
		<td>
			<?php echo $item->creation_date; ?>
		</td>
		<td>
			<?php echo $item->author_name; ?>
		</td>
		<td>
			<?php
			for ($j = 0 ; $j < $item->difficulty; $j++) {
		?>
			<img src="../media/com_yoorecipe/images/star-icon.png"/>
		<?php
			}
		?>
		</td>
		<td>
			<?php
			for ($j = 0 ; $j < $item->cost; $j++) {
		?>
			<img src="../media/com_yoorecipe/images/star-icon.png"/>
		<?php
			}
		?>
		</td>
		<td>
			<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->preparation_time);	?>
		</td>
		<td>
			<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->cook_time); ?>
		</td>
		<td>
			<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->wait_time); ?>
		</td>
		<td>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'yoorecipes.'); ?>
		</td>
		<td>
			<?php echo JHtml::_('yoorecipeadminutils.validated', $item->validated, $i); ?>
		</td>
		<td>
			<?php echo $item->nb_views; ?>
		</td>
		<td>
			<?php echo $item->note; ?>
		</td>
	</tr>
<?php endforeach; ?>