<?php
/**
 * @version		$Id: obsections.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter.element');
if(class_exists('JElement')):
class JElementobSections extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'obSections';
	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = JFactory::getDBO();
		$query = '
			SELECT s.id AS value, s.title AS text
			FROM #__sections AS s
			WHERE s.scope = "content"
			ORDER BY s.title
		';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		if ($options) {
			array_unshift($options, JHTML::_('select.option', '0', JText::_('OBRSS_ADDON_CONTENT_ALL_SECTIONS')));
			array_unshift($options, JHTML::_('select.option', '', JText::_('OBRSS_ADDON_CONTENT_ALL_SECTIONS')));
			return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'multiple="true"', 'value', 'text', $value, $control_name.$name);
		} else {
			return JText::_('OBRSS_ADDON_PARAMS_NO_DATA');
		}
	}
}
endif;