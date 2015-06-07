<?php
/**
 * @version		$Id: obcategory.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter.element');
if(class_exists('JElement')):
class JElementobCategory extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'obCategory';
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $isJ25;
		if (!$isJ25) {
			$cats = JHTML::_('category.options', 'com_content');
		} else {
			$db	= JFactory::getDBO();
			$qry	= '
				SELECT c.`id` AS `value`, CONCAT_WS( " / ", s.`title`, c.`title`) AS `text`
				FROM
					`#__sections` AS s,
					`#__categories` AS c
				WHERE
					s.`scope` = "content" AND
					c.`section` = s.`id`
				ORDER BY s.`ordering`, c.`ordering`
			';
			$db->setQuery($qry);
			$cats = $db->loadObjectList();
		}
		if ($cats) {
			array_unshift($cats, JHTML::_('select.option', '0', JText::_('OBRSS_ADDON_CONTENT_UNCATEGORISED')));
			array_unshift($cats, JHTML::_('select.option', '', JText::_('OBRSS_ALLCAT')));
			$options_size = count($cats) <= 20 ? count($cats) : 20;
			return JHTML::_('select.genericlist',  $cats, ''.$control_name.'['.$name.'][]', 'multiple="true" size="'.$options_size.'"', 'value', 'text', $value, $control_name.$name );
		} else {
			return JText::_('OBRSS_ADDON_PARAMS_NO_DATA');
		}
	}
}
endif;