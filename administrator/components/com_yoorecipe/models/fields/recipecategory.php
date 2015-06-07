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
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Recipe Category Form Field class for the YooRecipe component
 */
class JFormFieldRecipeCategory extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'RecipeCategory';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__yoorecipe_category');
		$db->setQuery((string)$query);
		$categories = $db->loadObjectList();
		$options = array();
		if ($categories)
		{
			foreach($categories as $category) 
			{
				$options[] = JHtml::_('select.option', $category->id, JText::_('$category->title'));
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}