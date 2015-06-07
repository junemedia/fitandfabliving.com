<?php
/**
 * @version		$Id: obauthor.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter.element');
if(class_exists('JElement')):
class JElementobAuthor extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'obAuthor';
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $isJ25;
		$db = &JFactory::getDBO();
		if (!$isJ25) {
			$qury = "SELECT u.id as value, u.name as text FROM #__users as u WHERE u.block = 0 ORDER BY u.name";
		} else {
			$qury = "SELECT u.id as value, u.name as text FROM #__users as u WHERE u.gid >18 ORDER BY u.name";
		}
		$db->setQuery($qury);
		$rows = $db->loadObjectList();
		if ($rows) {
			$options[] = JHTML::_('select.option', '', JText::_('OBRSS_ADDON_CONTENT_ALL_AUTHORS'));
			$options = array_merge($options, $rows);
			$options_size = count($options) <= 20 ? count($options) : 20;
			$authors = JHTML::_('select.genericlist', $options, ''.$control_name.'['.$name.'][]', 'multiple="multiple" size="'.$options_size.'"', 'value', 'text', $value, $control_name.$name );
			return $authors;
		} else {
			return JText::_('OBRSS_ADDON_PARAMS_NO_DATA');
		}
	}
}

endif;

/**
 * Field to select a user id from a modal list.
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       1.6.0
 */
class JFormFieldobAuthor extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6.0
	 */
	public $type = 'obAuthor';

	/**
	 * Method to get the user field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.6.0
	 */
	protected function getInput()
	{
		global $isJ25;
		$html = array();
		$groups = $this->getGroups();
		$excluded = $this->getExcluded();
		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=' . $this->id
		. (isset($groups) ? ('&amp;groups=' . base64_encode(json_encode($groups))) : '')
		. (isset($excluded) ? ('&amp;excluded=' . base64_encode(json_encode($excluded))) : '');

		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal_' . $this->id);

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_' . $this->id . '(id, title) {';
		$script[] = '		var old_id = document.getElementById("' . $this->id . '_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '			document.getElementById("' . $this->id . '_name").value = title;';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('user');
		if ($this->value)
		{
			$table->load($this->value);
		}
		else
		{
			$table->username = JText::_('JLIB_FORM_SELECT_USER');
		}

		// Create a dummy text field with the user name.
		$html[] = '<div class="input-append">';
		$html[] = '	<input class="span12 input-medium" type="text" id="' . $this->id . '_name" value="' . htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') . '"'
				. ' disabled="disabled"' . $attr . ' />';

		// Create the user select button.
		$icon_padding = '6px 10px 7px 10px';
		if (!$isJ25) {
			$icon_padding = '5px 10px 5px 10px';
		}
		if ($this->element['readonly'] != 'true')
		{
			$html[] = '		<a style="padding: '.$icon_padding.';" class="btn btn-primary modal_' . $this->id . '" title="' . JText::_('JLIB_FORM_CHANGE_USER') . '" href="' . $link . '"'
					. ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html[] = '<i class="icon-user"></i></a>';
		}
		$html[] = '</div>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="' . (int) $this->value . '" />';

		return implode("\n", $html);
	}

	/**
	 * Method to get the filtering groups (null means no filtering)
	 *
	 * @return  mixed  array of filtering groups or null.
	 *
	 * @since   1.6.0
	 */
	protected function getGroups()
	{
		return null;
	}

	/**
	 * Method to get the users to exclude from the list of users
	 *
	 * @return  mixed  Array of users to exclude or null to to not exclude them
	 *
	 * @since   1.6.0
	 */
	protected function getExcluded()
	{
		return null;
	}
}