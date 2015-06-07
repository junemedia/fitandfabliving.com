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
global $mainframe, $option;
if(isset($this->search_keyword->data)) {
	$display = $this->search_keyword->data;
	$pageNav	= $this->search_keyword->pageNav;
}
$lists			= $this->search_keyword->lists;
$search 		= $mainframe->getUserStateFromRequest( $option.'search1', 			'search1', 			'',				'string' );
?>
<script type="text/javascript">
function submitbutton(pressbutton){
	submitform(pressbutton);
	return;
}
</script>
<form action ="index.php?option=com_obrss" method="POST" name="adminForm" id ="adminForm" >
<div>&nbsp;</div>
	<table style="float:right;">
		<tr>
			<td nowrap="nowrap">
			<?php
				echo $lists['langs'];
			?>
			</td>
			<td nowrap="nowrap">
			<?php
				echo $lists['dirlangs'];
			?>
			</td>
			<td nowrap="nowrap">
			<?php
				echo $lists['file_style'];
			?>
			</td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
		<tr>
			<th width="10px">
				<?php echo JText::_( 'Num' ); ?>
			</th>
			<th width="10px">
				<?php echo JText::_( 'Character defined' ); ?>
			</th>
			<th width="25px" class="title">
				<?php echo JText::_( 'Update Character Language' ); ?>
			</th>
			<th width="25px" class="title">
				<?php echo JText::_( 'Language File' ); ?>
			</th>
		</tr>
		</thead>
		<?php if(isset($pageNav)) {?>
			<tfoot>
				<tr>
					<td colspan="11">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		<?php } ?>
		<tbody>
		<?php 
		if(isset($display)) {
			for($i=0;$i<count($display);$i++) {
		?>
				<tr>
					<td width="5%">
					<?php 
						echo $pageNav->getRowOffset($i); 
					?>
					</td>
					<td width="5%">
						<input  disabled = "disabled" class ="text_area" type = "text" value = "<?php echo $display[$i]->id; ?>" size="60" />
						<input type="hidden" name = "lang<?php echo $i;?>" value = "<?php echo $display[$i]->id; ?>" />
					</td>
					<td width="25px">
						<input name = "jlord_lang<?php echo $i;?>" class ="text_area" type = "text" value = "<?php echo $display[$i]->value; ?>" size="100" onchange="if(this.name.lastIndexOf('123456') == -1) {this.name=this.name+123456; document.getElementById('add_write').value='jlord';}" />
					</td>
					<td width="25px">
							<?php
								$file_name =substr( $display[$i]->file_name, ( strrpos( $display[$i]->file_name, DS) + 1 ) ); 
								$link = JFilterOutput::ampReplace('index.php?option='.$option.'&controller=langs&task=getrwlanguage&cid[]='.$display[$i]->file_name.'&search='.$search);
							?>
							<a href="<?php echo $link; ?>" ><?php echo $file_name; ?></a>
							<input type="hidden" name= "jlord_lang_file<?php echo $i; ?>" value="<?php echo $display[$i]->file_name; ?>" />
					</td>
				</tr>
			<?php
			}
		}
			?>
		</tbody>
	</table>
	<input type="hidden" value="langs" name ="controller" />
	<input type="hidden" name ="add_write" id = "add_write" value=""/>
	<input type="hidden" value="<?php echo count($display); ?>" name ="total_data" />
	<input type="hidden" value="search_keyword" name ="task" />
</form>