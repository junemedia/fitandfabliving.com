<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$class = ' class="first"';
JHtml::_('bootstrap.tooltip');
$lang	= JFactory::getLanguage();
$getapps= JFactory::getApplication();
$template = $getapps->getTemplate();
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
//if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
	<?php //foreach($this->items[$this->parent->id] as $id => $item) : ?>
		<?php
		//if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
		//if (!isset($this->items[$this->parent->id][$id + 1]))
		//{
		//	$class = ' class="last"';
		//}
		?>
<div>		
<div id="news">
	<h1 class="content_h2">WHAT'S NEW</h1>
	<div class="s_line"></div>
	<div id="news_boxes" class="whats_new">		
		<?php if(!empty($this->newItems)){?>
		<?php $i = 1; 	
		foreach($this->newItems as $item) {
		if($i==5)break;?>
				<div id="box_<?php echo $i;?>" class="box">
					<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
					<div class="box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
					<?php if($item->sponsored == 1){?>
						<br><font class="sponsored" size='1'>Sponsored</font>
					<?php }?>
					</div>
					<!--<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>-->
				</div>
		<?php 	$i++;}?>
		<?php }?>							
	</div>
</div>
<iframe frameborder="0" scrolling="No" width="638" height="67" src="/signup/index.php" style="margin-top:15px;"></iframe>
<div id="stay_fit">
	<h1 class="content_h2"><a href="/health">HEALTH</a></h1>
	<div class="s_line"></div>
	<?php if(!empty($this->health_items)){?>
		<?php $i = 1; 	
		foreach($this->health_items as $item) {
		if($i==3)break;?>
	<div class="stay_box <?php echo $i!=1?'box_top_space':'';?>">
		<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="stay_box_img"/></a>
		<div class="stay_box_content">
			<a href="<?php echo $item->readmore_link;?>"><h2 class="stay_box_title"><?php echo $this->escape($item->title);?></h2></a>
			<div class="stay_box_brief"><?php echo getplaintextintrofromhtml($item->text,120);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>
			<?php if($item->sponsored == 1){?>
				<div><font class="sponsored" size='1'>Sponsored</font></div>
			<?php }?>
			<!--<div class="stay_box_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $item->category_title;?></div>	-->							
		</div>
	</div>
	<?php 	$i++;}?>
	<?php }?>
</div>
<div id="eat_healthy">
	<h1 class="content_h2"><a href="/fitness">FITNESS</a></h1>
	<div class="s_line"></div>
	<!--<div class="eat_box">
		<ul class="article_list">
		<?php if(!empty($this->fitness_items)){?>
		<?php $i = 1; 	
		foreach($this->fitness_items as $item) {
		if($i==4)break;?>
			<li>
				<a href="<?php echo $item->readmore_link;?>"><h2><?php echo $this->escape($item->title);?></h2></a>
				<div class="article_brief"><?php echo getplaintextintrofromhtml($item->text,90);?></div>
			</li>
		<?php 	$i++;}?>
		<?php }?>								
			<li class="see_all"><a href="/index.php/fitness">SEE ALL FITNESS</a></li>
		</ul>						
		<?php if(!empty($this->fitnessimage_item)){?>
		<a href="<?php echo $this->fitnessimage_item[0]->readmore_link;?>"><img class="eat_img" src="<?php echo $this->fitnessimage_item[0]->showImage;?>"/></a>
		<?php }?>
	</div>-->
	<div id="news_boxes">		
		<?php if(!empty($this->fitness_items)){?>
		<?php $i = 1; 	
		foreach($this->fitness_items as $item) {
		if($i==4)break;?>
				<div id="box_<?php echo $i;?>" class="box">
					<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
					<div class="box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
					<?php if($item->sponsored == 1){?>
						<br><font class="sponsored" size='1'>Sponsored</font>
					<?php }?>
					</div>
					<!--<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>-->
				</div>
		<?php 	$i++;}?>
		<?php }?>							
	</div>
</div>					
<!--<div id="lose_weight">
	<h2 class="content_h2">BEAUTY</h2>
	<div class="s_line"></div>
	<div class="lose_box">		
		<?php if(!empty($this->beauty_items)){?>
		<?php $i = 1; 	
		foreach($this->beauty_items as $item) {
		if($i==4)break;?>						
		<div class="wrap_box">
			<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
			<div class="wrap_box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a></div>
			<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more...</a></div>
		</div>
		<?php 	$i++;}?>
		<?php }?>	
	</div>
</div>-->
	
<div id="feel_good">
	<h1 class="content_h2"><a href="/beauty">BEAUTY</a></h1>
	<div class="s_line"></div>
	<!-- BEGIN 3LIFT TAG -->
	<!--<script src="http://ib.3lift.com/ttj?inv_code=fitandfabliving_main_fitness"></script>-->
	<!-- END 3LIFT TAG -->
	<?php if(!empty($this->beauty_items)){?>
		<?php $i = 1; 	
		foreach($this->beauty_items as $item) {
		if($i==4)break;?>
	<div class="feel_box <?php echo $i!=1?'box_top_space':'';?>">
		<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="feel_box_img"/></a>
		<div class="feel_box_content">
			<a href="<?php echo $item->readmore_link;?>"><h2 class="feel_box_title"><?php echo $this->escape($item->title);?></h2></a>
			<div class="feel_box_brief"><?php echo getplaintextintrofromhtml($item->text,120);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>
			<?php if($item->sponsored == 1){?>
				<div><font class="sponsored" size='1'>Sponsored</font></div>
			<?php }?>
			<!--<div class="feel_box_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $item->category_title;?></div>-->
		</div>
	</div>
	<?php 	$i++;}?>
	<?php }?>	
</div>

<div id="lose_weight">
	<h1 class="content_h2"><a href="/recipes">RECIPES</a></h1>
	<div class="s_line"></div>
	<div id="news_boxes">		
		<?php if(!empty($this->recipes_items)){?>
		<?php $i = 1; 	
		foreach($this->recipes_items as $item) {
		if($i==4)break;?>
				<div id="box_<?php echo $i;?>" class="box">
					<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
					<div class="box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
					<?php if($item->sponsored == 1){?>
						<br><font class="sponsored" size='1'>Sponsored</font>
					<?php }?>
					</div>
					<!--<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>-->
				</div>
		<?php 	$i++;}?>
		<?php }?>							
	</div>
	<!--<div class="lose_box">		
		<?php if(!empty($this->recipes_items)){?>
		<?php $i = 1; 	
		foreach($this->recipes_items as $item) {
		if($i==4)break;?>						
		<div class="wrap_box">
			<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
			<div class="wrap_box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a></div>
			<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>
		</div>
		<?php 	$i++;}?>
		<?php }?>	
	</div>-->
</div>
<div id="feel_good">
	<h1 class="content_h2"><a href="/weightloss">WEIGHT LOSS</a></h1>
	<div class="s_line"></div>
	<?php if(!empty($this->weightl_items)){?>
		<?php $i = 1; 	
		foreach($this->weightl_items as $item) {
		if($i==4)break;?>
	<div class="feel_box <?php echo $i!=1?'box_top_space':'';?>">
		<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="feel_box_img"/></a>
		<div class="feel_box_content">
			<a href="<?php echo $item->readmore_link;?>"><h2 class="feel_box_title"><?php echo $this->escape($item->title);?></h2></a>
			<div class="feel_box_brief"><?php echo getplaintextintrofromhtml($item->text,120);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>
			<?php if($item->sponsored == 1){?>
				<div><font class="sponsored" size='1'>Sponsored</font></div>
			<?php }?>
			<!--<div class="feel_box_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $item->category_title;?></div>-->
		</div>
	</div>
	<?php 	$i++;}?>
	<?php }?>							
</div>

<div id="lose_weight">
	<h1 class="content_h2"><a href="/lifestyle">LIFESTYLE</a></h1>
	<div class="s_line"></div>
	<div id="news_boxes">		
		<?php if(!empty($this->lifestyle_items)){?>
		<?php $i = 1; 	
		foreach($this->lifestyle_items as $item) {
		if($i==4)break;?>
				<div id="box_<?php echo $i;?>" class="box">
					<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
					<div class="box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a>
					<?php if($item->sponsored == 1){?>
						<br><font class="sponsored" size='1'>Sponsored</font>
					<?php }?>
					</div>
					<!--<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>-->
				</div>
		<?php 	$i++;}?>
		<?php }?>							
	</div>
	<!--<div class="lose_box">		
		<?php if(!empty($this->lifestyle_items)){?>
		<?php $i = 1; 	
		foreach($this->lifestyle_items as $item) {
		if($i==4)break;?>						
		<div class="wrap_box">
			<a href="<?php echo $item->readmore_link;?>"><img src="<?php echo $item->showImage;?>" class="box_img"/></a>
			<div class="wrap_box_title"><a href="<?php echo $item->readmore_link;?>"><?php echo $this->escape($item->title);?></a></div>
			<div class="box_content"><?php //echo cutWord($item->text,40);?> <a href="<?php echo $item->readmore_link;?>" class="read_more">read more</a></div>
		</div>
		<?php 	$i++;}?>
		<?php }?>	
	</div>-->
</div>
<!--<div id="look_fab">
	<h2 class="content_h2">RECIPES</h2>
	<div class="s_line"></div>
	<div class="look_box">
	<?php if(!empty($this->recipes_items)){?>
		<?php $i = 1; 	
		foreach($this->recipes_items as $item) {
		if($i==3)break;?>
		<div class="look_box_content">
			<a href="<?php echo $item->readmore_link;?>"><img class="look_box_img" src="<?php echo $item->showImage;?>"/></a>
			<div class="look_box_detail">
				<a href="<?php echo $item->readmore_link;?>"><h2 class="look_box_title"><?php echo $this->escape($item->title);?></h2></a>
				<div class="look_box_breif"><?php echo getplaintextintrofromhtml($item->text,50);?></div>
			</div>
		</div>
		<?php 	$i++;}?>
		<?php }?>
		
	</div>
</div>-->

		<?php //endif; ?>
	<?php //endforeach; ?>
<?php //endif; ?>
