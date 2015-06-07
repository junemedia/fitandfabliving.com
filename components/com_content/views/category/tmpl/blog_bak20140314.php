<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.caption');
function cutWord($str,$length)
{	
	$str=preg_replace("/<img.*?>/si","",$str);
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
$getapps= & JFactory::getApplication();
$template = $getapps->getTemplate();

$subCatCount = count($this->children[$this->category->id]); 
?>
<div class="blog<?php echo $this->pageclass_sfx;?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<!--<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>-->
	<?php endif; ?>
<?php if (empty($this->items) && $subCatCount == 0) { ?>
	<p>There are no articles in this category at this time. </p>
<?php }else{?>
<div id="news">
	
	<?php if(!empty($this->newItems)){?>
	<div class="section_title"><h1 class="content_h2 f_left" >WHAT'S NEW IN <?php echo $this->category->title;?></h1></div>
	<div id="section_news_boxes" class="whats_new">	
	<?php $i = 1; 	
	foreach($this->newItems as $item) {
	if($i==5)break;?>	
		<div id="box_<?php echo $i;?>" class="box">
			<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="section_box_img"/></a>
			<div class="wrap_box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
			<?php if($item->sponsored == 1){?>
				<br><font class="sponsored" size='1'>Sponsored</font>
			<?php }?>
			</div>
		</div>
	<?php 	$i++;}?>
	</div>
	<?php }?>
</div>

<iframe frameborder="0" scrolling="No" width="638" height="67" src="/signup/index.php" style="margin-top:15px;"></iframe>

<?php 
$sectionNum = 1;
if ($subCatCount > 0 && $this->maxLevel != 0) { ?>
	<?php foreach ($this->children[$this->category->id] as $id => $child) {?>
	<?php if (!empty($this->childArticles[$child->id]['articles'])){
if($sectionNum%2){	?>
<div class="long_line"></div>
<div id="work_out">
	<div class="section_title">
		<h1 class="content_h2 f_left" >
			<a href="<?php echo $this->childArticles[$child->id]['cat_link'];?>"><?php echo $this->escape($child->title); ?></a>
		</h1>
		<a class="see_all" href="<?php echo $this->childArticles[$child->id]['cat_link'];?>">See all  &gt;&gt;</a>
	</div>
	<div class="work_out_box">
		<div class="box_left">
		<?php foreach($this->childArticles[$child->id]['articles'] as $item){?>
			<div class="workout_item">
				<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>"/></a>
				<div class="workout_item_content">
					<a href="<?php echo $item->readmore_link;?>"><h2 class="workout_box_title"><?php echo $this->escape($item->title);?></h2></a>
					<?php if($item->sponsored == 1){?>
						<font class="sponsored" size='1'>Sponsored</font>
					<?php }?>
				</div>
			</div>
		<?php }?>
		</div>
		<div class="box_right">
		<?php if(!empty($this->childArticles[$child->id]['featured'])){?>
			<a href="<?php echo $this->childArticles[$child->id]['featured'][0]->readmore_link;?>"><img style="width:305px;height:235px;" src="<?php echo $this->childArticles[$child->id]['featured'][0]->showImage;?>"/></a>	
			<div class="workout_big">
				<a href="<?php echo $this->childArticles[$child->id]['featured'][0]->readmore_link;?>"><h2 class="workout_big_title"><?php echo $this->escape($this->childArticles[$child->id]['featured'][0]->title);?></h2></a>
				<?php if($this->childArticles[$child->id]['featured'][0]->sponsored == 1){?>
						<font class="sponsored" size='1'>Sponsored</font>
				<?php }?>
			</div>
		<?php }?>
		</div>
	</div>	
</div>
	<?php }
	else{ ?>
	<div class="long_line"></div>
	<div id="news">	
		<div class="section_title">
			<h1 class="content_h2 f_left" >
				<a href="<?php echo $this->childArticles[$child->id]['cat_link'];?>"><?php echo $this->escape($child->title); ?></a>
			</h1>
			<a class="see_all" href="<?php echo $this->childArticles[$child->id]['cat_link'];?>">See all  &gt;&gt;</a>
		</div>
		<div id="section_news_boxes">	
		<?php $i = 1; 	
		foreach($this->childArticles[$child->id]['articles'] as $item) {
		if($i==4)break;?>	
			<div id="box_<?php echo $i;?>" class="box">
				<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="section_box_img"/></a>
				<div class="wrap_box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
				<?php if($item->sponsored == 1){?>
					<font class="sponsored" size='1'>Sponsored</font>
				<?php }?>
				</div>
			</div>
		<?php 	$i++;}?>
		</div>
	</div>
	<?php }
		$sectionNum++;
		}?>
	<?php }?>	
<?php } ?>	

<?php if($this->category->id == 178){?>
<style>
.result_item{
float:left;
}
</style>
<div class="long_line"></div>
<div class="category_list">
	<div class="section_title"><h1 class="content_h2 f_left" ><a href="/lifestyle">Lifestyle</a></h1></div>
</div>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
<?php foreach ($this->items as $i => $article) { ?>
	<?php if($this->items[$i]->state == 1){?>
		<div class="result_item">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
        
            <?php //print_r($article);?>
            <!-- Display the intro image or the fulltext image -->
            <img src="<?php 
                    if(isset($article->images['image_intro']) && trim($article->images['image_intro']) != "" ) {
                        echo $article->images['image_intro'];
                    } else if(isset($article->images['image_fulltext']) ) {
                        echo $article->images['image_fulltext'];
                    }else{
                         echo JURI::base()."templates/".$template."/images/image_reserve.png";
                    }
            ?>" class="result_item_img"/></a>
            
            <!-- Display image end -->
            
		<div class="result_content">
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><h2 class="result_title"><?php echo $this->escape($article->title); ?></h2></a>
			<div class="result_brief"><?php echo cutWord($article->text,130);?><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>" class="read_more">read more</a></div>
			<?php if($article->sponsored == 1){?>
				<div><font class="sponsored" size='1'>Sponsored</font></div>
			<?php }?>
			<!--<div class="result_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $this->escape($this->category->title); ?></div>-->
		</div>
		</div>	
	<?php }?>
<?php } ?>

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
<?php endif; ?>
</form>
<!--<div class="category_list">
	<div class="section_title"><h2 class="content_h2 f_left" >Categories</h2></div>
	<ul class="parent_ul">
	<?php if(!empty($this->categories)){
		foreach($this->categories as $parent)
		{?>
		<li class="parent_li"><a href="<?php echo $parent->link;?>"><?php echo $parent->title;?></a>
			<?php if(!empty($parent->children))
			{?>
				<ul class="child_ul">
				<?php foreach($parent->children as $child)
					{?>
					<li class="child_li"><a href="<?php echo $child->link;?>"><?php echo $child->title;?></a></li>
				<?php }?>
				</ul>
			<?php }?>
		</li>						
		<?php }?>
	
	<?php }?>
	</ul>
</div>-->
<?php }?>
	<?php }?>
</div>
