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
$model 	= $this->getModel();
$rows 	= $model->getAddonList();
?>
<div class="well well-small">
	<div class="module-title nav-header"><?php echo JText::_('COM_OBRSS_INSTALLED_ADDONS');?></div>
	<table class="adminlist table table-striped">
		<?php
		foreach ($rows as $row){
			?>
			<tr>
				<td>
					<strong class="row-title"><a href="index.php?option=com_plugins&view=plugin&layout=edit&extension_id=<?php echo $row->id; ?>" >
						<?php echo $row->name; ?>
					</a></strong>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
</div>