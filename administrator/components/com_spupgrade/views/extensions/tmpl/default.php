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
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) { 
        var myDomain = location.protocol + '//' +
            location.hostname +
            location.pathname.substring(0, location.pathname.lastIndexOf('/')) +
            //'/index.php?option=com_spupgrade&view=monitoring_log';                    
        '/components/com_spupgrade/log.htm';                    
        window.open(myDomain,'SP Upgrade','width=640,height=480, scrollbars=1');
        Joomla.submitform(task);                
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_spupgrade&view=extensions'); ?>" method="post" name="adminForm" id="adminForm">        
    <div class="clr"></div>
    <table class="table table-striped">
        <thead><?php echo $this->loadTemplate('head'); ?></thead>
        <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
        <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>