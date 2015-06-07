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

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script','system/multiselect.js',false,true);

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe&view=comments'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
			</div>
			<div class="btn-group hidden-phone">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
			<div class="clearfix"></div>
		</div>
		
	<table class="table table-striped" id="commentList">
		<thead>
			<th width="1%" class="nowrap hidden-phone">
				<?php echo JText::_('COM_YOORECIPE_YOORECIPE_HEADING_ID'); ?>
			</th>
			<th width="1%" class="nowrap hidden-phone">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>			
			<th class="wrap">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_COMMENT', 'rat.comment', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_TITLE', 'r.title', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_NOTE', 'rat.note', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_AUTHOR', 'rat.author', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_EMAIL', 'rat.email', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_PUBLISHED', 'rat.published', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_COMMENTS_HEADING_OFFENSIVE', 'rat.abuse', $listDirn, $listOrder); ?>
			</th>
			<th class="nowrap hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_CREATION_DATE', 'rat.creation_date', $listDirn, $listOrder); ?>
			</th>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach($this->items as $i => $item): ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="nowrap hidden-phone">
					<?php echo $item->id; ?>
				</td>
				<td class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td><a href="<?php echo JRoute::_('index.php?option=com_yoorecipe&task=comment.edit&id='.$item->id) ?>"><?php echo substr($item->comment, 0, 60); if (strlen($item->comment) > 60) { echo '...'; }?></a></td>
				<td>
					<a href="<?php echo JRoute::_( 'index.php?option=com_yoorecipe&task=yoorecipe.edit&id='.$item->recipe_id ); ?>"><?php echo $item->title; ?></a>
				</td>
				<td class="nowrap hidden-phone">
				<?php for ($j = 0 ; $j < $item->note; $j++) {	?>
					<img src="../media/com_yoorecipe/images/star-icon.png" title="<?php echo $item->note ?>"/>
				<?php } ?></td>
				<td class="nowrap hidden-phone"><?php
				
					if ($item->author_name != '') {
						echo '<a href="'. JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->user_id) .'">' . $item->author_name . '</a>';
					 } else { 
						echo $item->author . ' (' . JText::_('COM_YOORECIPE_NOT_REGISTERED') . ')';
					} ?>
				</td>
				<td class="nowrap hidden-phone"><?php echo $item->email ?></td>
				<td class="nowrap"><?php echo JHtml::_('jgrid.published', $item->published, $i, 'comments.'); ?></td>
				<td class="nowrap"><?php echo JHtml::_('yoorecipeadminutils.offensive', $item->abuse, $i); ?></td>
				<td class="nowrap hidden-phone"><?php echo $item->creation_date; ?></td>
			</tr>
		<?php endforeach; ?>	
		</tbody>
	</table>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>