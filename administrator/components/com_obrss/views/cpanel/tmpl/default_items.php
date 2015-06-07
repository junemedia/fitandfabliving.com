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
global $option,$obRootSite;
$models = $this->getModel('cpanel');
$rows 	= $models->getLatestItem();
?>
<div class="well well-small">
	<div class="module-title nav-header"><?php echo JText::_('OBRSS_LATEST_FEEDS');?></div>
	<table class="table table-striped table-condensed">
		<?php
		foreach ($rows as $row){
			?>
			<tr>
				<td>
					<?php
					$link 		= JFilterOutput::ampReplace('index.php?option='.$option.'&controller=feed&task=edit&cid[]='.$row->id);
					$linkURL	= $obRootSite.'index.php?option='.$option.'&amp;task=feed&amp;id='.$row->id.':'.$row->alias;
					?>
					<strong class="row-title"><a href="<?php echo $link; ?>" title="Edit feed <?php echo $row->name; ?>"><?php echo $row->name; ?></a></strong>&nbsp;<a href=" <?php echo $linkURL?>" target="_blank" title="Preview this feed"> [+] </a>
				</td>
				<td>
					<span class="small label"><?php echo $row->addon; ?></span>
				</td>
				<td>
				<span class="small"><i class="icon-calendar-2"></i><?php
					$created = $row->created;
					$created = explode(' ', $created);
					echo $created[0];
					 ?></span>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
</div>