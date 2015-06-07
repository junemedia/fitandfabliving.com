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
global $isJ25;
?>
<div id="foobla">
<div class="row-fluid">
	<?php if($isJ25 or 1==1):?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<?php endif;?>
	<div class="span6">
		<?php echo $this->loadTemplate('items'); ?>
		<?php echo $this->loadTemplate('addons'); ?>
	</div>
	<div class="span4">
		<?php echo $this->loadTemplate('info');?>
	</div>
</div>
</div>