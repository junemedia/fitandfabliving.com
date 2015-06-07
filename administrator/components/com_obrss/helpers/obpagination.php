<?php
class obPagination extends JPagination {
	
	public function getListFooter()
	{
		$app = JFactory::getApplication();
	
		$list = array();
		$list['prefix'] = $this->prefix;
		$list['limit'] = $this->limit;
		$list['limitstart'] = $this->limitstart;
		$list['total'] = $this->total;
		$list['limitfield'] = $this->getLimitBox();
		$list['pagescounter'] = $this->getPagesCounter();
		$list['pageslinks'] = $this->getPagesLinks();
	
		return $this->pagination_list_footer($list);
		/* $chromePath = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';
		if (file_exists($chromePath))
		{
			include_once $chromePath;
			if (function_exists('pagination_list_footer'))
			{
				return pagination_list_footer($list);
			}
		} */
// 		return $this->_list_footer($list);
	}
	
	/**
	 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x.
	 *
	 * @return  string  Pagination page list string.
	 *
	 * @since   11.1
	 */
	public function getPagesLinks()
	{
		$app = JFactory::getApplication();

		// Build the page navigation list.
		$data = $this->_buildDataObject();

		$list = array();
		$list['prefix'] = $this->prefix;

		$itemOverride = false;
		$listOverride = false;

// 		$chromePath = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';
// 		if (file_exists($chromePath))
// 		{
// 			include_once $chromePath;
// 			if (function_exists('pagination_item_active') && function_exists('pagination_item_inactive'))
// 			{
// 				$itemOverride = true;
// 			}
// 			if (function_exists('pagination_list_render'))
// 			{
// 				$listOverride = true;
// 			}
// 		}

		// Build the select list
		if ($data->all->base !== null)
		{
			$list['all']['active'] = true;
			$list['all']['data'] = ($itemOverride) ? pagination_item_active($data->all) : $this->_item_active($data->all);
		}
		else
		{
			$list['all']['active'] = false;
			$list['all']['data'] = ($itemOverride) ? pagination_item_inactive($data->all) : $this->_item_inactive($data->all);
		}

		if ($data->start->base !== null)
		{
			$list['start']['active'] = true;
			$list['start']['data'] = ($itemOverride) ? pagination_item_active($data->start) : $this->_item_active($data->start);
		}
		else
		{
			$list['start']['active'] = false;
			$list['start']['data'] = ($itemOverride) ? pagination_item_inactive($data->start) : $this->_item_inactive($data->start);
		}
		if ($data->previous->base !== null)
		{
			$list['previous']['active'] = true;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
		}
		else
		{
			$list['previous']['active'] = false;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_inactive($data->previous) : $this->_item_inactive($data->previous);
		}

		// Make sure it exists
		$list['pages'] = array();
		foreach ($data->pages as $i => $page)
		{
			if ($page->base !== null)
			{
				$list['pages'][$i]['active'] = true;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_active($page) : $this->_item_active($page);
			}
			else
			{
				$list['pages'][$i]['active'] = false;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_inactive($page) : $this->_item_inactive($page);
			}
		}

		if ($data->next->base !== null)
		{
			$list['next']['active'] = true;
			$list['next']['data'] = ($itemOverride) ? pagination_item_active($data->next) : $this->_item_active($data->next);
		}
		else
		{
			$list['next']['active'] = false;
			$list['next']['data'] = ($itemOverride) ? pagination_item_inactive($data->next) : $this->_item_inactive($data->next);
		}

		if ($data->end->base !== null)
		{
			$list['end']['active'] = true;
			$list['end']['data'] = ($itemOverride) ? pagination_item_active($data->end) : $this->_item_active($data->end);
		}
		else
		{
			$list['end']['active'] = false;
			$list['end']['data'] = ($itemOverride) ? pagination_item_inactive($data->end) : $this->_item_inactive($data->end);
		}

		if ($this->total > $this->limit)
		{
			return ($listOverride) ? pagination_list_render($list) : $this->_list_render($list);
		}
		else
		{
			return '';
		}
	}
	
	function pagination_list_footer_bak($list)
	{
		// Initialise variables.
		$lang = JFactory::getLanguage();
		$html = "<div id=\"obpagination\" class=\"container\"><div class=\"pagination\">\n";
	
		$html .= "\n<div class=\"limit\">".JText::_('JGLOBAL_DISPLAY_NUM').$list['limitfield']."</div>";
		$html .= $list['pageslinks'];
		$html .= "\n<div class=\"limit\">".$list['pagescounter']."</div>";
	
		$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"".$list['limitstart']."\" />";
		$html .= "\n</div></div>";
	
		return $html;
	}
	
	/**
	 * Renders the pagination footer
	 *
	 * @param   array  $list  Array containing pagination footer
	 *
	 * @return  string  HTML markup for the full pagination footer
	 *
	 * @since   3.0
	 */
	function pagination_list_footer($list)
	{
		$html = "<div class=\"pagination pagination-toolbar-bak\">\n";
		$html .= $list['pageslinks'];
		$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"" . $list['limitstart'] . "\" />";
		$html .= "\n</div>";
	
		return $html;
	}
	
	/**
	 * Renders the pagination list
	 *
	 * @param   array  $list  Array containing pagination information
	 *
	 * @return  string  HTML markup for the full pagination object
	 *
	 * @since   3.0
	 */
	function pagination_list_render($list)
	{
		// Calculate to display range of pages
		$currentPage = 1;
		$range = 1;
		$step = 5;
		foreach ($list['pages'] as $k => $page)
		{
			if (!$page['active'])
			{
				$currentPage = $k;
			}
		}
		if ($currentPage >= $step)
		{
			if ($currentPage % $step == 0)
			{
				$range = ceil($currentPage / $step) + 1;
			}
			else
			{
				$range = ceil($currentPage / $step);
			}
		}
	
		$html = '<ul class="pagination-list">';
		$html .= $list['start']['data'];
		$html .= $list['previous']['data'];
	
		foreach ($list['pages'] as $k => $page)
		{
			if (in_array($k, range($range * $step - ($step + 1), $range * $step)))
			{
				if (($k % $step == 0 || $k == $range * $step - ($step + 1)) && $k != $currentPage && $k != $range * $step - $step)
				{
					$page['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $page['data']);
				}
			}
	
			$html .= $page['data'];
		}
	
		$html .= $list['next']['data'];
		$html .= $list['end']['data'];
	
		$html .= '</ul>';
		return $html;
	}
	
	/**
	 * Renders an active item in the pagination block
	 *
	 * @param   JPaginationObject  $item  The current pagination object
	 *
	 * @return  string  HTML markup for active item
	 *
	 * @since   3.0
	 */
	function pagination_item_active(&$item)
	{
		// Check for "Start" item
		if ($item->text == JText::_('JLIB_HTML_START'))
		{
			$display = '<i class="icon-first"></i>';
		}
	
		// Check for "Prev" item
		if ($item->text == JText::_('JPREV'))
		{
			$display = '<i class="icon-previous"></i>';
		}
	
		// Check for "Next" item
		if ($item->text == JText::_('JNEXT'))
		{
			$display = '<i class="icon-next"></i>';
		}
	
		// Check for "End" item
		if ($item->text == JText::_('JLIB_HTML_END'))
		{
			$display = '<i class="icon-last"></i>';
		}
	
		// If the display object isn't set already, just render the item with its text
		if (!isset($display))
		{
			$display = $item->text;
		}
	
		if ($item->base > 0)
		{
			$limit = 'limitstart.value=' . $item->base;
		}
		else
		{
			$limit = 'limitstart.value=0';
		}
	
		return '<li><a href="#" title="' . $item->text . '" onclick="document.adminForm.' . $item->prefix . $limit . '; Joomla.submitform();return false;">' . $display . '</a></li>';
	}
	
	/**
	 * Renders an inactive item in the pagination block
	 *
	 * @param   JPaginationObject  $item  The current pagination object
	 *
	 * @return  string  HTML markup for inactive item
	 *
	 * @since   3.0
	 */
	function pagination_item_inactive(&$item)
	{
		// Check for "Start" item
		if ($item->text == JText::_('JLIB_HTML_START'))
		{
			return '<li class="disabled"><a><i class="icon-first"></i></a></li>';
		}
	
		// Check for "Prev" item
		if ($item->text == JText::_('JPREV'))
		{
			return '<li class="disabled"><a><i class="icon-previous"></i></a></li>';
		}
	
		// Check for "Next" item
		if ($item->text == JText::_('JNEXT'))
		{
			return '<li class="disabled"><a><i class="icon-next"></i></a></li>';
		}
	
		// Check for "End" item
		if ($item->text == JText::_('JLIB_HTML_END'))
		{
			return '<li class="disabled"><a><i class="icon-last"></i></a></li>';
		}
	
		// Check if the item is the active page
		if (isset($item->active) && ($item->active))
		{
			return '<li class="active"><a>' . $item->text . '</a></li>';
		}
	
		// Doesn't match any other condition, render a normal item
		return '<li class="disabled"><a>' . $item->text . '</a></li>';
	}
	
}

?>