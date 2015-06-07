<?php
/**
 * @version		$Id: obeditor.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class JElementOBEditor extends JElement
{
	var $_name = 'obeditor';
	public function fetchElement($name, $value, &$node, $control_name)
	{
		global $isJ25;
		if(!$isJ25){ 
			jimport('joomla.html.editor');
			$editor = new JEditor('tinymce');
			$editor_params = array('mode'=>'0', 'content_css'=>'1', 'newlines'=>'1', 'cleanup_save'=>'2');
			$editor_ticket_message = $editor->display($control_name.'['.$name.']',  $value, '100%', '200', '200', '200', false,$control_name.'['.$name.']',null, null, $editor_params);
		} else {
			$editor_params = array('mode'=>'simple', 'content_css'=>'1', 'newlines'=>'1', 'cleanup_save'=>'2');
			$editor = &JFactory::getEditor('tinymce');
			$editor_ticket_message = $editor->display($control_name.'['.$name.']',  $value, '100%', '200', '200', '200', false, $editor_params);
		}
		return $editor_ticket_message;
	}
}
