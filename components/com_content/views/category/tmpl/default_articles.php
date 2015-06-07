<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.framework');

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$getapps= & JFactory::getApplication();
$template = $getapps->getTemplate();
// check for at least one editable article
$isEditable = false;
if (!empty($this->items))
{
	foreach ($this->items as $article)
	{
		if ($article->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
}
function cutWord($str,$length)
{$str=preg_replace("/<img.*?>/si","",$str);
	if($str[0]=='"')
	{
		$str = substr($str,1,strlen($str));
	}
	
	if(strlen($str) <= $length){
		return $str;
	}else{
		$pos = strrpos(substr($str, 0, $length) , ' ', -1);
		return substr($str, 0, $pos) . " ... ";
		//return substr($str,0,$length).'...';	// we want to get fixed number of chars even if it breaks the word.
	}
}

?>
<div class="breadcrumb">
	<?php 
		$title = $this->escape($this->category->title); 
		$parentTitle = $this->escape($this->parent->title); 		
		$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->category->slug)) . '">' . $title . '</a>';
		$parentUrl = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->parent->slug)) . '">' . $parentTitle . '</a>';
	?>
	<?php if ($this->category->slug) : ?>
	<a href="<?php echo JURI::base();?>" class="bread_home">HOME</a> / 
	<?php if(strcasecmp($parentTitle,'root') !=0){ echo $parentUrl;?> /
		<?php }?>
	<span><?php echo $url;?></span>
	<?php else : ?>
		<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
	<?php endif; ?>
</div>
<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
	<p>There are no articles in this category at this time. <?php //echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL'); ?>" />
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
	</fieldset>
	<?php endif; ?>
<?php //print_r($this->category);//echo "<pre>";print_r($this->items);echo "</pre>";?>
<?php foreach ($this->items as $i => $article) : ?>
	<?php if($this->items[$i]->state == 1){?>
		<div class="result_item">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
        
            <?php //print_r($article);?>
            <!-- Display the intro image or the fulltext image -->
            <img src="<?php 
                    if(isset($article->images['image_fulltext']) && trim($article->images['image_fulltext']) != "" ) {
                        echo $article->images['image_fulltext'];
                    }else if(isset($article->images['image_intro']) && trim($article->images['image_intro']) != "" ) {
                        echo $article->images['image_intro'];
                    } else {
                         echo JURI::base()."templates/".$template."/images/image_reserve.png";
                    }
            ?>" class="result_item_img"/></a>
            
            <!-- Display image end -->
            
            
		<div class="result_content">
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><h2 class="result_title"><?php echo $this->escape($article->title); ?></h2></a>
			<div class="result_brief"><?php echo getplaintextintrofromhtml($article->text,130);?><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>" class="read_more">read more</a></div>
			<?php if($article->sponsored == 1){?>
				<div><font class="sponsored" size='1'>Sponsored</font></div>
			<?php }?>
			<!--<div class="result_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $this->escape($this->category->title); ?></div>-->
		</div>
		</div>	
	<?php }?>
<?php endforeach; ?>
	<!--<table class="category table table-striped table-bordered table-hover">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<?php if ($date = $this->params->get('list_show_date')) : ?>
					<th id="categorylist_header_date">
						<?php if ($date == "created") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.created', $listDirn, $listOrder); ?>
						<?php elseif ($date == "modified") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.modified', $listDirn, $listOrder); ?>
						<?php elseif ($date == "published") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.publish_up', $listDirn, $listOrder); ?>
						<?php endif; ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_author')) : ?>
					<th id="categorylist_header_author">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits')) : ?>
					<th id="categorylist_header_hits">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($isEditable) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_CONTENT_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php foreach ($this->items as $i => $article) : ?>
				<?php if ($this->items[$i]->state == 0) : ?>
				 <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else: ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<?php endif; ?>
					<td headers="categorylist_header_title" class="list-title">
						<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
							<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
								<?php echo $this->escape($article->title); ?>
							</a>
						<?php else: ?>
							<?php
							echo $this->escape($article->title).' : ';
							$menu		= JFactory::getApplication()->getMenu();
							$active		= $menu->getActive();
							$itemId		= $active->id;
							$link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);
							$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));
							$fullURL = new JUri($link);
							$fullURL->setVar('return', base64_encode($returnURL));
							?>
							<a href="<?php echo $fullURL; ?>" class="register">
								<?php echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
							</a>
						<?php endif; ?>
						<?php if ($article->state == 0) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JUNPUBLISHED'); ?>
							</span>
						<?php endif; ?>
					</td>
					<?php if ($this->params->get('list_show_date')) : ?>
						<td headers="categorylist_header_date" class="list-date small">
							<?php
							echo JHtml::_(
								'date', $article->displayDate,
								$this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))
							); ?>
						</td>
					<?php endif; ?>
					<?php if ($this->params->get('list_show_author', 1)) : ?>
						<td headers="categorylist_header_author" class="list-author">
							<?php if (!empty($article->author) || !empty($article->created_by_alias)) : ?>
								<?php $author = $article->author ?>
								<?php $author = ($article->created_by_alias ? $article->created_by_alias : $author);?>

								<?php if (!empty($article->contactid ) &&  $this->params->get('link_author') == true):?>
									<?php echo JHtml::_(
											'link',
											JRoute::_('index.php?option=com_contact&view=contact&id='.$article->contactid),
											$author
									); ?>

								<?php else :?>
									<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<?php if ($this->params->get('list_show_hits', 1)) : ?>
						<td headers="categorylist_header_hits" class="list-hits">
							<span class="badge badge-info">
								<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $article->hits); ?>
							</span>
						</td>
					<?php endif; ?>
					<?php if ($isEditable) : ?>
						<td headers="categorylist_header_edit" class="list-edit">
							<?php if ($article->params->get('access-edit')) : ?>
								<?php echo JHtml::_('icon.edit', $article, $params); ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>-->
<?php endif; ?>

<?php // Code to add a link to submit an article. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo JHtml::_('icon.create', $this->category, $this->category->params); ?>
<?php  endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>
