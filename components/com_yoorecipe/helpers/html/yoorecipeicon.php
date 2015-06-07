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

// no direct access
defined('_JEXEC') or die;

/**
 * YooRecipe Component HTML Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_yoorecipe
 */
class JHtmlYooRecipeIcon
{

	/**
	* Generate send to a friend email icon
	*/
	static function email($recipe, $params, $attribs = array())
	{
		require_once(JPATH_SITE.'/components/com_mailto/helpers/mailto.php');
		$uri	= JURI::getInstance();
		$base	= $uri->toString(array('scheme', 'host', 'port'));
		$template = JFactory::getApplication()->getTemplate();
		//$link	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($recipe->slug, $recipe->catid) , false);
		$link	= $base.JRoute::_('index.php?option=com_yoorecipe&task=viewRecipe&id='. $recipe->slug , false);
		$url	= 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($link);

		$status = 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get('show_icons', 1)) {
			$text = JHtml::_('image','system/emailButton.png', JText::_('JGLOBAL_EMAIL'), NULL, true);
		} else {
			$text = JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']	= JText::_('JGLOBAL_EMAIL');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

		$output = JHtml::_('link',JRoute::_($url), $text, $attribs);
		return $output;
	}

	/**
	* Generate print popup icon
	*/
	static function print_popup($recipe, $params, $attribs = array())
	{
		$url  = 'index.php?option=com_yoorecipe&amp;task=viewRecipe&amp;id='. $recipe->slug;
		$url .= '&amp;tmpl=component&amp;print=1';
				
		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=940,height=480,directories=no,location=no';

		// checks template image directory for image, if non found default are loaded
		if ($params->get('show_icons', 1)) {
			$text = JHtml::_('image','system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
		} else {
			$text = JText::_('JGLOBAL_PRINT');
		}

		$attribs['title']	= JText::_('JGLOBAL_PRINT');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']		= 'nofollow';

		return JHtml::_('link', JRoute::_($url), $text, $attribs);
	}

	/**
	* Generate print screen icon
	*/
	static function print_screen($recipe, $params, $attribs = array())
	{
		// checks template image directory for image, if non found default are loaded
		if ($params->get('show_icons', 1)) {
			$text = JHtml::_('image','system/pdf_button.png', JText::_('JGLOBAL_PRINT'), NULL, true);
		} else {
			$text = JText::_('JGLOBAL_PRINT');
		}
		return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
	}
	
	/**
	* Generate add to/remove from favourites icon
	*/
	static function favourites($recipe, $params)
	{
		$attribs = array();
		
		if ($recipe->favourite != 1)
		{
			// checks template image directory for image, if non found default are loaded
			if ($params->get('show_icons', 1))
			{
				$attribs['title'] = JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES');
				$text = JHtml::_('image','com_yoorecipe/add-to-favourites.png', JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES'), $attribs, true);
			} else {
				$text = JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES');
			}
			$html = '<a href="#" onclick="addToFavourites(' . $recipe->id . ');return false;">'.$text.'</a>';
		} 
		else {
			
			// checks template image directory for image, if non found default are loaded
			if ($params->get('show_icons', 1)) {

				$attribs['title'] = JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES');
				$text = JHtml::_('image','com_yoorecipe/favourites.png', JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES'), $attribs, true);
			} else {
				$text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
			}
			$html = '<a href="#" onclick="removeFromFavourites(' . $recipe->id . ');return false;">'.$text.'</a>';
		}

		return $html;
	}

}
