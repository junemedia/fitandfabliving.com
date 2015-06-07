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
global $option;
$isOldVer	= $this->isOldVer;
$lastestVer	= JRequest::getVar('lastestver','');
$upgLnk	= "index.php?option=$option&controller=upgrade&task=";
?>
	<div class="col width-40">
		<fieldset class="adminform">
			<legend>Version</legend>
			<div style="padding: 10px;">
				<p>Current : <b style="color:#444;"><?php echo JRequest::getVar('curVer','');?> 
				<?php
				if ($isOldVer) {
					echo ' <span style="color:red;padding-left:10px;"><blink> [ You are using out-of-date version! ] </blink></span></b></p>';
					echo $lastestVer?'<p>Lastest : <span style="color:#00aa00"><b>'.$lastestVer.'</b></span></p>':'';
				} else {
					if(JRequest::getVar('notCheck',false)){
						$msg	= 'Disconnect';
						$tColor	= 'ff4400';
					}elseif ($lastestVer=='0'){
						$msg	= 'Error check';
						$tColor	= 'FF4400';
					}else {
						$msg = 'Congrats, you are using Latest version';
						$tColor	= '009900';
					}
					echo '<span style="color:#'.$tColor.';padding-left:10px;"> [ '.$msg.'! ]</span></b></p>';
				}?>
			</div>
		</fieldset>
	</div>
	<div class="col width-60">
	<?php if ($isOldVer) { ?>
		<fieldset class="adminform">
		<legend> <?php echo JText::_('Upgrade to Newer Version'); ?> </legend>
			<div style="margin: 20px;">
				Click here to: 
				<a class="hasTip" title="One-Click Upgrade:: Auto get Patch package" href="<?php echo $upgLnk.'doupdate'; ?>">
				<b>UPGRADE NOW!</b></a>
			</div>
			<div style="margin: 20px;">
				<form method="post" action="<?php echo $upgLnk.'doupdate&act=ugr&type=local'; ?>" enctype="multipart/form-data">
					<span>Patch package:</span><input type="file" name="patchfile"/>
					<input type="submit" value="Upgrade"/> <span style="color:#f60;font-weight:bold;padding-left: 15px;"> ( Not recommended )</span>
				</form>
			</div>
		</fieldset>
	<?php }
	$objLogs = $this->logs;
	if($objLogs->resto){ ?>
		<fieldset class="adminform">
		<legend> <?php echo JText::_('Restore'); ?> </legend>
			<div style="margin: 20px;">
				<p><a title="Backup" href="<?php echo $upgLnk.'doupdate&act=rst&rp='.$objLogs->resto; ?>">
				<b>Turn back to previous version!</b></a><span style="color:#f60;font-weight:bold;padding-left: 15px;"> ( Not recommended )</span></p>
			</div>
		</fieldset>
	<?php }
	if($objLogs->logs){ ?>
		<fieldset class="adminform">
		<legend> <?php echo JText::_('History Update'); ?> </legend>
			<div style="margin:10px;border:1px solid #ddd;padding:10px; height: 100px;overflow: auto;background:#f8f8f8;">
		<?php
		echo '<ol>';
		foreach ($objLogs->logs as $log){
			$color = substr($log,-7)=='upgrade'?'339933':'cc5500';
			echo '<li><span style="color:#'.$color.'">'.$log.'</span><a href="index.php?option=com_foobla_upgrade&controller=upgrade&rp='.$log.'"> [ Details ... ]</a></li>';
		}
		echo '</ol>'
		?>
			</div>
		<?php if($this->report){?>
			<div style="margin:10px;border:1px solid #ddd;padding:10px; height: 300px;overflow: auto;background:#f8f8f8;">
				<?php echo $this->report;?>
			</div>
		<?php }?>
		</fieldset>
	<?php }?>
	</div>