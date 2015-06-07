<?php

/**
 * @package		SP Upgrade
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die;

//jimport('joomla.application.component.model');

/**
 * SP CYEND general class
 *
 */
class SPUpgradeGeneral {

    public static function getOldId($id, $task_id) {
        $tableLog = CYENDFactory::getTable('Log', 'SPUpgradeTable');
        $tableLog->reset();
        $tableLog->id = null;
        $tableLog->load(array("tables_id" => $task_id, "source_id" => $id));

        return $tableLog->destination_id;
    }

}
