<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content home view.
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.5
 */
class ContentViewHome extends JViewLegacy
{
	protected $state = null;

	protected $item = null;

	protected $items = null;

	protected $categorys = null;
	protected $newItems = array();
	protected $health_items = array();
	protected $fitness_items = array();
	protected $fitnessimage_item = array();
	protected $beauty_items = array();
	protected $weightl_items = array();
	protected $recipes_items = array();
	protected $lifestyle_items = array();
	protected $totalItems = null;
	/**
	 * Display the view
	 *
	 * @return  mixed  False on error, null otherwise.
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$parent		= $this->get('Parent');
		$model = $this->getModel();
		
		//Categories shown in menu
		//Health:163, Fitness:162, Beauty:172, Weight Loss:151, Recipes:173, Lifestyle:178
		$this->categorys = array(163,162,172,151,173,178);
		$sliderItems = $model->getArticles(0,null,'publish_up DESC',false,0,4,false,false,true);		
		$newItems =  array_diff_key($model->getArticles(0,null,'whatsnewfeatured DESC,a.publish_up DESC',false,0,8,false,true),$sliderItems);
		$this->newItems = array_slice($newItems,0,4,true);unset($newItems);
		//$this->totalItems = $model->getArticles($this->categorys,null,'publish_up DESC',true);
		//echo "<pre>";
		//print_r($sliderItems);
		//echo "</pre>";

		$this->health_items = array_diff_key($model->getArticles(163,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		$this->fitness_items = array_diff_key($model->getArticles(162,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		//$this->fitnessimage_item = $model->getArticles(162,null,'publish_up DESC',true,0,1,true);
		$this->beauty_items = array_diff_key($model->getArticles(172,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		$this->weightl_items = array_diff_key($model->getArticles(151,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		$this->recipes_items = array_diff_key($model->getArticles(173,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		$this->lifestyle_items = array_diff_key($model->getArticles(178,null,'publish_up DESC',true,0,16),$this->newItems,$sliderItems);
		unset($sliderItems);


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($items === false)
		{
			JError::raiseError(404, JText::_('COM_CONTENT_ERROR_CATEGORY_NOT_FOUND'));
			return false;
		}

		if ($parent == false)
		{
			JError::raiseError(404, JText::_('COM_CONTENT_ERROR_PARENT_CATEGORY_NOT_FOUND'));
			return false;
		}

		$params = &$state->params;

		$items = array($parent->id => $items);

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->maxLevelcat = $params->get('maxLevelcat', -1);
		$this->params = &$params;
		$this->parent = &$parent;
		$this->items  = &$items;

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
