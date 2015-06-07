<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe componentJHT
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
		<td><a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=comment.edit&id='.$item->id) ?>"><?php echo substr($item->comment, 0, 60); if (strlen($item->comment) > 60) { echo '...'; }?></a></td>
		<td>
			<a href="<?php echo JRoute::_( 'index.php?option=com_yoorecipe&task=yoorecipe.edit&id='.$item->recipe_id ); ?>"><?php echo $item->title; ?></a>
		</td>
		<td>
		<?php for ($j = 0 ; $j < $item->note; $j++) {	?>
			<img src="../media/com_yoorecipe/images/star-icon.png" title="<?php echo $item->note ?>"/>
		<?php } ?></td>
		<td><?php
		
			if ($item->author_name != '') {
				echo '<a href="'. JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->user_id) .'">' . $item->author_name . '</a>';
			 } else { 
				echo $item->author . ' (' . JText::_('COM_YOORECIPE_NOT_REGISTERED') . ')';
			} ?>
		</td>
		<td><?php echo $item->email ?></td>
		<td><?php echo JHtml::_('jgrid.published', $item->published, $i, 'comments.'); ?></td>
		<td><?php echo JHtml::_('yoorecipeadminutils.offensive', $item->abuse, $i); ?></td>
		<td><?php echo $item->creation_date; ?></td>
	</tr>
<?php endforeach; ?>