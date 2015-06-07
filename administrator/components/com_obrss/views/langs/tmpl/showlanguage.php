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
global $mainframe,$option;
if(isset($this->res->files)) { 
		$rows 		= $this->res->files;
		$pageNav	= $this->res->pageNav;
	}
	$lists			= $this->res->lists;
	$client			= $this->res->client;
	$filter_langs		= $mainframe->getUserStateFromRequest( $option.'filter_langs',	'filter_langs',	0,				'int' );
	$params = JComponentHelper::getParams('com_languages');
	$langua = $params->get($client->name, 'en-GB');
?>
<SCRIPT language="JavaScript">
function submitform_jlord() {
	var jlord_search = document.getElementById('search1');
	if(jlord_search.value == '') {
		alert('you can enter a litle character to search');
		document.adminForm.search1.focus();
		return false;
	}
	document.getElementById('jlord_task').value='search_keyword';
	document.adminForm.submit();
}
</SCRIPT> 
<form action="index.php?option=com_obrss" method="post" name="adminForm" id="adminForm">
		<table>
			<tr>
				<td align="left" width="100%">
					<?php echo JText::_( 'Filter' ); ?>:
					<input type="text" name="search1" id="search1" value="<?php echo $lists['search1'];?>" class="text_area" />
					<button onclick="submitform_jlord();"><?php echo JText::_( 'Go' ); ?></button>
					<button onclick="document.getElementById('search1').value='';this.form.getElementById('filter_langs').value='0';this.form.getElementById('filter_dirs').value='0';this.form.getElementById('filter_file').value='*';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				</td>
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
				&nbsp;
			</th>
			<th width="25px" class="title">
				<?php echo JText::_( 'Language File' ); ?>
			</th>
			<th width="25px" class="title">
				<?php echo JText::_( 'Language Location' ); ?>
			</th>
			<th width="5%">
					<?php echo JText::_( 'Default' ); ?>
			</th>
		</tr>
		</thead>
		<?php if(isset($this->res->pageNav)) { ?>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<?php }?>
		<?php if(isset($this->res->pageNav)) { ?>
		<tbody>
		<?php
			for($i=0;$i<count($rows);$i++) {
			?>
					<tr>
						<td width="5%">
						<?php echo $pageNav->getRowOffset($i); ?>
						</td>
						<td width="5%">
							<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $rows[$i]; ?>" onclick="isChecked(this.checked);" />
						</td>
						<td width="25px">
							<?php
								$file_name =substr( $rows[$i], ( strrpos( $rows[$i], DS) + 1 ) ); 
								$link = JFilterOutput::ampReplace('index.php?option='.$option.'&controller=langs&task=getrwlanguage&cid[]='.$rows[$i].'&redirect_file=showlanguage');
							?>
							<a href="<?php echo $link; ?>" ><?php echo $file_name; ?></a>
						</td>
						<td width="25px">
							<?php
								if($filter_langs == 0 || $filter_langs ==2) {
									echo "<b>Adminitrator</b>";
								} else {
									echo "<b>Site</b>";
								}
							?>
						</td>
						<td width="5%" align="center">
						<?php
							if (substr( $rows[$i], ( strrpos( $rows[$i], DS) + 1 ),5 ) == $langua) {	 ?>
								<img src="templates/khepri/images/menu/icon-16-default.png" alt="<?php echo JText::_( 'Default' ); ?>" />
								<?php
							} else {
								?>
								&nbsp;
						<?php
							}
						?>
						</td>
					</tr>
			<?php } ?>
		</tbody>
		<?php } ?>
	</table>
	<input type="hidden" name="controller" value="langs" />
	<input type="hidden" id = "jlord_task" name="task" value="showlanguage" />
	<input type="hidden" name="boxchecked" value="0" />
</form>