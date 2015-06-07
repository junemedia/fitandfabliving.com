<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
* Form Field class for the Joomla Framework.
*
* @package      Joomla.Framework
* @subpackage   Form
* @since      1.6
*/
class JFormFieldUsermedia extends JFormField
{
   /**
    * The form field type.
    *
    * @var      string
    * @since   1.6
    */
   protected $type = 'usermedia';

   /**
    * The initialised state of the document object.
    *
    * @var      boolean
    * @since   1.6
    */
   protected static $initialised = false;

   /**
    * Method to get the field input markup.
    *
    * @return   string   The field input markup.
    * @since   1.6
    */
   protected function getInput()
   {
      $user      = JFactory::getUser();
      
      $assetField   = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
      $authorField	= $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
      $asset      	= $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'] ;
	  
	  $input 	= JFactory::getApplication()->input;
      if ($asset == "") {
          $asset = $input->get('option');
      }
      
      $link = (string) $this->element['link'];
      if (!self::$initialised) {

         // Load the modal behavior script.
         JHtml::_('behavior.modal');

         // Build the script.
         $script = array();
         $script[] = '   function jInsertFieldValue(value, id) {';
         $script[] = '      var old_id = document.id(id).value;';
         $script[] = '      if (old_id != id) {';
         $script[] = '         var elem = document.id(id)';
         $script[] = '         elem.value = value;';
         $script[] = '         elem.fireEvent("change");';
         $script[] = '      }';
         $script[] = '   }';

         // Add the script to the document head.
         JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

         self::$initialised = true;
      }

      // Initialize variables.
      $html = array();
      $attr = '';

      // Initialize some field attributes.
      $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
      $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

      // Initialize JavaScript field attributes.
      $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

      // The text field.
      $html[] = '<div class="fltlft">';
      $html[] = '   <input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
               ' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
               ' readonly="readonly"'.$attr.' />';
      $html[] = '</div>';

      $componentName = (string)$this->element['componentname'];
      $directory=$user->username.'-'.$user->id;
      
      if(!is_dir(JPATH_ROOT.'/images/'.$componentName)){
         mkdir(JPATH_ROOT.'/images/'.$componentName);
      }
      if(!is_dir(JPATH_ROOT.'/images/'.$componentName.'/'.$directory)){
         mkdir(JPATH_ROOT.'/images/'.$componentName.'/'.$directory);
      }

      $directory=$componentName.'/'.$directory;

      if ($this->value && file_exists(JPATH_ROOT.'/'.$this->value)) {
         $folder = explode ('/',$this->value);
         array_shift($folder);
         array_pop($folder);
         $folder = implode('/',$folder);
      }
      elseif (file_exists(JPATH_ROOT.'/images/'.$directory)) {
         $folder = $directory;
      }
      else {
         $folder='';
      }
      // The button.
      $html[] = '<div class="button2-left">';
      $html[] = '   <div class="blank">';
      $html[] = '      <a class="modal" title="'.JText::_('JLIB_FORM_BUTTON_SELECT').'"' .
               ' href="'.($this->element['readonly'] ? '' : ($link ? $link : 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset='.$asset.'&amp;author='.$this->form->getValue($authorField)) . '&amp;fieldid='.$this->id.'&amp;folder='.$folder).'"' .
               ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
      $html[] = '         '.JText::_('JLIB_FORM_BUTTON_SELECT').'</a>';
      $html[] = '   </div>';
      $html[] = '</div>';
      
      $html[] = '<div class="button2-left">';
      $html[] = '   <div class="blank">';
      $html[] = '      <a title="'.JText::_('JLIB_FORM_BUTTON_CLEAR').'"' .
               ' href="#"'.
               ' onclick="$(\''.$this->id.'\').value=\'\'; $(\''.$this->id.'\').onchange();">';
      $html[] = '         '.JText::_('JLIB_FORM_BUTTON_CLEAR').'</a>';
      $html[] = '   </div>';
      $html[] = '</div>';

      return implode("\n", $html);
   }
}