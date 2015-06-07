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
 * YooRecipe Form Field class for the YooRecipe component
 */
class JFormFieldYooRecipe extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'YooRecipe';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, title, alias, description, category_id, created_by, preparation, servings_type, nb_persons, difficulty, cost, creation_date, preparation_time, cook_time, wait_time, picture, published, validated, featured, nb_views, note');
		$query->from('#__yoorecipe');
		$query->where('published = 1');
		$db->setQuery((string)$query);
		$recipes = $db->loadObjectList();
		$options = array();
		if ($recipes)
		{
			foreach($recipes as $recipe) 
			{
				$options[] = JHtml::_('select.option', $recipe->id, JText::_($recipe->title));
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}