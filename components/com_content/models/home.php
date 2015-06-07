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
 * This models supports retrieving lists of article Home.
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.6
 */
class ContentModelHome extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_content.home';

	/**
	 * The category context (allows other extensions to derived from this model).
	 *
	 * @var		string
	 */
	protected $_extension = 'com_content';

	private $_parent = null;

	private $_items = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
		$parentId = $app->input->getInt('id');
		$this->setState('filter.parentId', $parentId);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id	A prefix for the store id.
	 *
	 * @return  string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.extension');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.parentId');

		return parent::getStoreId($id);
	}

	/**
	 * Redefine the function an add some properties to make the styling more easy
	 *
	 * @param   bool	$recursive	True if you want to return children recursively.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 * @since   1.6
	 */
	public function getItems($recursive = false)
	{
		if (!count($this->_items))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_num_articles_cat', 1) || !$params->get('show_empty_categories_cat', 0);
			$categories = JCategories::getInstance('Content', $options);
			$this->_parent = $categories->get($this->getState('filter.parentId', 'root'));

			if (is_object($this->_parent))
			{
				$this->_items = $this->_parent->getChildren($recursive);
			}
			else {
				$this->_items = false;
			}
		}

		return $this->_items;
	}

	public function getParent()
	{
		if (!is_object($this->_parent))
		{
			$this->getItems();
		}

		return $this->_parent;
	}
	
	public function getArticles($catId,$groupBy = null,$orderBy='publish_up',$featured = false,$offset=0,$limit=0,$hasImage = false,$iswhatsnew = false,$homepageFeatured = false)
	{
		$whereCat = '';
		$limitSql = '';
		$orderSql = '';
		$whereFeatured = '';
		$groupSql = '';
		$imageArticles = array();

		if($groupBy != null)
		{
			$groupSql = " GROUP BY a.".$groupBy;
		}
		
		if(is_array($catId) && !empty($catId))
		{
			$whereCat = " AND a.catid IN (".implode(",", $catId).")";
		}
		else if(is_numeric($catId) && $catId != 0)
		{
			//$whereCat = " AND a.catid=".(int)$catId;
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($catId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$whereCat = ' AND c.lft >= '.(int) $lft;
			$whereCat .= ' AND c.rgt <= '.(int) $rgt;
		}
		
		if($limit!=0)
		{
			$limitSql = " LIMIT ".$offset.",".$limit;
		}
		
		if($orderBy != '')
		{
			$orderSql = " ORDER BY a.".$orderBy;
		}
		
		if($featured)
		{
			$whereFeatured = " AND a.featured=1 ";
		}
		
		if($homepageFeatured)
		{
			$whereFeatured .= " AND a.homepagefeatured=1 ";
		}
		
        $whereImage = " ";
		if($hasImage)
		{
			$whereImage = " AND a.images != ''";
		}
		
		// Filter by start and end dates.
		$db = JFactory::getDBO ();
		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();

		$nowDate = $db->quote($date->toSql());

		$publishQuery = ' AND (a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ') AND (a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ') ';
		
		$sql = "SELECT a.id,a.title,a.introtext,a.sponsored,a.fulltext,a.alias,a.publish_up,a.created_by,a.state,a.images,a.access,a.catid, c.alias AS category_alias,c.title AS category_title FROM #__content AS a LEFT JOIN #__categories AS c on c.id = a.catid WHERE c.published =1 AND a.state =1 ".$publishQuery.$whereFeatured.$whereImage.$whereCat.$groupSql.$orderSql.$limitSql; 
		$db->setQuery($sql);
		$result = $db->loadObjectList('id');
		foreach($result as &$item)
		{
			$item->slug			= $item->alias ? ($item->id.':'.$item->alias) : $item->id;
			$item->catslug		= $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;			
			$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			$item->text = $item->introtext.' '.$item->fulltext;
			$item->images = json_decode($item->images,true);
			
			if($iswhatsnew)
			{
				if(isset($item->images['image_intro']) && !empty($item->images['image_intro'])) 
				{
					  $item->showImage = $item->images['image_intro'];
				}else if(isset($item->images['image_fulltext']) && !empty($item->images['image_fulltext'])){
					  $item->showImage = $item->images['image_fulltext'];
				}else {  
					$item->showImage = JURI::base()."templates/protostar/images/image_reserve.png"; 
				}
			}else
			{
				if(isset($item->images['image_fulltext']) && !empty($item->images['image_fulltext'])){
					  $item->showImage = $item->images['image_fulltext'];
				}else if(isset($item->images['image_intro']) && !empty($item->images['image_intro'])) 
				{
					  $item->showImage = $item->images['image_intro'];
				}else {  
					$item->showImage = JURI::base()."templates/protostar/images/image_reserve.png"; 
				}
			}			
			
            $imageArticles[] = $item;
		}
		if($hasImage)
		{
			return $imageArticles;
		}
		return $result;
		
	}
}
