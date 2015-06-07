<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$getapps= & JFactory::getApplication();
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
?>

<?php foreach ($this->results as $result) : ?>
	<div class="result_item">
		<a href="<?php echo JRoute::_($result->href); ?>"><img src="<?php if(!isset($result->images['image_intro']) || empty($result->images['image_intro'])) {echo JURI::base()."templates/".$template."/images/image_reserve.png";} else {echo $result->images['image_intro'];}?>" class="result_item_img"/></a>
		<div class="result_content">
			<a href="<?php echo JRoute::_($result->href); ?>"><h2 class="result_title"><?php echo $this->escape($result->title);?></h2></a>
			<div class="result_brief"><?php echo cutWord($result->text,130);?><a href="<?php echo JRoute::_($result->href); ?>" class="read_more">read more</a></div>
			<!--<div class="result_tips"><img class="comment_img" src="<?php echo JURI::base(); ?>templates/<?php echo $template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> <?php echo $this->escape($result->section); ?></div>-->
		</div>
	</div>	

<?php endforeach; ?>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
