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
 * This models supports retrieving a category, the articles associated with the category,
 * sibling, child and parent categories.
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.5
 */
class ContentModelCategory extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_content.category';

	/**
	 * The category that applies.
	 *
	 * @access	protected
	 * @var		object
	 */
	protected $_category = null;

	/**
	 * The list of other newfeed categories.
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_categories = null;

	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'hits', 'a.hits',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'author', 'a.author'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * return	void
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('site');
		$pk  = $app->input->getInt('id');

		$this->setState('category.id', $pk);

		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params', $mergedParams);
		$user		= JFactory::getUser();
				// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		if ((!$user->authorise('core.edit.state', 'com_content')) &&  (!$user->authorise('core.edit', 'com_content'))){
			// limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);
			// Filter by start and end dates.
			$nullDate = $db->quote($db->getNullDate());
			$nowDate = $db->quote(JFactory::getDate()->toSQL());

			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
				->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}
		else
		{
			$this->setState('filter.published', array(0, 1, 2));
		}

		// process show_noauth parameter
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}

		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		// filter.order
		$itemid = $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'a.ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir',
			'filter_order_Dir', '', 'cmd');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));

		// set limit for query. If list, use parameter. If blog, add blog parameters for limit.
		if (($app->input->get('layout') == 'blog') || $params->get('layout_type') == 'blog')
		{
			$limit = $params->get('num_leading_articles') + $params->get('num_intro_articles') + $params->get('num_links');
			$this->setState('list.links', $params->get('num_links'));
		}
		else
		{
			$limit = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		}

		$this->setState('list.limit', $limit);

		// set the depth of the category query based on parameter
		$showSubcategories = $params->get('show_subcategory_content', '0');

		if ($showSubcategories)
		{
			$this->setState('filter.max_category_levels', $params->get('show_subcategory_content', '1'));
			$this->setState('filter.subcategories', true);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());

		$this->setState('layout', $app->input->get('layout'));

	}

	/**
	 * Get the articles in the category
	 *
	 * @return  mixed  An array of articles or false if an error occurs.
	 * @since   1.5
	 */
	function getItems()
	{
		$limit = $this->getState('list.limit');

		if ($this->_articles === null && $category = $this->getCategory())
		{
			$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			$model->setState('params', JFactory::getApplication()->getParams());
			$model->setState('filter.category_id', $category->id);
			$model->setState('filter.published', $this->getState('filter.published'));
			$model->setState('filter.access', $this->getState('filter.access'));
			$model->setState('filter.language', $this->getState('filter.language'));
			$model->setState('list.ordering', $this->_buildContentOrderBy());
			$model->setState('list.start', $this->getState('list.start'));
			$model->setState('list.limit', $limit);
			$model->setState('list.direction', $this->getState('list.direction'));
			$model->setState('list.filter', $this->getState('list.filter'));
			// filter.subcategories indicates whether to include articles from subcategories in the list or blog
			$model->setState('filter.subcategories', $this->getState('filter.subcategories'));
			$model->setState('filter.max_category_levels', $this->setState('filter.max_category_levels'));
			$model->setState('list.links', $this->getState('list.links'));
			if($category->id==178)
			{
				$model->setState('list.ordering', 'a.publish_up');
				$model->setState('list.direction', 'DESC');
			}

			if ($limit >= 0)
			{
				$this->_articles = $model->getItems();

				if ($this->_articles === false)
				{
					$this->setError($model->getError());
				}
			}
			else
			{
				$this->_articles = array();
			}

			$this->_pagination = $model->getPagination();
		}

		return $this->_articles;
	}

	/**
	 * Build the orderby for the query
	 *
	 * @return  string	$orderby portion of query
	 * @since   1.5
	 */
	protected function _buildContentOrderBy()
	{
		$app		= JFactory::getApplication('site');
		$db			= $this->getDbo();
		$params		= $this->state->params;
		$itemid		= $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol	= $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn	= $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby	= ' ';

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = null;
		}

		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}

		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}

		$articleOrderby		= $params->get('orderby_sec', 'rdate');
		$articleOrderDate	= $params->get('order_date');
		$categoryOrderby	= $params->def('orderby_pri', '');
		$secondary			= ContentHelperQuery::orderbySecondary($articleOrderby, $articleOrderDate) . ', ';
		$primary			= ContentHelperQuery::orderbyPrimary($categoryOrderby);

		$orderby .= $primary . ' ' . $secondary . ' a.created ';

		return $orderby;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			return null;
		}
		return $this->_pagination;
	}

	/**
	 * Method to get category data for the current category
	 *
	 * @param   integer  An optional ID
	 *
	 * @return  object
	 * @since   1.5
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			if ( isset( $this->state->params ) )
			{
				$params = $this->state->params;
				$options = array();
				$options['countItems'] = $params->get('show_cat_num_articles', 1) || !$params->get('show_empty_categories_cat', 0);
			}
			else {
				$options['countItems'] = 0;
			}

			$categories = JCategories::getInstance('Content', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			// Compute selected asset permissions.
			if (is_object($this->_item))
			{
				$user	= JFactory::getUser();
				$asset	= 'com_content.category.'.$this->_item->id;

				// Check general create permission.
				if ($user->authorise('core.create', $asset))
				{
					$this->_item->getParams()->set('access-create', true);
				}

				// TODO: Why aren't we lazy loading the children and siblings?
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else {
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_parent;
	}

	/**
	 * Get the left sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	/**
	 * Get the right sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		// Order subcategories
		if (count($this->_children))
		{
			$params = $this->getState()->get('params');
			if ($params->get('orderby_pri') == 'alpha' || $params->get('orderby_pri') == 'ralpha')
			{
				jimport('joomla.utilities.arrayhelper');
				JArrayHelper::sortObjects($this->_children, 'title', ($params->get('orderby_pri') == 'alpha') ? 1 : -1);
			}
		}

		return $this->_children;
	}

	/**
	 * Increment the hit counter for the category.
	 *
	 * @param   int  $pk  Optional primary key of the category to increment.
	 *
	 * @return  boolean True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('category.id');

		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->update('#__categories')
			->set('hits = hits + 1')
			->where('id = ' . (int) $pk);
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}
	
	public function getArticles($catId,$groupBy = null,$orderBy='publish_up',$featured = false,$offset=0,$limit=0,$iswhatsnew = false)
	{
	
		/**
        * It takes too much time to run such a single query.
        * We will just use cache to replace this.
        */
        $cacheLife = 3600; // Define the cache life time.
        $cacheDir = JPATH_CACHE . "/howe/articles/";
        $cacheFile = $cacheDir . md5($limit.$catId) . ".cache";
        
        
        $result = getCache($cacheFile, $cacheLife);
		$result = false;
        if($result === false){		
			$whereCat = '';
			$limitSql = '';
			$orderSql = '';
			$whereFeatured = '';
			$groupSql = '';

			
			if($groupBy != null)
			{
				$groupSql = " GROUP BY a.".$groupBy;
			}
			
			if(is_array($catId) && !empty($catId))
			{
				$whereCat = " AND a.catid IN (".implode(",", $catId).")";
			}
			else if(!is_array($catId) && $catId != 0)
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
			else if(!$iswhatsnew)
			{
				$whereFeatured = " AND a.featured!=1 ";
			}
		
			// Filter by start and end dates.
			$db = JFactory::getDBO ();
			$nullDate = $db->quote($db->getNullDate());
			$date = JFactory::getDate();

			$nowDate = $db->quote($date->toSql());

			$publishQuery = ' AND (a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ') AND (a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ') ';
						
			$sql = "SELECT a.id,a.title,a.sponsored,a.introtext,a.fulltext,a.alias,a.publish_up,a.created_by,a.state,a.images,a.access,a.catid, c.alias AS category_alias,c.title AS category_title FROM #__content AS a LEFT JOIN #__categories AS c on c.id = a.catid WHERE c.published =1 AND a.state =1 ".$publishQuery.$whereFeatured.$whereCat.$groupSql.$orderSql.$limitSql; 
			//echo "<div id='testsql' style='display:none;color:white;'>";print_r($catId.' '.$sql);echo "</div>";
			$db->setQuery($sql);
			$result = $db->loadObjectList('id');
			foreach($result as &$item)
			{
				$item->slug			= $item->alias ? ($item->id.':'.$item->alias) : $item->id;
				$item->catslug		= $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;
				$item->images = json_decode($item->images,true);
				if(isset($item->images['image_intro']) && !empty($item->images['image_intro'])) 
				{
					  $item->showImage = $item->images['image_intro'];
				}else if(isset($item->images['image_fulltext']) && !empty($item->images['image_fulltext'])){
					  $item->showImage = $item->images['image_fulltext'];
				}else {  
					$item->showImage = JURI::base()."templates/protostar/images/image_reserve.png"; 
				}
				
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
				
				$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
				$item->text = $item->introtext.' '.$item->fulltext;
			}			
			// Save the Cache
            saveCache($cacheFile,$result);       
		}
		return $result;
		
	}
	
	public function getCategories($catIds = array())
	{
        /**
        * It takes too much time to run such a single query.
        * We will just use cache to replace this.
        */
        $cacheLife = 86400; // Define the cache life time.
        $cacheDir = JPATH_CACHE . "/leon/";
        
        $catIdsStr = empty($catIds)? 'NonCategory': implode($catIds,"_");
        $cacheFile = $cacheDir . "category_" .  md5($catIdsStr) . ".cache";
        
        //exit('cate'); 
        
        $return = getCache($cacheFile, $cacheLife);
        if($return === false){
            
		    $categoryItems = array();
		    $options['countItems'] = 0;		
		    $result = array();
		    $subResult = array();
		    
		    $categories = JCategories::getInstance('Content', $options);
		    if(!empty($catIds))
		    {
			    foreach($catIds as $catId)
			    {
				    $tempCat = $categories->get($catId);
				    $db = JFactory::getDBO ();
				    $sql = "SELECT c.id,c.asset_id,c.parent_id,c.lft,c.rgt,c.level,c.path,c.title,c.alias,c.published FROM #__categories AS c WHERE c.published=1 AND c.id=".(int)$catId; 
				    $db->setQuery($sql);
				    $result = $db->loadObjectList();
				    
				    /*$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

				    $item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

				    // No link for ROOT category
				    if ($item->parent_alias == 'root')
				    {
					    $item->parent_slug = null;
				    }

				    $item->catslug		= $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;*/

				    if(!empty($result))
				    {
					    $db = JFactory::getDBO ();
					    $sql = "SELECT c.id,c.asset_id,c.parent_id,c.lft,c.rgt,c.level,c.path,c.title,c.alias,c.published FROM #__categories AS c WHERE c.published=1 AND c.lft>=".(int)$result[0]->lft." AND c.rgt<=".(int)$result[0]->rgt." AND c.id !=".(int)$catId; 
					    $db->setQuery($sql);
					    $subResult = $db->loadObjectList();
				    }				
				    $result[0]->children = $subResult;
				    $categoryItems[] = $result[0];
			    }
			    
			    if(!empty($categoryItems))
			    {
				    foreach($categoryItems as $parent)
				    {
					    $parent->slug = $parent->alias ? ($parent->id . ':' . $parent->alias) : $parent->id;
					    $parent->parent_slug = ($parent->parent_alias) ? ($parent->parent_id . ':' . $parent->parent_alias) : $parent->parent_id;
					    $parent->link = JRoute::_(ContentHelperRoute::getCategoryRoute($parent->slug));
					    // No link for ROOT category
					    if ($parent->parent_id == 1)
					    {
						    $parent->parent_slug = null;
					    }
					    
					    if(!empty($parent->children))
					    {
						    foreach($parent->children as $child)
						    {
							    $child->slug = $child->alias ? ($child->id . ':' . $child->alias) : $child->id;
							    $child->parent_slug = ($parent->alias) ? ($child->parent_id . ':' . $parent->alias) : $child->parent_id;
							    $child->link = JRoute::_(ContentHelperRoute::getCategoryRoute($child->slug));
						    }
					    }
				    }
			    }
		    }
            $return = $categoryItems;
            // Save the Cache
            saveCache($cacheFile,$return);           
        }
        return $return;
	}
}
