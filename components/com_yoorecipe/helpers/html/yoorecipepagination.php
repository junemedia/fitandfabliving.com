<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class JHtmlYooRecipePagination
{
	/**
	 * Creates a dropdown box for selecting how many records to show per page.
	 * Override Joomla native pagination to make it possible to display more than one pagination per page
	 * @return  string  The HTML for the limit # input box.
	 *
	 * @since   11.1
	 */
	public static function getLimitBox($paginationObject)
	{
		$app = JFactory::getApplication();
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$yoorecipe_layout 	= $yooRecipeparams->get('yoorecipe_layout', 'twocols');
		
		// Initialise variables.
		$limits = array();
		
		switch ($yoorecipe_layout) {
			case 'twocols':
				// Make the option list.
				for ($i = 2; $i <= 10; $i += 2)
				{
					$limits[] = JHtml::_('select.option', $i);
				}
				$limits[] = JHtml::_('select.option', 20);
				$limits[] = JHtml::_('select.option', 30);
			break;
			
			default:
				// Make the option list.
				for ($i = 5; $i <= 30; $i += 5)
				{
					$limits[] = JHtml::_('select.option', $i);
				}
			break;
		}

		// Make the option list.
		$limits[] = JHtml::_('select.option', '50', JText::_('J50'));
		// $limits[] = JHtml::_('select.option', '100', JText::_('J100'));
		// $limits[] = JHtml::_('select.option', '0', JText::_('JALL'));

		//$selected = $paginationObject->_viewall ? 0 : $paginationObject->limit;
		$selected = 	$paginationObject->limit;

		// Build the select list.
		if ($app->isAdmin())
		{
			$html = JHtml::_(
				'select.genericlist',
				$limits,
				$paginationObject->prefix . 'limit',
				'class="inputbox" size="1" onchange="updateLimitBox(this);Joomla.submitform();"',
				'value',
				'text',
				$selected
			);
		}
		else
		{
			$html = JHtml::_(
				'select.genericlist',
				$limits,
				$paginationObject->prefix . 'limit',
				'class="inputbox" size="1" onchange="updateLimitBox(this);Joomla.submitform();"',
				'value',
				'text',
				$selected
			);
		}
		return $html;
	}
}