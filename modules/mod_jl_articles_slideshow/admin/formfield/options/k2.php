<?php 
/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license		GNU/GPL, see LICENSE
 */

// Check to ensure this file is included in Joomla!
// Set flag that this is a parent file
define( '_JEXEC', 1 );
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../../..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');
jimport('joomla.html.parameter');
$jlang = JFactory::getLanguage();
$jlang->load('mod_jl_articles_slideshow', JPATH_SITE, null, true);

$moduleid	= JRequest::getInt('moduleid','0');
$catid		= 0;
if( $moduleid ){
	$table = JTable::getInstance('module');
	$table->load($moduleid);
	if( $table->id==$moduleid ){
		$params = class_exists('JParameter') ? new JParameter($table->params) : new JRegistry($table->params);
		$catid	= $params->get('catid');
	}
}
$JHtmlCategoryK2 = new JHtmlCategoryK2();
$arroptions = $JHtmlCategoryK2->getData();

$options	= JHtml::_('select.options',$arroptions,'value','text',$catid);
echo $options;exit;
?>

<?php
class JHtmlCategoryK2{
	function getData() {
		$db = JFactory::getDBO();
		$query = 'SELECT c.*, g.title AS groupname, exfg.name as extra_fields_group 
					FROM #__k2_categories as c
					LEFT JOIN #__viewlevels AS g ON g.id = c.access
					LEFT JOIN #__k2_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup
					WHERE c.id>0 AND c.trash=0 ORDER BY c.ordering ';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if($rows){
			foreach($rows as $row){
				$row->parent_id = $row->parent;
				$row->title = $row->name;
			}
		}
		$categories = array();
		$categories = $this->indentRows($rows);
		
		return $categories;
	}
	function indentRows( & $rows, $root = 0) {
		$children = array ();
		if(count($rows)){
			foreach ($rows as $v) {
				$pt = $v->parent;
				$list = @$children[$pt]?$children[$pt]: array ();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$categories = $this->treerecurse($root, '', array (), $children);
		return $categories;
	}
	public function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1){
		if (@$children[$id] && $level <= $maxlevel){
			foreach ($children[$id] as $v){
				$id = $v->id;

				if ($type){
					$pre = '|_ &#160;';
					$spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;';
				}
				else{
					$pre = '- ';
					$spacer = '&#160;&#160;';
				}

				if ($v->parent_id == 0){
					$txt = $v->title;
				}
				else{
					$txt = $pre . $v->title;
				}
				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename	= "$indent$txt";
				$list[$id]->children	= count(@$children[$id]);

				$list[$id]->text		= "$indent$txt";
				$list[$id]->value		= $id;
				
				$list = $this->TreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
			}
		}
		return $list;
	}
}
