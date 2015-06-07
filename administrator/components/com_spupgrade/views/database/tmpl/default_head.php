<?php
/**
 * @package		SP Upgrade
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<thead>
    <tr>
        <th width="1%">
            <?php echo JText::_('JGLOBAL_FIELD_ID_LABEL'); ?>
        </th>
        <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
        </th>
        <th class="left">
            <?php echo JText::_('COM_SPUPGRADE_FIELD_TABLE_NAME_LABEL'); ?>
        </th>
        <th class="center">
            <?php echo JText::_('COM_SPUPGRADE_FIELD_TABLE_IDS_LABEL'); ?>
        </th>
    </th>
    <th width="10%">

    </th>
</tr>
</thead>
<div class="btn-group pull-right hidden-phone">
    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
    <?php echo $this->pagination->getLimitBox(); ?>
</div>
