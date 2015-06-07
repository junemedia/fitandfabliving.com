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
<form action="<?php echo JRoute::_('index.php?option=com_yoorecipe'); ?>" method="post" name="adminForm" id="adminForm">
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
		
		<table class="table table-striped" id="recipeList">
			<thead>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JText::_('COM_YOORECIPE_YOORECIPE_HEADING_ID'); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>			
				<th class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_TITLE', 'r.title', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_FEATURED', 'r.featured', $listDirn, $listOrder); ?>
				</th>
				<th class="wrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_CATEGORY', 'c.title', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_CREATION_DATE', 'r.creation_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_CREATED_BY', 'author_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_DIFFICULTY', 'r.difficulty', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_COST', 'r.cost', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_PREPARATION_TIME', 'r.preparation_time', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_COOK_TIME', 'r.cook_time', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_WAIT_TIME', 'r.wait_time', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_PUBLISHED', 'r.published', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_VALIDATED', 'r.validated', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_NB_VIEWS', 'r.nb_views', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_GLOBAL_NOTE', 'r.note', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_YOORECIPE_YOORECIPE_HEADING_PICTURE', 'r.picture', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'r.language', $listDirn, $listOrder); ?>
				</th>	
			</thead>
			<tfoot><td colspan="19"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
			<tbody>
			<?php foreach($this->items as $i => $item): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php echo $item->id; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="nowrap">
						<a href="<?php echo JRoute::_( 'index.php?option=com_yoorecipe&task=yoorecipe.edit&id='.$item->id ); ?>"><?php echo $item->title; ?></a>
						<p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
					</td>
					<td class="nowrap hidden-phone"><?php echo JHtml::_('yoorecipeadminutils.featured', $item->featured, $i, true); ?></td>
					<td class="nowrap hidden-phone">
						<?php 
						$titles = array();
						foreach ($item->categories as $category) :
							$titles[] =  $category->title;
						endforeach;
						echo  implode(',',$titles); 
						?>
					</td>
					<td class="center hidden-phone">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo $item->creation_date; ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo $item->author_name; ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php
						for ($j = 0 ; $j < $item->difficulty; $j++) {
					?>
						<img src="../media/com_yoorecipe/images/star-icon.png"/>
					<?php
						}
					?>
					</td>
					<td class="nowrap hidden-phone">
						<?php
						for ($j = 0 ; $j < $item->cost; $j++) {
					?>
						<img src="../media/com_yoorecipe/images/star-icon.png"/>
					<?php
						}
					?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->preparation_time);	?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->cook_time); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('yoorecipeadminutils.formattime', $item->wait_time); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'yoorecipes.'); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('yoorecipeadminutils.validated', $item->validated, $i); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo $item->nb_views; ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo $item->note; ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php echo JHtml::_('yoorecipeadminutils.haspicture', $item->picture, $i); ?>
					</td>
					<td class="nowrap hidden-phone">
						<?php if ($item->language=='*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif;?>
					</td>
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