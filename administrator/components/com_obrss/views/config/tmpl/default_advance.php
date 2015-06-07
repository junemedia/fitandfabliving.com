<?php
/**
 * @version		$Id: default_advance.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	$('hide_product').addEvent('click', function(e){
		document.getElementById('show_cpanel_info0').checked	= 'checked';
		document.getElementById('show_config0').checked			= 'checked';
		return;
	});
	$('show_product').addEvent('click', function(e){
		document.getElementById('show_cpanel_info1').checked 	= 'checked';
		document.getElementById('show_config1').checked 		= 'checked';
		return;
	});
	$('show_all').addEvent('click', function(e){
		document.getElementById('show_config1').checked 			= 'checked';
		document.getElementById('show_cpanel1').checked 			= 'checked';
		document.getElementById('show_langs1').checked 				= 'checked';
		document.getElementById('show_items1').checked 				= 'checked';
		document.getElementById('show_tools1').checked 				= 'checked';
		document.getElementById('show_addons1').checked 			= 'checked';
		document.getElementById('show_upgrade1').checked 			= 'checked';
		document.getElementById('show_cpanel_info1').checked 		= 'checked';
		document.getElementById('show_cpanel_latestitems1').checked = 'checked';
		document.getElementById('show_cpanel_addons1').checked 		= 'checked';
		return;
	});
	$('show_items0').addEvent('click', function(e){
		document.getElementById('show_cpanel_latestitems0').checked = 'checked';
		return;
	});	      
	$('show_addons0').addEvent('click', function(e){
		document.getElementById('show_cpanel_addons0').checked = 'checked';
		return;
	});	   
	$('show_cpanel_latestitems1').addEvent('click', function(e){
		document.getElementById('show_items1').checked = 'checked';
		return;
	});	      
	$('show_cpanel_addons1').addEvent('click', function(e){
		document.getElementById('show_addons1').checked = 'checked';
		return;
	});
});
</script>
<div class="col width-40">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Simple' ); ?></legend>
		<div align="center">
			<p><input type="button" name="show_product" id="show_product" value='Show All Product Information' /></p>
			<p><input type="button" name="hide_product" id="hide_product" value='Hide All Product Information' /></p>
			<p><input type="button" name="show_all" id="show_all" value='Show me the rest of fun' /></p>
		</div>
	</fieldset>
</div>
<div class="col width-60">
    <fieldset class="adminform">
		<legend><?php echo JText::_( 'Advance' ); ?></legend>
    	<table class="admintable" width="100%">
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Backend Layout Config'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_config', '', ConfigHelper::isShow('show_config'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Control Panel'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_cpanel', '', ConfigHelper::isShow('show_cpanel'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Languages'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_langs', '', ConfigHelper::isShow('show_langs'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Tools'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_tools', '', ConfigHelper::isShow('show_tools'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Items'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_items', '', ConfigHelper::isShow('show_items'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Addons'); ?></td>
				<td width="60%">
					<?php echo JHTML::_( 'select.booleanlist', 'show_addons', '', ConfigHelper::isShow('show_addons'), 'Yes', 'No' ); ?>
				</td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show Upgrade'); ?></td>
				<td width="60%"><?php echo JHTML::_( 'select.booleanlist', 'show_upgrade', '', ConfigHelper::isShow('show_upgrade'), 'Yes', 'No' ); ?></td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show cPanel-Info'); ?></td>
				<td width="60%"><?php echo JHTML::_( 'select.booleanlist', 'show_cpanel_info', '', ConfigHelper::isShow('show_cpanel_info'), 'Yes', 'No' ); ?></td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show cPanel-Latest Items'); ?></td>
				<td width="60%"><?php echo JHTML::_( 'select.booleanlist', 'show_cpanel_latestitems', '', ConfigHelper::isShow('show_cpanel_latestitems'), 'Yes', 'No' ); ?></td>
			</tr>
			<tr>
				<td width="40%" class="key"><?php echo JText::_('Show cPanel-Addons'); ?></td>
				<td width="60%"><?php echo JHTML::_( 'select.booleanlist', 'show_cpanel_addons', '', ConfigHelper::isShow('show_cpanel_addons'), 'Yes', 'No' ); ?></td>
			</tr>
		</table>
	</fieldset>
</div>
<div style="clear: both"></div>