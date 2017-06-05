<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('mosets.profilepicture.profilepicture');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$menuItem = JSite::getMenu()->getActive();

$isPrivacy = false;
if(!empty($menuItem) && ($menuItem->route == 'privacy-policy'||$menuItem->route == 'terms-of-use'||$menuItem->route == 'aboutus'))
{
$isPrivacy = true;
}

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$canEdit = '';	// we don't want to allow users to edit recipes from front-end. Samir 9/5/13
$user    = JFactory::getUser();
//$author = JFactory::getUser($this->item->created_by); //echo"<pre>";print_r($author);echo"</pre>";
$picture = new ProfilePicture($this->item->created_by);
$headimage = $picture->getURL('200');	
if(!$headimage)
{
	$headimage="images/headimg_reserve.png";
}	
$info    = $params->get('info_block_position', 0);
$getapps= & JFactory::getApplication();
$template = $getapps->getTemplate();
$baseurl = substr(JURI::base(),0,-1);
JHtml::_('behavior.caption');
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
$authorLink = JRoute::_('index.php?option=com_users&view=author&user_name='.$this->item->authorAccount);

$isSlide = false;
if (isset ($this->item->toc)){
	$isSlide = true;
}
?>
<div class="item-page<?php echo $this->pageclass_sfx?>">
        <?php if ($params->get('show_category')) : ?>
            <div class="breadcrumb">
                <?php 
                    $title = $this->escape($this->item->category_title); 
                    $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';
                ?>
                <?php if ($params->get('link_category') && $this->item->catslug) : ?>
				<a href="<?php echo JURI::base();?>" class="bread_home">HOME</a> / 
				<span><?php echo $url;?></span>
                    <?php //echo "HOME / " . $url; //echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
                <?php endif; ?>
            </div>
         <?php endif; ?>
<div class="article">
<div id="article_details" class="<?php echo $isSlide?'article_slideshow':'';?>"> 
	<?php if ($this->params->get('show_page_heading') && $params->get('show_title')) : ?>
	<!--<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>-->
	<?php endif;
if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
{
	echo $this->item->pagination;
}
?>
	<?php if ($params->get('show_title') || $params->get('show_author')) : ?>    

		
			<?php if ($this->item->state == 0) : ?>
				<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
			<?php endif; ?>
			<?php if ($params->get('show_title')) : ?>
				<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
					<a href="<?php echo $this->item->readmore_link; ?>">
						<h1 class="article_title"><?php echo $this->escape($this->item->title); ?></h1>					
					</a>
					<h3 class="article_subtitle"><?php echo $this->escape($this->item->subtitle);?></h3>	
					<?php if($this->item->sponsored == 1 && !$isSlide){?>
					<div><font class="sponsored" size='1'>Sponsored</font></div>
					<?php }?>
				<?php else : ?>
					<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			<?php endif; ?>				
	<?php endif; ?>
	<?php if (!$this->print) : ?>
		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
			<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
			<ul class="dropdown-menu actions">
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php else : ?>
		<div class="pull-right">
		<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
		</div>
	<?php endif; ?>
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author')); ?>
	<?php if ($useDefList && ($info == 0 || $info == 2) && !$isPrivacy) : ?>
		<?php if($isSlide && $this->item->sponsored == 1){ echo '';}else{?>
		<div class="author_info">
			
			<?php if (!empty($this->item->author )) : ?>
			<img style="width:66px;height:66px;" src="<?php echo $headimage; ?>"/>
					<?php $author = $this->item->author;//$this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
					<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
						<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
						?>
						<?php //echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
						<span>by </span> <a href="<?php echo $authorLink;?>"><span class="name"><?php echo $author; ?></span></a>
					<?php else: ?>
						<?php //echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
						<span>by </span> <a href="<?php echo $authorLink;?>"><span class="name"><?php echo $author; ?></span></a>
					<?php endif; ?>
			<?php endif; ?>
			<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
				<dd class="parent-category-name">
					<?php $title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>


			<?php if ($params->get('show_publish_date')) : ?>
				<dd class="published">
					<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
			<?php endif; ?>

			<?php if ($info == 0) : ?>
				<?php if ($params->get('show_modify_date')) : ?>
					<dd class="modified">
						<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_create_date')) : ?>
					<dd class="create">
						<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
					</dd>
				<?php endif; ?>

				<?php if ($params->get('show_hits')) : ?>
					<dd class="hits">
						<span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
					</dd>
				<?php endif; ?>
			<?php endif; ?>
			<!--<div class="article_social"><img src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/article_social.jpg"/></div>-->
		</div>
		<?php }?>
	<?php endif; ?>
<div class="share_recipe">
		<!--<div class="share_title"><h2>Share Article</h2></div>-->
		<?php if($this->item->sponsored == 1 && $isSlide){?>
			<div class="sponsored"><font class="sponsored" size='1'>Sponsored</font></div>
		<?php }?>
		<div class="share_icon">
			<ul>
				<li><a href="http://www.facebook.com/sharer.php?u=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank"><img src="<?php echo $baseurl."/templates/".$template."/images/FFL-facebook-box.png";?>"/></a></li>
				<li><a href="https://twitter.com/share?original_referer=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank"><img src="<?php echo $baseurl."/templates/".$template."/images/FFL-twitter-box.png";?>"/></a></li>
				<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $baseurl.$this->item->readmore_link;?>&media=<?php echo $baseurl.'/'.htmlspecialchars($images->image_fulltext);?>" target="_blank"><img src="<?php echo $baseurl."/templates/".$template."/images/FFL-pinterest-box.png";?>"/></a></li>
				<li><a href="https://plus.google.com/share?url=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank"><img src="<?php echo $baseurl."/templates/".$template."/images/FFL-googleplus-box.png";?>"/></a></li>
				<li> <a href="<?php echo $baseurl.$this->item->readmore_link;?>?tmpl=component&amp;print=1&amp;layout=default&amp;page=" title="Print" onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;" rel="nofollow"><img src="<?php echo $baseurl."/templates/".$template."/images/FFL-print-box.png";?>"/></a></li>
			</ul>
			<script type="text/javascript">var switchTo5x=false;</script>
			<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
			<script type="text/javascript">stLight.options({publisher:'0541fe9f-2a3f-4c01-ac74-8f02c84e7fde'});</script>
		</div>
</div>

<div <?php echo $isSlide?'class="s_line"':''?> style="clear:both;"></div>
	<?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<div class="article_content">
	<?php if ($params->get('access-view')):?>
	<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
	<?php if ($images->image_fulltext_caption):
		echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) . '"';
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/> </div>
	<?php endif; ?>
	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
		echo $this->item->pagination;
	endif;
	?>
	<?php if (isset ($this->item->toc)) :
		echo $this->item->toc;
	endif; ?>
	
    
    <?php
        //print_r($this->item->pageImage);
		$slideImages = $this->item->pageImage->images;
		$page = $this->item->pageImage->page;
    ?>
    
		<!--<img src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/article_img.jpg" />-->
		<div class="article_content_detail">
			<?php if($isSlide){
				$itemIds = array(8513);
			?>
				<?php if(in_array($this->item->id,$itemIds)) {?>
					<div style="text-align:center;"><img style="<?php if($this->item->id==8513 && $page !=6){echo 'width:500px;';}?>" src="<?php echo $slideImages[$page];?>" /></div>
				<?php }else{?>
				<div style="text-align:center;"><img src="<?php echo $slideImages[$page];?>" /></div>
				<?php }?>
				
                 <?php 
                     echo $this->removeSlideImage($this->item->text);
			     }else{ 
                     echo $this->item->text;
                 }
                 ?>
		</div>
	</div>	

	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<div class="article-info muted">
			<dl class="article-info">
			<dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>

			<?php if ($info == 1) : ?>
				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
					<dd class="createdby">
						<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
						<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
						<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
						?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
						<?php else: ?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
					<dd class="parent-category-name">
						<?php	$title = $this->escape($this->item->parent_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
						<?php if ($params->get('link_parent_category') && $this->item->parent_slug) : ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
						<?php else : ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_category')) : ?>
					<dd class="category-name">
						<?php 	$title = $this->escape($this->item->category_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
						<?php if ($params->get('link_category') && $this->item->catslug) : ?>
							<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
						<?php else : ?>
							<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_publish_date')) : ?>
					<dd class="published">
						<span class="icon-calendar"></span>
						<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
					</dd>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($params->get('show_create_date')) : ?>
				<dd class="create">
					<span class="icon-calendar"></span>
					<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_modify_date')) : ?>
				<dd class="modified">
					<span class="icon-calendar"></span>
					<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_hits')) : ?>
				<dd class="hits">
					<span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
				</dd>
			<?php endif; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php
if (!empty($this->item->pagination) &&!$isPrivacy && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative && !$isSlide):
	echo $this->item->pagination;
?>
	<?php endif; ?>
	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JUri($link1);?>
	<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs); ?>
		<?php
		if ($attribs->alternative_readmore == null) :
			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $this->item->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		else :
			echo JText::_('COM_CONTENT_READ_MORE');
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif; ?>
		</a>
	</p>
	<?php endif; ?>
	<?php endif; ?>
	<?php
if (!empty($this->item->pagination) &&!$isPrivacy && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative && !$isSlide) :
	echo $this->item->pagination;
?>
	<?php endif; ?>
</div>

<?php if(!$isPrivacy){?>
<div id="more_articles">
<h2 class="content_h2">MORE FROM <?php echo $title;?></h2>
<div class="s_line"></div>
        <?php 
		$i = 0;
		foreach($this->moreList as $moreItem){?>
		<div class="article_box <?php echo $i!=0?"box_top_space":""; $i++;?>">
			<img src="<?php if(!isset($moreItem->images['image_intro']) || empty($moreItem->images['image_intro'])) {echo JURI::base()."templates/".$template."/images/image_reserve.png";} else {echo $moreItem->images['image_intro'];}?>" class="article_box_img"/>
			<div class="article_box_content">
				<a href="<?php echo $moreItem->readmore_link;?>"><h2 class="article_box_title"><?php echo $moreItem->title;?></h2></a>
				<div class="article_box_brief"><?php echo getplaintextintrofromhtml($moreItem->text,120);?> <a href="<?php echo $moreItem->readmore_link;?>" class="read_more">read more</a></div>								
			</div>
		</div>
        <?php }?>		
</div>
<?php }?>
</div>
	<?php echo $this->item->event->afterDisplayContent; ?> </div>
