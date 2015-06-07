<?php
/**
 * @version		$Id: feedburner.php 732 2013-07-22 08:53:07Z tsvn $
 * @package	foobla RSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author: foobla.com
 * @license: GNU/GPL, see LICENSE
 */
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
global $option, $obRootSite, $mainframe;
$link_feed = $obRootSite.'index.php?option='.$this->option.'&amp;task=feed&amp;id='.$this->cid;
$link_save = 'index.php?option='.$option.'&controller='.$this->controller;$link_save .= '&task=save_fb&cid='.$this->cid.'&tmpl=component';
?>
<script type='text/javascript'>
<!--
function notEmpty(uri)
{
	if (uri.value.length == 0) {
		alert('<?php echo JText::_('OBRSS_FEEDBURNER_MSG_INPUTURI'); ?>');
		uri.focus();
		return false;
	} else {
		reloadPage();
		//form.submit();
	}
}
var ogb_reload = 0;
function ob_close(ogb_reload){
	var ogb_pr	= window.parent;
	var fb = ogb_pr.document.getElementById('foobla');
	if(!fb){
		location = 'index.php?option=com_obrss&controller=feed';
	}
	if(ogb_reload==1){
		var msg = '<h4 style="color:#009900;">Reloading ...</h4>';
			msg = '<div align="center">'+msg+'</div>';
		ogb_pr.document.getElementById('foobla').innerHTML = msg;
		ogb_pr.location.reload();
	}
	ogb_pr.SqueezeBox.close();
}
</script>
<form action="<?php echo $link_save;?>" method="post" name="fb_form">
<div class="configuration" style="font-size: 2em;">FeedBurner Integration</div>
<fieldset class="adminform">
	<legend>[ <?php if($this->task == "add_fb") echo JText::_('OBRSS_FEEDBURNER_BURN'); else echo JText::_('OBRSS_FEEDBURNER_LEDEND_EDIT');?> ]</legend>
	<ul class="config-option-list">
		<li>
			<label id="" for="">1. <?php echo JText::_('OBRSS_FEEDBURNER_COPY'); ?></label>
			<input type="text" name="feed_name" disabled size="100" value="<?php echo $link_feed; ?>" />
		</li>
		<li style="clear: left;">
			<label id="" for="">2. <?php echo JText::_('OBRSS_FEEDBURNER_LOGIN'); ?></label>
		</li>
		<li style="clear: left;">
			<label id="" for="">3. <?php echo JText::_('OBRSS_FEEDBURNER_BURNFEED'); ?></label>
		</li>
		<li style="clear: left;">
			<label id="" for="">4. <?php echo JText::_('OBRSS_FEEDBURNER_COPYURI'); ?> <i>http://feeds.feedburner.com/</i></label>
			<input type="text" name="uri" size="20" value="<?php echo $this->uri;?>" />
		</li>
		<li style="clear: left;">
			<label id="" for="">5. <?php echo JText::_('OBRSS_FEEDBURNER_DONE'); ?></label>
			<input class="submit" type="submit" value="<?php echo JText::_('SAVE'); ?>"/>
			<input class="submit" type="submit" value="<?php echo JText::_('CLOSE'); ?>" onclick="ob_close(0);"/>
			<input class="submit" type="submit" value="Close & Reload" onclick="ob_close(1);"/>
		</li>
	</ul>
</fieldset>
</form>
