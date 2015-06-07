<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<!--<ul class="relateditems<?php echo $moduleclass_sfx; ?>">-->
<div class="related_article">
						<h2 class="content_h2">RELATED ARTICLES</h2>
						<div class="s_line" style="width:290px"></div>
						<div class="related_detail">
<?php foreach ($list as $item) :	//echo "<pre>";print_r($item);echo "</pre>";?>
<?php $artimg = "";
if(isset($item->images["image_intro"]) && $item->images["image_intro"] != "")
{
	$artimg = $item->images["image_intro"];
}
else
{
	$artimg = "images/relate_reserve.png";
}
?>
<div class="article_item">
	<a href="<?php echo $item->route; ?>"><img src="<?php echo $artimg;?>"/>
	<span><?php echo $item->title; ?></span></a>
</div>
<div class="grey_line"></div>
<!--<li>
	<a href="<?php echo $item->route; ?>">
		<?php if ($showDate) echo JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC4')). " - "; ?>
		<?php echo $item->title; ?></a>
</li>-->
<?php endforeach; ?>
</div>
					</div>
<!--</ul>-->
