<?php
/**
 * @package	foobla RSS Feed Creator for Joomla.
 * @created: Setember 2008.
 * @updated: 2009/12/02 
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
global $option;
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );
$version	=	MediaHelper::getVersion();
// $version	=	$checkversion['version'];
?>
<div class="well well-small">
	<div class="module-title nav-header"><?php echo JText::_('COM_OBRSS_INFORMATION');?></div>
	<table class="table table-striped table-condensed">
		<tr>
			<td valign="top"><strong class="row-title"><?php echo JText::_('VERSION'); ?></strong></td>
			<td><strong><?php echo $version; ?></strong></td>
		</tr>
		<tr>
			<td valign="top"><strong class="row-title"><?php echo JText::_('OBRSS_COPYRIGHT'); ?></strong></td>
			<td><?php echo JText::_('OBRSS_COPYRIGHT_INFO'); ?></td>
		</tr>
		<tr>
			<td valign="top"><strong class="row-title"><?php echo JText::_('OBRSS_LICENSE'); ?></strong></td>
			<td>GNU/GPL</td>
		</tr>
		<tr>
			<td valign="top"><strong class="row-title"><?php echo JText::_('OBRSS_CREDITS'); ?></strong></td>
			<td>
				<ul style="margin: 0; padding-left: 15px;">
					<li><strong>Thong Tran</strong> (<?php echo JText::_('OBRSS_INFO_PRODUCTMANAGER'); ?>)</li>
					<li><strong>Hang Duong</strong> (<?php echo JText::_('OBRSS_INFO_DEVELOPER'); ?>)</li>
					<li><strong>Kha Nguyen</strong> (<?php echo JText::_('OBRSS_INFO_DEVELOPER'); ?>)</li>
					<li><strong>Phong Lo</strong> (<?php echo JText::_('OBRSS_INFO_DEVELOPER'); ?>)</li>
				</ul>
			</td>
		</tr>
	</table>
</div>