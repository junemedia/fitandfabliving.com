<?php
/**
 * @version		$Id: default.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.mootools');
global $option;
?>
<script type="text/javascript">
<!--
function obDoSwitch(switcher){
	var el = switcher.getParent().getFirst(".ob_switch");
	if(el.value == 1){
		switcher.setProperty("class","switcher-off");
		el.value = 0;
	}else{
		switcher.setProperty("class","switcher-on");
		el.value = 1;
	}
}

window.addEvent("domready", function() {
	/* Switch buttons */
	$$(".ob_switch").each(function(el){
		el.setStyle("display","none");
		var style = (el.value == 1) ? "on" : "off";
		var switcher = new Element("div",{"class" : "switcher-"+style,"onclick":"obDoSwitch(this)"});
		switcher.injectAfter(el);
	});
});
//-->
</script>
<form action="index.php?option=<?php echo $option; ?>" method="post" name="adminForm">
	<?php
	echo $this->loadTemplate('feed');
	?>
	<input type="hidden" name="controller" value="config"/>
	<input type="hidden" name="task" value="saveConfig" />
</form>
