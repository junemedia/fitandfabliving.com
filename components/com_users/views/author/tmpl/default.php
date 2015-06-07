<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
function cutWord($str,$length)
{$str=preg_replace("/<img.*?>/si","",$str);
	if($str[0]=='"')
	{
		$str = substr($str,1,strlen($str));
	}
	
	if(strlen($str) <= $length){
		return $str;
	}else{
		//$pos = strrpos(substr($str, 0, $length) , ' ', -1);
		//return substr($str, 0, $pos) . " ...";
		return substr($str,0,$length).'...';	// we want to get fixed number of chars even if it breaks the word.
	}
}
$user    = JFactory::getUser();
$author = JFactory::getUser($this->authorid); //echo"<pre>";print_r($author);echo"</pre>";
$picture = new ProfilePicture($this->authorid);
$headimage = $picture->getURL('original');	




// Initialise the table with JUser.
//$author    = new JUser($this->authorid);
// Get the dispatcher and load the users plugins.
$dispatcher    = JEventDispatcher::getInstance();
JPluginHelper::importPlugin('user');

// Trigger the data preparation event.
$dispatcher->trigger('onContentPrepareData', array('com_users.profile', $author));



if(!$headimage)
{
	$headimage="images/headimg_reserve.png";
}	

$getapps= & JFactory::getApplication();
$template = $getapps->getTemplate();
?>
<!--<div class="profile <?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header">
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>
<?php endif; ?>
<?php if (JFactory::getUser()->id == $this->data->id) : ?>
<ul class="btn-toolbar pull-right">
	<li class="btn-group">
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
			<span class="icon-user"></span> <?php echo JText::_('COM_USERS_EDIT_PROFILE'); ?></a>
	</li>
</ul>
<?php endif; ?>
<?php echo $this->loadTemplate('core'); ?>

<?php echo $this->loadTemplate('params'); ?>

<?php echo $this->loadTemplate('custom'); ?>

</div>-->

<div class="author_box">
	<img src="<?php echo $headimage;?>" class="author_image"/>
	<div class="author_detail">
		<div class="author_name"><?php echo $author->name;?></div>
		<div class="author_title"><?php echo $author->profile['aboutme'];?></div>
	</div>
</div>
<div class="author_articles">
<?php if(!empty($this->authorArticles)) {?>
<?php foreach ($this->authorArticles as $i => $article) : ?>
	<?php if($this->authorArticles[$i]->state == 1){?>
		<div class="result_item">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><img src="<?php if(!isset($article->images['image_intro']) || empty($article->images['image_intro'])) {echo JURI::base()."templates/".$template."/images/image_reserve.png";} else {echo $article->images['image_intro'];}?>" class="result_item_img"/></a>
		<div class="result_content">
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><h2 class="result_title"><?php echo $this->escape($article->title); ?></h2></a>
			<div class="result_brief"><?php echo cutWord($article->text,130);?><br><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>" class="read_more">read more</a></div>
			<!--<div class="result_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/><font class="slash"></font> <?php echo $this->escape($this->category->title); ?></div> -->								
		</div>
		</div>	
	<?php }?>
<?php endforeach; ?>
<?php }?>
</div>
