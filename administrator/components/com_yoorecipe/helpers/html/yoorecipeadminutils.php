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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class JHtmlYooRecipeAdminUtils
{
	/**
	 * @var	array	Cached array of the recipe items.
	 */
	protected static $items = array();
	
	/**
	 * Format an amount of minutes into hours and minutes
	 */
	static function formatTime($duration, $D = null, $H = null, $M = null) {

		if ($D == null) {
			$D = JText::_('COM_YOORECIPE_DAY') . ' ';
		}
		if ($H == null) {
			$H = JText::_('COM_YOORECIPE_HOUR') . ' ';
		}
		if ($M == null) {
			$M = JText::_('COM_YOORECIPE_MIN');
		}
		
		if ($duration == 0) {
			return '0 min';
		}
		
		$d = floor ($duration / 1440);
		$h = floor ( ($duration - ($d * 1440)) / 60);
		$m = $duration - $d * 1440 - $h * 60;
		
		$result = '';
		
		if ($d > 0) {
			$result .= $d . $D ;
		}
		if ($h > 0) {
			$result .= $h . $H;
		}
		if ($m > 0) {
			$result .= $m . $M;
		}
		return $result;
	}
	
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png',	'yoorecipes.featured',	'COM_YOORECIPE_UNFEATURED',	'COM_YOORECIPE_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png',	'yoorecipes.unfeatured',	'COM_CONTENT_FEATURED',		'COM_YOORECIPE_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image','admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html.'</a>';
		}

		return $html;
	}
	
	/**
	 * Returns an array of standard published state filter options.
	 *
	 * @param	array			An array of configuration options.
	 *							This array can contain a list of key/value pairs where values are boolean
	 *							and keys can be taken from 'published', 'unpublished', 'all'.
	 *							These pairs determine which values are displayed.
	 * @return	string			The HTML code for the select tag
	 *
	 * @since	1.6
	 */
	static function publishedOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('published', $config) || $config['published']) {
			$options[]	= JHtml::_('select.option', '1', 'JPUBLISHED');
		}
		if (!array_key_exists('unpublished', $config) || $config['unpublished']) {
			$options[]	= JHtml::_('select.option', '0', 'JUNPUBLISHED');
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*', 'JALL');
		}
		return $options;
	}
	
	static function j3_publishedOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('published', $config) || $config['published']) {
			$options[]	= JHtml::_('select.option', '1', JText::_('JPUBLISHED'));
		}
		if (!array_key_exists('unpublished', $config) || $config['unpublished']) {
			$options[]	= JHtml::_('select.option', '0',  JText::_('JUNPUBLISHED'));
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*',  JText::_('JALL'));
		}
		return $options;
	}
	
	/**
	 * Returns a list of authors who created recipes
	 */
	static function createdByOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select('distinct u.id, u.username');
		$query->from('#__yoorecipe as r');
		$query->join('LEFT', '#__users u on u.id = r.created_by');
		$query->order('r.created_by asc');
		
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as &$item) {
			$options[] = JHtml::_('select.option', $item->id, $item->username);
		}
		
		return $options;
	}
	
	/**
	 * Returns an array of ingredient units state filter options.
	 *
	 * @param	array			An array of configuration options.
	 *							This array can contain a list of key/value pairs where values are boolean
	 *							and keys can be taken from 'published', 'unpublished', 'all'.
	 *							These pairs determine which values are displayed.
	 * @return	string			The HTML code for the select tag
	 *
	 * @since	1.6
	 */
	static function unitsOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
			
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select('distinct u.code');
		$query->from('#__yoorecipe_units as u');
		$query->order('u.code asc');
		
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as &$item) {
			$options[] = JHtml::_('select.option', $item->code, $item->code.' ');
		}
		
		return $options;
	}
	
	/**
	 * Returns an array of language ingredient units state filter options.
	 *
	 * @param	array			An array of configuration options.
	 *							This array can contain a list of key/value pairs where values are boolean
	 *							and keys can be taken from 'published', 'unpublished', 'all'.
	 *							These pairs determine which values are displayed.
	 * @return	string			The HTML code for the select tag
	 *
	 * @since	1.6
	 */
	static function unitsLanguages($config = array())
	{
		// Build the active state filter options.
		$options	= array();
			
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select('distinct u.lang');
		$query->from('#__yoorecipe_units as u');
		$query->order('u.lang asc');
		
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as &$item) {
			$options[] = JHtml::_('select.option', $item->lang, $item->lang);
		}
		
		return $options;
	}
	
	/**
	 * Returns an array of standard offensive state filter options.
	 *
	 * @param	array			An array of configuration options.
	 *							This array can contain a list of key/value pairs where values are boolean
	 *							and keys can be taken from 'published', 'unpublished', 'all'.
	 *							These pairs determine which values are displayed.
	 * @return	string			The HTML code for the select tag
	 *
	 * @since	1.6
	 */
	static function offensiveOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('offensive', $config) || $config['offensive']) {
			$options[]	= JHtml::_('select.option', '1', 'COM_YOORECIPE_COMMENTS_OFFENSIVE');
		}
		if (!array_key_exists('notoffensive', $config) || $config['notoffensive']) {
			$options[]	= JHtml::_('select.option', '0', 'COM_YOORECIPE_COMMENTS_NOT_OFFENSIVE');
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*', 'JALL');
		}
		return $options;
	}
	
	static function j3_offensiveOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('offensive', $config) || $config['offensive']) {
			$options[]	= JHtml::_('select.option', '1', JText::_('COM_YOORECIPE_COMMENTS_OFFENSIVE'));
		}
		if (!array_key_exists('notoffensive', $config) || $config['notoffensive']) {
			$options[]	= JHtml::_('select.option', '0', JText::_('COM_YOORECIPE_COMMENTS_NOT_OFFENSIVE'));
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*', JText::_('JALL'));
		}
		return $options;
	}
	
	/**
	 * @param	mixed $value	Either the scalar value, or an object (for backward compatibility, deprecated)
	 * @param	int $i
	 * @param	string $img1	Image for a positive or on value
	 * @param	string $img0	Image for the empty or off value
	 * @param	string $prefix	An optional prefix for the task
	 *
	 * @return	string
	 */
	public static function offensive($value, $i, $img1 = 'tick.png', $img0 = 'publish_x.png', $prefix='comments.')
	{
		if (is_object($value)) {
			$value = $value->published;
		}

		$img	= $value ? $img1 : $img0;
		$task	= $value ? 'setToNonOffensive' : 'setToOffensive';
		$alt	= $value ? JText::_('COM_YOORECIPE_OFFENSIVE') : JText::_('COM_YOORECIPE_NONOFFENSIVE');
		$action = $value ? JText::_('COM_YOORECIPE_SET_NONOFFENSIVE') : JText::_('COM_YOORECIPE_SET_OFFENSIVE');

		$href = '
		<a href="#" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">'.
		JHtml::_('image','admin/'.$img, $alt, NULL, true).'</a>'
		;

		return $href;
	}
	
	/**
	 * Returns an array of recipes
	 *
	 * @param	string	The extension option.
	 * @param	array	An array of configuration options. By default, only published and unpulbished categories are returned.
	 *
	 * @return	array
	 */
	public static function recipeOptions( $config = array('filter.published' => array(0,1)))
	{
		$hash = md5('recipes.'.serialize($config));

		if (!isset(self::$items[$hash])) {
			$config	= (array) $config;
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			$query->select('r.id, r.title');
			$query->from('#__yoorecipe AS r');

			// Filter on the published state
			if (isset($config['filter.published'])) {
				if (is_numeric($config['filter.published'])) {
					$query->where('r.published = '.(int) $config['filter.published']);
				} else if (is_array($config['filter.published'])) {
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('r.published IN ('.implode(',', $config['filter.published']).')');
				}
			}
			// Filter on the category state
			if (isset($config['filter.category_id']) && $config['filter.category_id'] != 0) {
			
				$query->join('LEFT', '#__yoorecipe_categories cc on cc.recipe_id = r.id');
				$query->where('cc.cat_id = '.(int) $config['filter.category_id']);
			}

			$query->order('r.title');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// Assemble the list options.
			self::$items[$hash] = array();

			foreach ($items as &$item) {
				self::$items[$hash][] = JHtml::_('select.option', $item->id, $item->title);
			}
		}

		return self::$items[$hash];
	}
	
	/**
	 * Returns an array of standard validation state filter options.
	 *
	 * @param	array			An array of configuration options.
	 *							This array can contain a list of key/value pairs where values are boolean
	 *							and keys can be taken from 'validated', 'not validated', 'all'.
	 *							These pairs determine which values are displayed.
	 * @return	string			The HTML code for the select tag
	 *
	 * @since	1.6
	 */
	static function validatedOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('validated', $config) || $config['validated']) {
			$options[]	= JHtml::_('select.option', '1', 'COM_YOORECIPE_VALIDATED_OPTION');
		}
		if (!array_key_exists('notvalidated', $config) || $config['notvalidated']) {
			$options[]	= JHtml::_('select.option', '0', 'COM_YOORECIPE_NOTVALIDATED_OPTION');
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*', 'JALL');
		}
		return $options;
	}
	
	static function j3_validatedOptions($config = array())
	{
		// Build the active state filter options.
		$options	= array();
		if (!array_key_exists('validated', $config) || $config['validated']) {
			$options[]	= JHtml::_('select.option', '1', JText::_('COM_YOORECIPE_VALIDATED_OPTION'));
		}
		if (!array_key_exists('notvalidated', $config) || $config['notvalidated']) {
			$options[]	= JHtml::_('select.option', '0', JText::_('COM_YOORECIPE_NOTVALIDATED_OPTION'));
		}
		if (!array_key_exists('all', $config) || $config['all']) {
			$options[]	= JHtml::_('select.option', '*', JText::_('JALL'));
		}
		return $options;
	}
	
	/**
	 * @param	mixed $value	Either the scalar value, or an object (for backward compatibility, deprecated)
	 * @param	int $i
	 * @param	string $img1	Image for a positive or on value
	 * @param	string $img0	Image for the empty or off value
	 * @param	string $prefix	An optional prefix for the task
	 *
	 * @return	string
	 */
	public static function validated($value, $i, $img1 = 'tick.png', $img0 = 'publish_x.png', $prefix='yoorecipes.')
	{
		if (is_object($value)) {
			$value = $value->validated;
		}

		$img	= $value ? $img1 : $img0;
		$task	= $value ? 'unvalidate' : 'validate';
		$alt	= $value ? JText::_('COM_YOORECIPE_VALIDATED_OPTION') : JText::_('COM_YOORECIPE_NOTVALIDATED_OPTION');
		$action = $value ? JText::_('COM_YOORECIPE_JLIB_HTML_UNVALIDATE_ITEM') : JText::_('COM_YOORECIPE_JLIB_HTML_VALIDATE_ITEM');

$href = '
		<a href="#" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">'.
		JHtml::_('image','admin/'.$img, $alt, NULL, true).'</a>';

		return $href;
	}
	
	public static function hasPicture($value, $i, $img1 = 'tick.png', $img0 = 'publish_x.png', $prefix='yoorecipes.')
	{
		if (is_object($value)) {
			$value = $value->validated;
		}

		$img	= $value=='' ? $img0 : $img1;
		$alt	= $value=='' ? JText::_('COM_YOORECIPE_NO_PICTURE') : JText::_('COM_YOORECIPE_HAS_PICTURE');
		
		$html[] = JHtml::_('image','admin/'.$img, $alt, NULL, true);
		
		$showAsTooltip = true;
		$options = array(
			'onShow' => 'jMediaRefreshPreviewTip',
		);
		$html[] = JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
		
		if ($value && file_exists(JPATH_ROOT . '/' . $value))
		{
			$src = JURI::root() . $value;
		}
		else
		{
			$src = '';
		}

		$attr = array(
			'id' => $i . '_preview',
			'class' => 'media-preview',
			'style' => 'max-width:160px; max-height:100px;'
		);
		$img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $attr);
		$previewImg = '<div id="' . $i . '_preview_img"' . ($src ? '' : ' style="display:none"') . '>' . $img . '</div>';
		$previewImgEmpty = '<div id="' . $i . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'
			. JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';

		$html[] = '<div class="media-preview fltlft">';
		$tooltip = $previewImgEmpty . $previewImg;
		$options = array(
			'title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'),
			'text' => JText::_('JLIB_FORM_MEDIA_PREVIEW_TIP_TITLE'),
			'class' => 'hasTipPreview'
		);
		$html[] = JHtml::tooltip($tooltip, $options);
			
		$html[] = '</div>';
			
		return implode("\n", $html);
	}
	
	public static function sendMailToUserOnValidation($recipe)
	{
		jimport('joomla.mail.helper');
		jimport('joomla.utilities.utility');
		
		$app		= JFactory::getApplication();
		$SiteName	= $app->getCfg('sitename');
		$MailFrom	= $app->getCfg('mailfrom');
		$FromName	= $app->getCfg('fromname');

		$email		= $recipe->author_email;
		$sender		= $FromName;
		$from		= $MailFrom;
		$subject	= JText::sprintf('COM_YOORECIPE_RECIPE_VALIDATION_SUBJECT', $recipe->title);

		// Check for a valid to address
		$error	= false;
		if (! $email  || ! JMailHelper::isEmailAddress($email))
		{
			$error	= JText::sprintf('COM_YOORECIPE_VALIDATION_EMAIL_NOT_SENT', $recipe->title);
			JError::raiseWarning(0, $error);
		}

		// Check for a valid from address
		if (! $from || ! JMailHelper::isEmailAddress($from))
		{
			$error	= JText::sprintf('COM_YOORECIPE_VALIDATION_EMAIL_NOT_SENT', $recipe->title);
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return;
		}

		// Build the message to send
		$body	= JText::sprintf('COM_YOORECIPE_RECIPE_VALIDATION_BODY', $recipe->author_name, $recipe->title, $FromName);

		// Clean the email data
		$subject = JMailHelper::cleanSubject($subject);
		$body	 = JMailHelper::cleanBody($body);
		$sender	 = JMailHelper::cleanAddress($sender);

		$mailer 	= JFactory::getMailer();
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		
		// Send the email
		$send =& $mailer->Send();
		if ( $send !== true )
		{
			JError::raiseNotice(500, JText:: _ ('COM_YOORECIPE_VALIDATION_EMAIL_NOT_SENT'));
		}
	}
	
	/**
	 * Generate a select list for duration in days
	 */
	static function selectDaysFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getDaysFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_days" id="' . $pfx_name . '_days">';
		for ($i=0 ; $i < 5 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
		$html[] = '<label for="'. $pfx_name .'_days" style="min-width:0">' . JText::_('COM_YOORECIPE_DAY') . '</label>';
			
		return implode("\n", $html);
	}
	
	/**
	 * Generate a select list for duration in hours
	 */
	static function selectHoursFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getHoursFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_hours" id="' . $pfx_name . '_hours">';
		for ($i=0 ; $i < 24 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
		$html[] = '<label for="'. $pfx_name .'_hours" style="min-width:0">' . JText::_('COM_YOORECIPE_HOUR') . '</label>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate a select list for duration in minutes
	 */
	static function selectMinutesFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getMinutesFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_minutes" id="'. $pfx_name . '_minutes">';
		for ($i=0 ; $i < 60 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
		$html[] = '<label for="'. $pfx_name .'_minutes" style="min-width:0">' . JText::_('COM_YOORECIPE_MIN') . '</label>';
			
		return implode("\n", $html);
	}
	
	/**
	 * Returns the number of days containes in $duration minutes
	 */
	static function getDaysFromDuration($duration) {
		return floor($duration / 1440);
	}
	
	/**
	 * Returns the number of hours contained in $duration minutes
	 */
	static function getHoursFromDuration($duration) {
		return floor ( ($duration - JHtmlYooRecipeAdminUtils::getDaysFromDuration($duration) * 1440) / 60);
	}
	
	/**
	 * Returns the remainder of minutes contained in $duration minutes
	 */
	static function getMinutesFromDuration($duration) {
		return $duration % 60;
	}
	
	static function decimalToFraction($decimal) {
		$elts = explode('.', $decimal);
		if (count($elts) > 1) {
			if ($elts[0] == '0') {
				$value = JHtmlYooRecipeAdminUtils::getFraction('0.' . $elts[1]);
			} else {
				if ($elts[1] == '0') { 
					$value = $elts[0] . JHtmlYooRecipeAdminUtils::getFraction('0.' . $elts[1]);
				}
				else {
					$value = $elts[0] . ' ' .JHtmlYooRecipeAdminUtils::getFraction('0.' . $elts[1]);
				}
			}
			return $value;
		}
		else {
			return $decimal;
		}
	}
	
	private static function getFraction($value) {
		
		if ($value == '0.0') return '';
		if ($value == '0.5') return '1/2';
		if ($value == '0.33') return '1/3';
		if ($value == '0.67') return '2/3';
		if ($value == '0.25') return '1/4';
		if ($value == '0.75') return '3/4';
		if ($value == '0.2') return '1/5';
		if ($value == '0.4') return '2/5';
		if ($value == '0.6') return '3/5';
		if ($value == '0.8') return '4/5';
		if ($value == '0.17') return '1/6';
		if ($value == '0.83') return '5/6';
		if ($value == '0.14') return '1/7';
		if ($value == '0.29') return '2/7';
		if ($value == '0.43') return '3/7';
		if ($value == '0.57') return '4/7';
		if ($value == '0.71') return '5/7';
		if ($value == '0.86') return '6/7';
		if ($value == '0.13') return '1/8';
		if ($value == '0.38') return '3/8';
		if ($value == '0.63') return '5/8';
		if ($value == '0.88') return '7/8';
		if ($value == '0.11') return '1/9';
		if ($value == '0.22') return '2/9';
		if ($value == '0.44') return '4/9';
		if ($value == '0.56') return '5/9';
		if ($value == '0.78') return '7/9';
		if ($value == '0.89') return '8/9';
		if ($value == '0.1') return '1/10';
		if ($value == '0.3') return '3/10';
		if ($value == '0.7') return '7/10';
		if ($value == '0.9') return '9/10';
		if ($value == '0.08') return '1/12';
		return $value;
	}
	
	/**
	 * Function to build ingredients objects from post data
	 */
	public function buildIngredientsFromRequest() {
	
		$input 	= JFactory::getApplication()->input;
		
		$jform 	= $input->get('jform', '', 'ARRAY');
		$post 	= $input->get('post', '', 'ARRAY');
		
		$quantities			= $input->get('quantity', array(), 'ARRAY');
		$units				= $input->get('unit', array(), 'ARRAY');
		$ingr_descriptions	= $input->get('ingr_description', array(), 'ARRAY');
		
		// Retrieve ingredients
		if (isset($quantities) && isset($units) && isset($ingr_descriptions)) {
			
			$recipeId				= $jform['id'];
			//$ingrIds				= $post['ingrId'];
			$orderings				= $input->get('ordering', array(), 'ARRAY');
			$prices					= $input->get('price', array(), 'ARRAY');
			$groups					= $input->get('group', array(), 'ARRAY');
			
			$ingredients;
			$cnt = 1;
			for ($i = 0 ; $i < count($quantities) ; $i++) {
				
				// Change decimal separator if needed
				$qty = str_replace(',', '.', $quantities[$i]);
				$qtyToNum;
				
				if (strpos($qty, '/') == false) {
					$qtyToNum = $qty;
				} else {
				
					// Turn fractions into decimal value if applicable
					$fraction = array('whole' => 0);
					
					preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $qty, $fraction);
					if ($fraction['denominator'] != 0) {
						$qtyToNum = $fraction['whole'] + $fraction['numerator']/$fraction['denominator'];
					} else {
						$qtyToNum = 0;
					}
				}
				
				if (!empty($ingr_descriptions[$i])) {
				
					$crtIngredient 				= new stdClass();
					//$crtIngredient->id 			= (int) $ingrIds[$i];
					$crtIngredient->recipe_id	= $recipeId;
					$crtIngredient->quantity 	= (is_numeric($qtyToNum)) ? $qtyToNum : 0; // Allow quantities to be empty
					$crtIngredient->unit 		= $units[$i];
					$crtIngredient->description = $ingr_descriptions[$i];
					$crtIngredient->ordering	= ($orderings[$i] == '') ? $cnt++ : $orderings[$i];
					$crtIngredient->price		= $prices[$i];
					$crtIngredient->group_id	= $groups[$i];
					
					$ingredients[] = $crtIngredient;
				}
			}	
		}
		else {
			Jerror::raiseWarning(500, JText::_('COM_YOORECIPE_NO_INGREDIENTS'));
			return false;
		}
		
		return $ingredients;
	}
	
	/**
	 * Function to build tags objects from post data
	 */
	public function buildTagsFromRequest() {
		
		// Retrieve tags
		$tags = array();
		
		$input 		= JFactory::getApplication()->input;
		$jform		= $input->get('jform', '', 'ARRAY');
		$withTags 	= $input->get('withTags', array(), 'ARRAY');
		
		if (isset($withTags)) {
			
			for ($i = 0 ; $i < count($withTags) ; $i++) {
			
				$crtTag	 			= new stdClass();
				$crtTag->recipe_id 	= $jform['id'];
				$crtTag->tag_value 	= $withTags[$i];
				
				$tags[] = $crtTag;
			}
		}
		
		return $tags;
	}
	
}