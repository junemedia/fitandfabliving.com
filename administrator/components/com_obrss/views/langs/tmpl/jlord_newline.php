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
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
?>
<script type="text/javascript">
function submitbutton(pressbutton){
	if(pressbutton=='insert_newline') {
		if(document.getElementById('jlord_character_defined').value == '') {
			alert('You can entre into least a character ');
			document.getElementById('jlord_character_defined').focus();
			return false;
		} else if(document.getElementById('jlord_character_update').value == '') {
			alert('You can entre into least a character ');
			document.getElementById('jlord_character_update').focus();
			return false;
		} else {
			submitform(pressbutton);
			return;
		}
	} else {
		submitform(pressbutton);
		return;
	}
}
</script>
<?php 
	$cid = JRequest::getVar('cid');
	$cid = $cid[0];
?>
<form action ="index.php?option=com_obrss" method="POST" name="adminForm" id ="adminForm" >
	<table class="adminform">
		<tr>
			<td>
				<label for="title">
					Comment:
				</label>
			</td>
			<td>
				<input type="text" value="" maxlength="255" size="40" id="jlord_comment" name="jlord_comment" class="inputbox"/>
			</td>
		</tr>
		<tr>
			<td>
				<label for="title">
					Character defined:
				</label>
			</td>
			<td>
				<input type="text" value="" maxlength="255" size="40" id="jlord_character_defined" name="jlord_character_defined" class="inputbox"/>
			</td>
		</tr>
		<tr>
			<td>
				<label for="title">
					Update Character Language:
				</label>
			</td>
			<td>
				<input type="text" value="" maxlength="255" size="40" id="jlord_character_update" name="jlord_character_update" class="inputbox"/>
			</td>
		</tr>
	</table>
	<input type="hidden" value="langs" name ="controller" />
	<input type="hidden" value="" name ="task" />
	<input type="hidden" value="<?php echo $cid;?>" name ="cid[]" />
</form>