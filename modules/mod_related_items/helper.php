<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

/**
 * Helper for mod_related_items
 *
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 * @since       1.5
 */
abstract class ModRelatedItemsHelper
{
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$date = JFactory::getDate();
		$maximum = (int) $params->get('maximum', 5);

		$option = $app->input->get('option');
		$view = $app->input->get('view');

		$temp = $app->input->getString('id');
		$temp = explode(':', $temp);
		$id = $temp[0];

		$nullDate = $db->getNullDate();
		$now = $date->toSql();
		$related = array();
		$query = $db->getQuery(true);
		$exIDs = array();

		if ($option == 'com_content' && $view == 'article' && $id)
		{
			// select the meta keywords from the item

			$query->select('metakey')
				->select('catid')
				->from('#__content')
				->where('id = ' . (int) $id);
			$db->setQuery($query);
			$result = $db->loadObjectList();
			$catid=$result[0]->catid; //print_r('sub catid:'.$catid."<br>");
			$metaKeyValue = $result[0]->metakey;
			
			//Get the category id (not sub-category)
			$query->clear()->select('parent_id')
				->from('#__categories')
				->where('id = ' . (int) $catid);
			$db->setQuery($query);
			$result = $db->loadObject();
			$p_catid=$result->parent_id; 
			if($p_catid==1)
			{
				$p_catid = $catid;
			}
			//print_r('catid:'.$p_catid."<br>");

			if ($metakey = trim($metaKeyValue))
			{
				// explode the meta keys on a comma
				$keys = explode(',', $metakey);
				$likes = array();

				// assemble any non-blank word(s)
				foreach ($keys as $key)
				{
					$key = trim($key);
					if ($key)
					{
						$likes[] = $db->escape($key);
					}
				}

				if (count($likes))
				{
					// select other items based on the metakey field 'like' the keys found
					$query->clear()
						->select('a.id')
						->select('a.title')
						->select('DATE_FORMAT(a.created, "%Y-%m-%d") as created')
						->select('a.catid')
						->select('a.images')
						->select('cc.access AS cat_access')
						->select('cc.published AS cat_state');

					// Sqlsrv changes
					$case_when = ' CASE WHEN ';
					$case_when .= $query->charLength('a.alias', '!=', '0');
					$case_when .= ' THEN ';
					$a_id = $query->castAsChar('a.id');
					$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
					$case_when .= ' ELSE ';
					$case_when .= $a_id . ' END as slug';
					$query->select($case_when);

					$case_when = ' CASE WHEN ';
					$case_when .= $query->charLength('cc.alias', '!=', '0');
					$case_when .= ' THEN ';
					$c_id = $query->castAsChar('cc.id'); 
					$case_when .= $query->concatenate(array($c_id, 'cc.alias'), ':');
					$case_when .= ' ELSE ';
					$case_when .= $c_id . ' END as catslug';
					$query->select($case_when)
						->from('#__content AS a')
						->join('LEFT', '#__content_frontpage AS f ON f.content_id = a.id')
						->join('LEFT', '#__categories AS cc ON cc.id = a.catid')
						->where('a.id != ' . (int) $id)
						->where('a.state = 1')
						->where('a.catid='.(int) $p_catid)
						->where("a.images like '".'{"image_intro":"images%'."'")
						->where('a.access IN (' . $groups . ')');
					$concat_string = $query->concatenate(array('","', ' REPLACE(a.metakey, ", ", ",")', ' ","'));
					$query->where('(' . $concat_string . ' LIKE "%' . implode('%" OR ' . $concat_string . ' LIKE "%', $likes) . '%")') //remove single space after commas in keywords)
						->where('(a.publish_up = ' . $db->quote($nullDate) . ' OR a.publish_up <= ' . $db->quote($now) . ')')
						->where('(a.publish_down = ' . $db->quote($nullDate) . ' OR a.publish_down >= ' . $db->quote($now) . ')');

					// Filter by language
					if (JLanguageMultilang::isEnabled())
					{
						$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
					}
					
					$query->order('a.publish_up desc');
					
					$db->setQuery($query, 0, $maximum);
					$temp = $db->loadObjectList();
					
					/*$resultCount = count($temp); 
					if($resultCount<$maximum)
					{
						$ids = array();
						if(!empty($temp))
						{
							foreach($temp as $item)
							{
								$ids[] = $item->id;
							}
						}
						$exIDs = $ids;
						// select other items based on the metakey field 'like' the keys found
						$query->clear()
							->select('a.id')
							->select('a.title')
							->select('DATE_FORMAT(a.created, "%Y-%m-%d") as created')
							->select('a.catid')
							->select('a.images')
							->select('cc.access AS cat_access')
							->select('cc.published AS cat_state');

						// Sqlsrv changes
						$case_when = ' CASE WHEN ';
						$case_when .= $query->charLength('a.alias', '!=', '0');
						$case_when .= ' THEN ';
						$a_id = $query->castAsChar('a.id');
						$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
						$case_when .= ' ELSE ';
						$case_when .= $a_id . ' END as slug';
						$query->select($case_when);

						$case_when = ' CASE WHEN ';
						$case_when .= $query->charLength('cc.alias', '!=', '0');
						$case_when .= ' THEN ';
						$c_id = $query->castAsChar('cc.id'); 
						$case_when .= $query->concatenate(array($c_id, 'cc.alias'), ':');
						$case_when .= ' ELSE ';
						$case_when .= $c_id . ' END as catslug';
						$query->select($case_when)
							->from('#__content AS a')
							->join('LEFT', '#__content_frontpage AS f ON f.content_id = a.id')
							->join('LEFT', '#__categories AS cc ON cc.id = a.catid')
							->where('a.id NOT IN ('.implode(',',$ids).')')
							->where('a.state = 1')
							->where('a.catid='.(int) $catid)
							->where('a.access IN (' . $groups . ')');
						$query->where('(a.publish_up = ' . $db->quote($nullDate) . ' OR a.publish_up <= ' . $db->quote($now) . ')')
							->where('(a.publish_down = ' . $db->quote($nullDate) . ' OR a.publish_down >= ' . $db->quote($now) . ')');

						$db->setQuery($query, 0, $maximum - $resultCount);
						$tempAdd = $db->loadObjectList();
						$temp = array_merge($temp,$tempAdd);
					}	*/				
					
					if (count($temp))
					{
						foreach ($temp as $row)
						{
							if ($row->cat_state == 1)
							{
								$row->route = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug));
								$row->images = json_decode($row->images,true);
								$related[] = $row;
								$exIDs[] = $row->id;
							}
						}
					}
					unset ($temp);
				}
			}
			if(count($related)<$maximum)
			{
				// select other items based on the metakey field 'like' the keys found
				$query->clear()
					->select('a.id')
					->select('a.title')
					->select('DATE_FORMAT(a.created, "%Y-%m-%d") as created')
					->select('a.catid')
					->select('a.images')
					->select('cc.access AS cat_access')
					->select('cc.published AS cat_state');

				// Sqlsrv changes
				$case_when = ' CASE WHEN ';
				$case_when .= $query->charLength('a.alias', '!=', '0');
				$case_when .= ' THEN ';
				$a_id = $query->castAsChar('a.id');
				$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
				$case_when .= ' ELSE ';
				$case_when .= $a_id . ' END as slug';
				$query->select($case_when);

				$case_when = ' CASE WHEN ';
				$case_when .= $query->charLength('cc.alias', '!=', '0');
				$case_when .= ' THEN ';
				$c_id = $query->castAsChar('cc.id'); 
				$case_when .= $query->concatenate(array($c_id, 'cc.alias'), ':');
				$case_when .= ' ELSE ';
				$case_when .= $c_id . ' END as catslug';
				$query->select($case_when)
					->from('#__content AS a')
					->join('LEFT', '#__content_frontpage AS f ON f.content_id = a.id')
					->join('LEFT', '#__categories AS cc ON cc.id = a.catid')
					->where('a.id != ' . (int) $id)					
					->where('a.state = 1')
					->where('a.catid='.(int) $p_catid)
					->where("a.images like '".'{"image_intro":"images%'."'")
					->where('a.access IN (' . $groups . ')');
				$query->where('(a.publish_up = ' . $db->quote($nullDate) . ' OR a.publish_up <= ' . $db->quote($now) . ')')
					->where('(a.publish_down = ' . $db->quote($nullDate) . ' OR a.publish_down >= ' . $db->quote($now) . ')');
				if(!empty($exIDs))
				{
					$query->where('a.id NOT IN ('.implode(',',$exIDs).')');
				}
				$query->order('a.publish_up desc');
				$db->setQuery($query, 0, $maximum - count($related));
				$tempAdd = $db->loadObjectList();				
				
				if (count($tempAdd))
				{
					foreach ($tempAdd as $row)
					{
						if ($row->cat_state == 1)
						{
							$row->route = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug));
							$row->images = json_decode($row->images,true);
							$related[] = $row;
							$exIDs[] = $row->id;
						}
					}
				}
				unset ($tempAdd);
			}			
		}

		return $related;
	}
}
