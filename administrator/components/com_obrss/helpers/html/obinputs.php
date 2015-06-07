<?php
defined('JPATH_PLATFORM') or die;

abstract class JHtmlobinputs
{
	/**
	 * Default values for options. Organized by option group.
	 *
	 * @var     array
	 * @since   11.1
	 */
	static protected $optionDefaults = array(
		'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
			'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
			'option.text.toHtml' => true));


	public static function booleanlist($name, $attribs = null, $selected = null, $yes = 'JYES', $no = 'JNO', $id = false)
	{
		$arr = array(JHtml::_('select.option', '0', JText::_($no)), JHtml::_('select.option', '1', JText::_($yes)));
		return JHtml::_('select.radiolist', $arr, $name, $attribs, 'value', 'text', (int) $selected, $id);
	}

	public static function radiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false) {
		reset($data);

		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
		}

		$id_text = $idtag ? $idtag : $name;

		$html = '<div class="controls">';
		$html .= '<fieldset id="'.$idtag.'" class="radio btn-group">';
		foreach ($data as $obj)
		{
			$k = $obj->$optKey;
			$t = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$extra .= $id ? ' id="' . $obj->id . '"' : '';
			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;
					if ($k == $k2)
					{
						$extra .= ' selected="selected"';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $k == (string) $selected ? ' checked="checked"' : '');
			}
			$html .= "\n\t" . '<label for="' . $id_text . $k . '"' . ' id="' . $id_text . $k . '-lbl" class="radio btn">';
			$html .= "\n\t" . "\n\t" . '<input type="radio" name="' . $name . '"' . ' id="' . $id_text . $k . '" value="' . $k . '"' . ' ' . $extra . ' '
				. $attribs . '>' . $t;
			$html .= "\n\t" . '</label>';
		}
		$html .= '</fieldset>';
		$html .= '</div>';
		$html .= "\n";
		return $html;
	}
}