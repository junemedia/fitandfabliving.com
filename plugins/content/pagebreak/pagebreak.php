<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagebreak
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.utilities.utility');

/**
 * Page break plugin
 *
 * <b>Usage:</b>
 * <code><hr class="system-pagebreak" /></code>
 * <code><hr class="system-pagebreak" title="The page title" /></code>
 * or
 * <code><hr class="system-pagebreak" alt="The first page" /></code>
 * or
 * <code><hr class="system-pagebreak" title="The page title" alt="The first page" /></code>
 * or
 * <code><hr class="system-pagebreak" alt="The first page" title="The page title" /></code>
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.pagebreak
 * @since       1.6
 */
class PlgContentPagebreak extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin that adds a pagebreak into the text and truncates text at that point
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   object   &$row     The article object.  Note $article->text is also available
	 * @param   mixed    &$params  The article params
	 * @param   integer  $page     The 'page' number
	 *
	 * @return  mixed  Always returns void or true
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$canProceed = $context == 'com_content.article';

		if (!$canProceed)
		{
			return;
		}

		$style = $this->params->get('style', 'pages');

		// Expression to search for.
		$regex = '#<hr(.*)class="system-pagebreak"(.*)\/>#iU';

		$input = JFactory::getApplication()->input;

		$print = $input->getBool('print');
		$showall = $input->getBool('showall');

		if (!$this->params->get('enabled', 1))
		{
			$print = true;
		}
        
        // We save the original text first!
        $originalFullText = $row->text;
		
		//echo "<pre>";print_r($originalFullText);echo "</pre>";exit;
        
		if ($print)
		{
			$row->text = preg_replace($regex, '<br />', $row->text);

			return true;
		}

		// Simple performance check to determine whether bot should process further.
		if (JString::strpos($row->text, 'class="system-pagebreak') === false)
		{
			return true;
		}

		$view = $input->getString('view');
		$full = $input->getBool('fullview');

		if (!$page)
		{
			$page = 0;
		}

		if ($params->get('intro_only') || $params->get('popup') || $full || $view != 'article')
		{
			$row->text = preg_replace($regex, '', $row->text);

			return;
		}

		// Find all instances of plugin and put in $matches.
		$matches = array();
		preg_match_all($regex, $row->text, $matches, PREG_SET_ORDER);

		if (($showall && $this->params->get('showall', 1)))
		{
			$hasToc = $this->params->get('multipage_toc', 1);

			if ($hasToc)
			{
				// Display TOC.
				$page = 1;
				$this->_createToc($row, $matches, $page);
			}
			else
			{
				$row->toc = '';
			}

			$row->text = preg_replace($regex, '<br />', $row->text);

			return true;
		}

		// Split the text around the plugin.
		$text = preg_split($regex, $row->text);

		// Count the number of pages.
		$n = count($text);

		// We have found at least one plugin, therefore at least 2 pages.
		if ($n > 1)
		{
			$title	= $this->params->get('title', 1);
			$hasToc = $this->params->get('multipage_toc', 1);

			// Adds heading or title to <site> Title.
			if ($title)
			{
				if ($page)
				{
					if ($page && @$matches[$page - 1][2])
					{
						$attrs = JUtility::parseAttributes($matches[$page - 1][1]);

						if (@$attrs['title'])
						{
							$row->page_title = $attrs['title'];
						}
					}
				}
			}

			// Reset the text, we already hold it in the $text array.
			$row->text = '';

			if ($style == 'pages')
			{
				// Display TOC.
				if ($hasToc)
				{
					$this->_createToc($row, $matches, $page);
				}
				else
				{
					$row->toc = '';
				}

				// Traditional mos page navigation
				$pageNav = new JPagination($n, $page, 1);

				
				//$row->text .= '<br />';
				$row->text .= '<div class="pager">';
               
                $this->_createPaginationImageObject($row, $originalFullText, $text, $page);	
				
				// Adds navigation between pages to bottom of text.
				if ($hasToc)
				{
					$this->_createNavigation($row, $page, $n,$pageNav);
				}

				// Page links shown at bottom of page if TOC disabled.
				if (!$hasToc)
				{
					//$row->text .= $pageNav->getPagesLinks();
					$this->_createNavigation($row, $page, $n,$pageNav);
				}

				$row->text .= '</div>';
				
				// Page counter.
				//$row->text .= '<div style="clear: both; margin-top: 5px;" class="s_line"></div>';
				/*$row->text .= '<div class="pagenavcounter">';
				$row->text .= $pageNav->getPagesCounter();
				$row->text .= '</div>';*/
				

				// Page text.
				$text[$page] = str_replace('<hr id="system-readmore" />', '', $text[$page]);
				//$row->text .= $text[$page];
				$row->text = $row->text.$text[$page];
                
                //$this->_createPaginationImageObject($row, $originalFullText, $text, $page);				
			}
			else
			{
				$t[] = $text[0];

				$t[] = (string) JHtml::_($style . '.start', 'article' . $row->id . '-' . $style);

				foreach ($text as $key => $subtext)
				{
					if ($key >= 1)
					{
						$match = $matches[$key - 1];
						$match = (array) JUtility::parseAttributes($match[0]);

						if (isset($match['alt']))
						{
							$title	= stripslashes($match['alt']);
						}
						elseif (isset($match['title']))
						{
							$title	= stripslashes($match['title']);
						}
						else
						{
							$title	= JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $key + 1);
						}

						$t[] = (string) JHtml::_($style . '.panel', $title, 'article' . $row->id . '-' . $style . $key);
					}

					$t[] = (string) $subtext;
				}

				$t[] = (string) JHtml::_($style . '.end');

				$row->text = implode(' ', $t);
			}
		}

		return true;
	}

	/**
	 * Creates a Table of Contents for the pagebreak
	 *
	 * @param   object   &$row      The article object.  Note $article->text is also available
	 * @param   array    &$matches  Array of matches of a regex in onContentPrepare
	 * @param   integer  &$page     The 'page' number
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	protected function _createTOC(&$row, &$matches, &$page)
	{
		$heading = isset($row->title) ? $row->title : JText::_('PLG_CONTENT_PAGEBREAK_NO_TITLE');
		$input = JFactory::getApplication()->input;
		$limitstart = $input->getUInt('limitstart', 0);
		$showall = $input->getInt('showall', 0);

		// TOC header.
		$row->toc .= '<div class="pull-right article-index">';

		if ($this->params->get('article_index') == 1)
		{
			$headingtext = JText::_('PLG_CONTENT_PAGEBREAK_ARTICLE_INDEX');

			if ($this->params->get('article_index_text'))
			{
				htmlspecialchars($headingtext = $this->params->get('article_index_text'));
			}

			$row->toc .= '<h3>' . $headingtext . '</h3>';
		}

		// TOC first Page link.
		$class = ($limitstart === 0 && $showall === 0) ? 'toclink active' : 'toclink';
		$row->toc .= '<ul class="nav nav-tabs nav-stacked">
		<li class="' . $class . '">

			<a href="' . JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=') . '" class="' . $class . '">'
			. $heading .
			'</a>

		</li>
		';

		$i = 2;

		foreach ($matches as $bot)
		{
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($i - 1));

			if (@$bot[0])
			{
				$attrs2 = JUtility::parseAttributes($bot[0]);

				if (@$attrs2['alt'])
				{
					$title	= stripslashes($attrs2['alt']);
				}
				elseif (@$attrs2['title'])
				{
					$title	= stripslashes($attrs2['title']);
				}
				else
				{
					$title	= JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $i);
				}
			}
			else
			{
				$title	= JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $i);
			}

			$class = ($limitstart == $i - 1) ? 'toclink active' : 'toclink';
			$row->toc .= '
				<li>

					<a href="' . $link . '" class="' . $class . '">'
					. $title .
					'</a>

				</li>
				';
			$i++;
		}

		if ($this->params->get('showall'))
		{
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=1&limitstart=');
			$class = ($showall == 1) ? 'toclink active' : 'toclink';
			$row->toc .= '
			<li>

					<a href="' . $link . '" class="' . $class . '">'
					. JText::_('PLG_CONTENT_PAGEBREAK_ALL_PAGES') .
					'</a>

			</li>
			';
		}

		$row->toc .= '</ul></div>';
	}

	/**
	 * Creates the navigation for the item
	 *
	 * @param   object  &$row  The article object.  Note $article->text is also available
	 * @param   int     $page  The total number of pages
	 * @param   int     $n     The page number
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function _createNavigation(&$row, $page, $n,&$pageNav = null)
	{
		$pnSpace = '';

		if (JText::_('JGLOBAL_LT') || JText::_('JGLOBAL_LT'))
		{
			$pnSpace = ' ';
		}
        
        // In the current page position 
        $sequence = ($page+1)%5;
        if ($sequence == 0) {
            // OMG, we are on the 5th item
            $sequence = 5;
        }
                                
        // the first item sequence
        $start = $page+1 - $sequence;
                         
        // In case we are on the first page
        if($start < 0) $start = 0;                   
        
        $slideAll = array_slice($row->pageImage->images, $start, 5,true);        

        $prePage =  ($page - 1) < 1? '': ($page - 1);
        $nextPage = ($page + 1) > $n - 1 ? $n - 1: ($page + 1);
		
		
		if ($page < $n - 1)
		{
			$page_next = $page + 1;

			//$link_next = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($page_next));
			$link_next = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($nextPage));

			// Next >>
			//$next = '<a href="' . $link_next . '">' . JText::_('JNEXT') . $pnSpace . JText::_('JGLOBAL_GT') . JText::_('JGLOBAL_GT') . '</a>';
			$next = '<a href="' . $link_next . '">' . '<img src="/templates/protostar/images/next.gif" />' . '</a>';
		}
		else
		{
			//$next = JText::_('JNEXT');
			//print_r($row->nextarticle);
			if(isset($row->nextarticle) && !empty($row->nextarticle))
			{
				$link_next = $row->nextarticle;
			}
			else
			{
				$link_next = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=');
			}		
			
			$next = '<a href="'.$link_next.'">' . '<img src="/templates/protostar/images/next.gif" />' . '</a>';
		}

		if ($page > 0)
		{
			$page_prev = $page - 1 == 0 ? '' : $page - 1;

			//$link_prev = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($page_prev));
			$link_prev = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($prePage));

			// << Prev
			//$prev = '<a href="' . $link_prev . '">' . JText::_('JGLOBAL_LT') . JText::_('JGLOBAL_LT') . $pnSpace . JText::_('JPREV') . '</a>';
			$prev = '<a href="' . $link_prev . '">' . '<img src="/templates/protostar/images/prev.gif" />' . '</a>';
		}
		else
		{
			//$prev = JText::_('JPREV');
			$prev = '<a href="javascript:void(0);">' . '<img src="/templates/protostar/images/prev.gif" />' . '</a>';
		}	
		
		$thumbnail = '';
		// Page counter.
		//$row->text .= '<div style="clear: both; margin-top: 5px;" class="s_line"></div>';
		$thumbnail .= '<li class="slide_pagecount"><div class="pagenavcounter">';
		$thumbnail .= $pageNav->getPagesCounter();
		$thumbnail .= '</div></li>';
		
		/*if(isset($row->pageImage) && !empty($row->pageImage->images))
		{
			foreach($slideAll as $key=>$value)
			{
				$thumb_link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid) . '&showall=&limitstart=' . ($key==0?'':$key));
				if($key == $row->pageImage->page)
				{
					$thumbnail .='<li class="slide_thumbnail current"><a href="'.$thumb_link.'"><img src="'.$value.'" /></a></li>';
				}
				else
				{
					$thumbnail .='<li class="slide_thumbnail"><a href="'.$thumb_link.'"><img src="'.$value.'" /></li>';
				}
			}
		}*/
		$row->text .= '<ul><li class="prev_thumb">' . $prev . ' </li>'.$thumbnail.'<li class="next_thumb">' . $next . '</li></ul>';
	}
    
    /**
    * @author Leon.Zhao
    * @access protected
    * @uses get the image split array and current image
    */
    protected function _createPaginationImageObject(&$row, $originalFullText, $text, $page){
        jimport('leon.dom');
        $html = str_get_html($originalFullText);
        foreach($html->find('img') as $element){
            $imagesArray[] = $element->src;
        } 
        $row->pageImage->images = $imagesArray;
        $row->pageImage->page = $page;
    }
}
