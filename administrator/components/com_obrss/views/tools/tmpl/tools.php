<?php 
/**
 * @package	foobla RSS Feed Creator for Joomla.
 * @subpackage: install.jlord_rss.php
 * @created: Setember 2008.
 * @updated: 2009/06/30
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author	foobla
 * @license	GNU/GPL, see LICENSE
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
jimport('joomla.html.pane');
global $isJ25;
$db = &JFactory::getDBO();
$option	= JRequest::getCmd( 'option' );
JHTML::_('behavior.tooltip');
//Artio JoomSEF
if ($this->install_pluginArtio == 'install') {
	$title_artio 			= JText::_('INSTALL_PLUGINARTIO');
	$title_artio_des 		= JText::_('INSTALL_PLUGINARTIO_DES');
	$image_artio 			= 'install.png';
} else {
	$title_artio		    = JText::_('UNINSTALL_PLUGINARTIO');
	$title_artio_des		= JText::_('UNINSTALL_PLUGINARTIO_DES');
	$image_artio 			= 'uninstall.png';
}
//sh404SEF
if ($this->install_pluginSh404sef == 'install') {
	$title_sh404sef	        = JText::_('INSTALL_PLUGINSH404SEF');
	$title_sh404sef_des	    = JText::_('INSTALL_PLUGINSH404SEF_DES');
	$image_sh404sef 		= 'install.png';
} else {
	$title_sh404sef	        = JText::_('UNINSTALL_PLUGINSH404SEF');
	$title_sh404sef_des	    = JText::_('UNINSTALL_PLUGINSH404SEF_DES');
	$image_sh404sef 		= 'uninstall.png';
}
if ($this->install_pluginAcesef == 'install') {
	$title_acesef 			= JText::_('INSTALL_PLUGINACESEF');
	$title_acesef_des 		= JText::_('INSTALL_PLUGINACESEF_DES');
	$image_acesef			= 'install.png';
} else {
	$title_acesef		    = JText::_('UNINSTALL_PLUGINACESEF');
	$title_acesef_des		= JText::_('UNINSTALL_PLUGINACESEF_DES');
	$image_acesef			= 'uninstall.png';
}
//Plugin load feed
if ($this->install_plugin_load == 'install') {
	$title_load 			= JText::_('INSTALL_LOAD');
	$title_load_des 		= JText::_('INSTALL_LOAD_DES');
	$image_load 			= 'install.png';
} else {
	$title_load		      	= JText::_('UNINSTALL_LOAD');
	$title_load_des			= JText::_('UNINSTALL_LOAD_DES');
	$image_load 			= 'uninstall.png';
}
//Plugin Live-feed-icon
if ($this->install_plugin_live == 'install') {
	$title_live 			= JText::_('INSTALL_LIVE');
	$title_live_des 		= JText::_('INSTALL_LIVE_DES');
	$image_live 			= 'install.png';
} else {
	$title_live		      	= JText::_('UNINSTALL_LIVE');
	$title_live_des			= JText::_('UNINSTALL_LIVE_DES');
	$image_live 			= 'uninstall.png';
}
//Module Jlord RSS
if ($this->install_module == 'install') {
	$title_mod 			= JText::_('INSTALL_MODULE');
	$title_mod_des 		= JText::_('INSTALL_MODULE_DES');
	$image_mod			= 'install.png';
} else {
	$title_mod		    = JText::_('UNINSTALL_MODULE');
	$title_mod_des		= JText::_('UNINSTALL_MODULE_DES');
	$image_mod 			= 'uninstall.png';
}
//Joomfish element
if ($this->install_joomfish == 'install'){
	$title_jf			= JText::_('INSTALL_JOOMFISH');
	$title_jf_des		= JText::_('INSTALL_JOOMFISH_DES');
	$image_jf			= 'install.png';
} else {
	$title_jf			= JText::_('UNINSTALL_JOOMFISH');
	$title_jf_des		= JText::_('UNINSTALL_JOOMFISH_DES');
	$image_jf			= 'uninstall.png';
}
if(file_exists(JPATH_BASE.DS.'components/com_sef/classes/config.php')){
	include_once(JPATH_BASE.DS.'components/com_sef/classes/config.php');
	$sefConfig = SEFConfig::getConfig();
	$Enabled1 = $sefConfig->enabled;
}
if (file_exists(JPATH_BASE.DS.'components/com_sh404sef/config/config.sef.php')) {
	include_once(JPATH_BASE.DS.'components/com_sh404sef/config/config.sef.php');
}
$JFDir	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS;
if(is_dir($JFDir)){
	$Enabled2 = true;
} else $Enabled2 = false;
if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef')){
	/*#include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'configuration.php');
	include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesef'.DS.'script.acesef.php');
	#$abc = new acesef_configuration;
	$abc = new com_acesefInstallerScript;*/
	# we need to get this value from params in _extensions table
	$Enabled3 = true;
} else {
	$Enabled3 = false;
}
$Enabled  = isset($Enabled) ? $Enabled : '';
$Enabled1 = isset($Enabled1)? $Enabled1: '';
$Enabled3 = isset($Enabled3)? $Enabled3: '';
/*
$sign = JRequest::getVar('sign');
$tmp = '';
if($sign == 'artio'){
	$tmp = 0; 
} else if($sign == 'sh404'){
	$tmp = 1; 
} else if($sign == 'mod'){
	$tmp = 2; 
} else if($sign == 'live'){
	$tmp = 3; 
}  else if($sign == 'load'){
	$tmp = 4; 
} else {
	$tmp = 0;
	$sign = 'artio';
} */
 ?>
<!--<script type="text/javascript">
	window.addEvent('load',function(){
	document.getElementById('<?php echo $tmp+1;?>').addClass('open');
	<?php if($sign != 'artio'){?>
	document.getElementById('1').addClass('closed').removeClass('open');
	<?php } ?>
	for(var i = 0; i < document.getElementsByTagName('dd').length; i ++ )
	{
		document.getElementsByTagName('dd')[i].setStyle('display','none');
	}
	if(document.getElementsByTagName('dd').length == 6) {
	  	document.getElementsByTagName('dd')[<?php echo $tmp+1;?>].setStyle('display','block');
	} else {
		document.getElementsByTagName('dd')[<?php echo $tmp;?>].setStyle('display','block');
	}
	});
</script>-->
<fieldset class="adminform">
	<legend><?php echo JText::_( 'ARTIO'); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
	<?php
	if ($isJ25) {
		$query = "SELECT COUNT(*) FROM `#__components` WHERE `link`='option=com_sef' AND `option`='com_sef'";
	} else {
		$query = "SELECT COUNT(*) FROM `#__extensions` WHERE `element`='com_sef' AND `type`='component'";
	}
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	if($rows){
			if($Enabled1 == 1 ){?>
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_pluginArtio;?>_pluginArtio">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_artio;?>" alt="<?php echo $this->install_pluginArtio;?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_artio_des;?></span>
			</td>
		</tr>
		<?php } else {?>
		<tr>
			<td colspan="2">
			   <?php echo JText::_( 'ARTIO_DIS' ); ?>
			    <br/>
			   <?php $path = JRoute::_('index.php?option=com_sef&controller=config&task=edit');?>
			   <a href="<?php echo $path; ?>" target="_blank"> <?php echo JText::_('ARTIO_CONFIG');?></a>
			</td>
		</tr>
		<?php }
	} else { echo JText::_('ARTIO_U'); }?>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'SH404SEF' ); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
	<?php
	if ($isJ25) {
		$query = "SELECT COUNT(*) FROM #__components WHERE `link` = 'option=com_sh404sef' AND `option` = 'com_sh404sef'";
	} else {
		$query = "SELECT COUNT(*) FROM `#__extensions` WHERE `element`='com_sh404sef' AND `type`='component'";
	}
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	if($rows){
	if($Enabled == 1 ){?>
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_pluginSh404sef;?>_pluginSh404sef">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_sh404sef;?>" alt="<?php echo $this->install_pluginSh404sef;?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_sh404sef_des;?></span>
			</td>
		</tr>
	<?php  } else {?>
		<tr>
			<td colspan="2">
			   <?php echo JText::_('SH404SEF_DIS'); ?>
			    <br/>
			   <?php $path1 = JRoute::_('index.php?option=com_sh404sef&task=showconfig');?>
			   <a href="<?php echo $path1; ?>" target="_blank"> <?php echo JText::_('SH404SEF_CONFIG');?></a>
			</td>
		</tr>
	<?php  }
	} else { ?>
		<tr>
			<td colspan="2">
			   <?php echo JText::_('SH404SEF_U'); ?>
			</td>
		</tr>
	<?php }?>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'ACESEF'); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
	<?php
	if ($isJ25) {
		$query = "SELECT COUNT(*) FROM #__components WHERE `link` = 'option=com_acesef' AND `option` = 'com_acesef'";
	} else {
		$query = "SELECT COUNT(*) FROM `#__extensions` WHERE `element`='com_acesef' AND `type`='component'";
	}
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	if($rows){
			if($Enabled3 == 1 ){?>
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_pluginAcesef;?>_pluginAcesef">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_acesef;?>" alt="<?php echo $this->install_pluginAcesef;?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_acesef_des;?></span>
			</td>
		</tr>
		<?php } else {?>
		<tr>
			<td colspan="2">
			   <?php echo JText::_( 'ACESEF_DIS' ); ?>
			    <br/>
			   <?php $path = JRoute::_('index.php?option=com_acesef&controller=config&task=edit');?>
			   <a href="<?php echo $path; ?>" target="_blank"> <?php echo JText::_('ACESEF_CONFIG');?></a>
			</td>
		</tr>
		<?php }
	} else { echo JText::_('ACESEF_U'); }?>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_($title_mod); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_module;?>_module">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_mod;?>" alt="<?php echo $this->install_module; ?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_mod_des;?></span>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( $title_live ); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_plugin_live;?>_plugin_live">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_live;?>" alt="<?php echo $this->install_plugin_live; ?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_live_des;?></span>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( $title_load ); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_plugin_load;?>_plugin_load">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_load;?>" alt="<?php echo $this->install_plugin_load; ?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_load_des;?></span>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( $title_jf ); ?></legend>
	<table class="admintable" cellpadding="0" cellpadding="0" border="0" width="100%">
	<?php if($Enabled2) {?>
		<tr>
			<td colspan="2">
				<a href="index.php?option=com_obrss&controller=tools&task=<?php echo $this->install_joomfish;?>_joomfish">
					<img src="components/<?php echo $option; ?>/assets/images/icons/<?php echo $image_jf;?>" alt="<?php echo $this->install_joomfish; ?>" width="32" height="32" align="middle" />
				</a> 
				<span style=""><?php echo $title_jf_des;?></span>
			</td>
		</tr>
	<?php } else {?>
		<tr>
			<td colspan="2">
				<?php echo JText::_('JOOMFISH_U');?>
			</td>
		</tr>
	<?php }?>
	</table>
</fieldset>
 
