<?php
/**
 * @version		$Id: obparameter.php 732 2013-07-22 08:53:07Z tsvn $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('JPATH_BASE') or die;
jimport('joomla.registry.registry');
/**
 * Parameter handler
 *
 * @package		Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */
//jimport();
/* if(!class_exists('JParameter')){
	class JParameter {
		public $form = null;
		public $params;
		function __construct( $params='{}', $xmlpath='',$control='details' ) {
			$registry = new JRegistry();
			$registry->loadString($params);
			$this->params = array('default'=>$registry->toArray());
			JForm::addFormPath( dirname( $xmlpath ) );
			$formname 	= pathinfo($xmlpath,PATHINFO_FILENAME);
			$this->form = JForm::getInstance('com_obrss.addon.params'.$formname, $formname, array('control' => $control, 'load_data' => true), false, 'config');
			$this->form->bind($this->params);
		}
		
		public function get( $key='',$default='' ) {
			$registry 		= new JRegistry();
			$registry->loadArray($this->params);
			return $registry->get($key, $default);
		}
	}
} */
class OBParameter
{
	public $form = null;
	public $params;
	function __construct( $params='{}', $xmlpath='',$control='details' ) {
		$registry = new JRegistry();
		$registry->loadString($params);
		$this->params = array('default'=>$registry->toArray());
		JForm::addFormPath( dirname( $xmlpath ) );
		$formname 	= pathinfo($xmlpath,PATHINFO_FILENAME);
		$this->form = JForm::getInstance('com_obrss.addon.params'.$formname, $formname, array('control' => $control, 'load_data' => true), false, 'config');
		$this->form->bind($this->params);
	}
	public function get( $key='',$default='' ) {
		$registry 		= new JRegistry();
		$registry->loadArray($this->params);
		return $registry->get($key, $default);
	}
	
	public function render($name = 'params', $group = '_default'){
		$params 		= $this->params;
		$fields 		= $this->form->getFieldset('basic');
		$addonRender 	= '';
		
		foreach ( $fields as $field) {
			$value = isset($params[$field->fieldname])?$params[$field->fieldname]:'';
			$input = $field->input;
			if (!$field->hidden) {
				$addonRender .= '<div class="control-group">'
									.'<div class="control-label">'.$field->label.'</div>'
									.'<div class="controls">' . $input. '</div>'
								.'</div>';
			} else {
				$addonRender .= $input;
			}
		}
		return $addonRender;
	}
	/**
	 * Render the form control.
	 *
	 * @param	string	An optional name of the HTML form control. The default is 'params' if not supplied.
	 * @param	string	An optional group to render.  The default group is used if not supplied.
	 * @return	string	HTML
	 * @since	1.5
	 */
/*
	public function render($name = 'params', $group = '_default')
	{
		if (!isset($this->_xml[$group])) {
			return false;
		}
		$params = $this->getParams($name, $group);
		$html = array ();
		if ($description = $this->_xml[$group]->attributes('description')) {
			// add the params description to the display
			$desc	= JText::_($description);
			$html[]	= '<p class="paramrow_desc">'.$desc.'</p>';
		}
		$out = '<fieldset class="panelform">'
				.'<ul class="adminformlist">';
		foreach ($params as $param) {
			if ($param[0]) {
				$html[] = $param[0];
				$html[] = $param[1];
				if (strpos($param[1], 'type="radio"')) {
					$out.= '<li>'.$param[0].'<fieldset id="jform_showtitle" class="radio">'.$param[1].'</fieldset><div style="clear:both;"></div></li>';
				} else {
					$out.= '<li>'.$param[0].$param[1].'<div style="clear:both;"></div></li>';
				}
			} else {
				$html[] = $param[1];
				$out.= '<li>'.$param[1].'<div style="clear:both;"></div></li>';
			}
		}
		$out .= '</ul></fieldset>';
		if (count($params) < 1) {
			$html[] = "<p class=\"noparams\">".JText::_('JLIB_HTML_NO_PARAMETERS_FOR_THIS_ITEM')."</p>";
		}
//		return implode(PHP_EOL, $html);
		return $out;
	}
*/
	/**
	 * Render all parameters to an array.
	 *
	 * @param	string	An optional name of the HTML form control. The default is 'params' if not supplied.
	 * @param	string	An optional group to render.  The default group is used if not supplied.
	 * @return	array
	 * @since	1.5
	 */
	public function renderToArray($name = 'params', $group = '_default')
	{
		if (!isset($this->_xml[$group])) {
			return false;
		}
		$results = array();
		foreach ($this->_xml[$group]->children() as $param)  {
			$result = $this->getParam($param, $name, $group);
			$results[$result[5]] = $result;
		}
		return $results;
	}
	/**
	 * Return the number of parameters in a group.
	 *
	 * @param	string	An optional group.  The default group is used if not supplied.
	 * @return	mixed	False if no params exist or integer number of parameters that exist.
	 * @since	1.5
	 */
	public function getNumParams($group = '_default')
	{
		if (!isset($this->_xml[$group]) || !count($this->_xml[$group]->children())) {
			return false;
		} else {
			return count($this->_xml[$group]->children());
		}
	}
	/**
	 * Get the number of params in each group.
	 *
	 * @return	array	Array of all group names as key and parameters count as value.
	 * @since	1.5
	 */
	public function getGroups()
	{
		if (!is_array($this->_xml)) {
			return false;
		}
		$results = array();
		foreach ($this->_xml as $name => $group)  {
			$results[$name] = $this->getNumParams($name);
		}
		return $results;
	}
	/**
	 * Render all parameters.
	 *
	 * @param	string	An optional name of the HTML form control. The default is 'params' if not supplied.
	 * @param	string	An optional group to render.  The default group is used if not supplied.
	 * @return	array	An array of all parameters, each as array of the label, the form element and the tooltip.
	 * @since	1.5
	 */
	public function getParams($name = 'params', $group = '_default')
	{
		if (!isset($this->_xml[$group])) {
			return false;
		}
		$results = array();
		foreach ($this->_xml[$group]->children() as $param)  {
			$results[] = $this->getParam($param, $name, $group);
		}
		return $results;
	}
	/**
	 * Render a parameter type.
	 *
	 * @param	object	A parameter XML element.
	 * @param	string	An optional name of the HTML form control. The default is 'params' if not supplied.
	 * @param	string	An optional group to render.  The default group is used if not supplied.
	 * @return	array	Any array of the label, the form element and the tooltip.
	 * @since	1.5
	 */
	public function getParam(&$node, $control_name = 'params', $group = '_default')
	{
		// Get the type of the parameter.
		$type = $node->attributes('type');
		$element = $this->loadElement($type);
		// Check for an error.
		if ($element === false) {
			$result = array();
			$result[0] = $node->attributes('name');
			$result[1] = JText::_('Element not defined for type').' = '.$type;
			$result[5] = $result[0];
			return $result;
		}
		// Get value.
		$value = $this->get($node->attributes('name'), $node->attributes('default'), $group);
		return $element->render($node, $value, $control_name);
	}
	/**
	 * Loads an xml setup file and parses it.
	 *
	 * @param	string	A path to the XML setup file.
	 * @return	object
	 * @since	1.5
	 */
	public function loadSetupFile($path)
	{
		$result = false;
		if ($path) {
			$xml = JFactory::getXMLParser('Simple');
			if ($xml->loadFile($path)) {
				if ($params = $xml->document->params) {
					foreach ($params as $param) {
						$this->setXML($param);
						$result = true;
					}
				}
			}
		} else {
			$result = true;
		}
		return $result;
	}
	/**
	 * Loads an element type.
	 *
	 * @param	string	The element type.
	 * @param	boolean	False (default) to reuse parameter elements; true to load the parameter element type again.
	 * @return	object
	 * @since	1.5
	 */
	public function loadElement($type, $new = false)
	{
		$signature = md5($type);
		if ((isset($this->_elements[$signature]) && !($this->_elements[$signature] instanceof __PHP_Incomplete_Class))  && $new === false) {
			return	$this->_elements[$signature];
		}
		$elementClass	=	'JElement'.$type;
		if (!class_exists($elementClass)) {
			if (isset($this->_elementPath)) {
				$dirs = $this->_elementPath;
			} else {
				$dirs = array();
			}
			$file = JFilterInput::getInstance()->clean(str_replace('_', DS, $type).'.php', 'path');
			jimport('joomla.filesystem.path');
			if ($elementFile = JPath::find($dirs, $file)) {
				include_once $elementFile;
			} else {
				$false = false;
				return $false;
			}
		}
		if (!class_exists($elementClass)) {
			$false = false;
			return $false;
		}
		$this->_elements[$signature] = new $elementClass($this);
		return $this->_elements[$signature];
	}
	/**
	 * Add a directory where JParameter should search for element types.
	 *
	 * You may either pass a string or an array of directories.
	 *
	 * JParameter will be searching for a element type in the same
	 * order you added them. If the parameter type cannot be found in
	 * the custom folders, it will look in
	 * JParameter/types.
	 *
	 * @param	string|array	Directory or directories to search.
	 * @since	1.5
	 */
	public function addElementPath($path)
	{
		// Just force path to array.
		settype($path, 'array');
		// Loop through the path directories.
		foreach ($path as $dir) {
			// No surrounding spaces allowed!
			$dir = trim($dir);
			// Add trailing separators as needed.
			if (substr($dir, -1) != DIRECTORY_SEPARATOR) {
				// Directory
				$dir .= DIRECTORY_SEPARATOR;
			}
			// Add to the top of the search dirs.
			array_unshift($this->_elementPath, $dir);
		}
	}
}