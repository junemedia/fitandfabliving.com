<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_wrapper
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<script type="text/javascript">
function iFrameHeight()
{
	var h = 0;
	if (!document.all)
	{
		h = document.getElementById('blockrandom').contentDocument.height;
		document.getElementById('blockrandom').style.height = h + 60 + 'px';
	} else if (document.all)
	{
		h = document.frames('blockrandom').document.body.scrollHeight;
		document.all.blockrandom.style.height = h + 20 + 'px';
	}
}
</script>
<div class="contentpane<?php echo $this->pageclass_sfx; ?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php if ($this->escape($this->params->get('page_heading'))) :?>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		<?php else : ?>
			<?php echo $this->escape($this->params->get('page_title')); ?>
		<?php endif; ?>
	</h1>
<?php endif; ?>
<?php
// quick work around, not the best way, but it works and does the job...
$unsub_url_querystring = "";
if (strstr($this->wrapper->url,"http://fitfab.popularliving.com/subctr/unsub/ff/entry.php")) {
	$unsub_url_querystring = "?listid=".trim($_GET['lid'])."&jobid=".trim($_GET['jid'])."&email=".trim($_GET['e']);
}
?>
<iframe <?php echo $this->wrapper->load; ?>
	id="blockrandom"
	name="iframe"
	src="<?php echo $this->escape($this->wrapper->url); echo $unsub_url_querystring; if (isset($_GET['guid'])) { echo (strpos($this->wrapper->url,"?")) ? "&" : "?";echo "guid=".trim($_GET['guid']); } ?>"
	width="<?php echo $this->escape($this->params->get('width')); ?>"
	height="<?php echo $this->escape($this->params->get('height')); ?>"
	scrolling="<?php echo $this->escape($this->params->get('scrolling')); ?>"
	frameborder="<?php echo $this->escape($this->params->get('frameborder', 1)); ?>"
	class="wrapper<?php echo $this->pageclass_sfx; ?>">
	<?php echo JText::_('COM_WRAPPER_NO_IFRAMES'); ?>
</iframe>
</div>
