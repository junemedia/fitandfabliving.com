<?php
/**
 * Plugin Helper File
 *
 * @package         Sourcerer
 * @version         4.3.0
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2013 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/protect.php';

/**
 * Plugin that replaces Sourcerer code with its HTML / CSS / JavaScript / PHP equivalent
 */
class plgSystemSourcererHelper
{
	function __construct(&$params)
	{
		$this->option = JFactory::getApplication()->input->get('option');

		// Set plugin parameters
		$this->src_params = new stdClass;
		$this->src_params->syntax_word = $params->syntax_word;
		$this->src_params->syntax_start = '{' . $this->src_params->syntax_word . '}';
		$this->src_params->syntax_start_0 = '{' . $this->src_params->syntax_word . ' 0}';
		$this->src_params->syntax_end = '{/' . $this->src_params->syntax_word . '}';

		// Matches the start and end tags with everything in between
		// Also matches any surrounding breaks and paragraph tags, to prevent unwanted empty lines in output.
		$breaks_start = '(<p(?: [^>]*)?>\s*)?((?:<span [^>]*>\s*)*)';
		$breaks_end = '((?:\s*</span>)*)(\s*</p>)?';
		$this->src_params->regex = '#(' . $breaks_start . '(' . preg_quote($this->src_params->syntax_start, '#') . '|' . preg_quote($this->src_params->syntax_start_0, '#') . ')(.*?)' . preg_quote($this->src_params->syntax_end, '#') . $breaks_end . ')#s';

		$this->src_params->tags_syntax = array(array('<', '>'), array('\[\[', '\]\]'));
		$this->src_params->splitter = '<!-- START: SRC_SPLIT -->';

		$user = JFactory::getUser();
		$this->src_params->user_is_admin = $user->authorise('core.admin', 1);

		// Initialie the different enables
		$this->src_params->areas = array();
		$this->src_params->areas['default'] = array();
		$this->src_params->areas['default']['enable_css'] = $params->enable_css;
		$this->src_params->areas['default']['enable_js'] = $params->enable_js;
		$this->src_params->areas['default']['enable_php'] = $params->enable_php;
		$this->src_params->areas['default']['forbidden_php'] = $params->forbidden_php;
		$this->src_params->areas['default']['forbidden_tags'] = $params->forbidden_tags;

		$this->src_params->areas['articles'] = $this->src_params->areas['default'];
		$this->src_params->areas['articles']['enable'] = $params->articles_enable;
		$this->src_params->areas['articles']['enable_css'] = $params->articles_enable_css;
		$this->src_params->areas['articles']['enable_js'] = $params->articles_enable_js;
		$this->src_params->areas['articles']['enable_php'] = $params->articles_enable_php;
		$this->src_params->areas['articles']['forbidden_php'] = $this->src_params->areas['default']['forbidden_php'] . ',' . $params->articles_forbidden_php;
		$this->src_params->areas['articles']['forbidden_tags'] = $this->src_params->areas['default']['forbidden_tags'] . ',' . $params->articles_forbidden_tags;
		$this->src_params->areas['articles']['security'] = (array) $params->articles_security_level;
		$this->src_params->areas['articles']['security_css'] = $params->articles_security_level_default_css ? (array) $params->articles_security_level : (array) $params->articles_security_level_css;
		$this->src_params->areas['articles']['security_js'] = $params->articles_security_level_default_js ? (array) $params->articles_security_level : (array) $params->articles_security_level_js;
		$this->src_params->areas['articles']['security_php'] = $params->articles_security_level_default_php ? (array) $params->articles_security_level : (array) $params->articles_security_level_php;

		$this->src_params->areas['components'] = $this->src_params->areas['default'];
		$this->src_params->areas['components']['enable'] = $params->components_enable;
		$this->src_params->areas['components']['components'] = $params->components;
		if (!is_array($this->src_params->areas['components']['components']))
		{
			$this->src_params->areas['components']['components'] = explode(',', $this->src_params->areas['components']['components']);
		}
		$this->src_params->areas['components']['enable_css'] = $params->components_enable_css;
		$this->src_params->areas['components']['enable_js'] = $params->components_enable_js;
		$this->src_params->areas['components']['enable_php'] = $params->components_enable_php;
		$this->src_params->areas['components']['forbidden_php'] = $this->src_params->areas['default']['forbidden_php'] . ',' . $params->components_forbidden_php;
		$this->src_params->areas['components']['forbidden_tags'] = $this->src_params->areas['default']['forbidden_tags'] . ',' . $params->components_forbidden_tags;

		$this->src_params->areas['other'] = $this->src_params->areas['default'];
		$this->src_params->areas['other']['enable'] = $params->other_enable;
		$this->src_params->areas['other']['enable_css'] = $params->other_enable_css;
		$this->src_params->areas['other']['enable_js'] = $params->other_enable_js;
		$this->src_params->areas['other']['enable_php'] = $params->other_enable_php;
		$this->src_params->areas['other']['forbidden_php'] = $this->src_params->areas['default']['forbidden_php'] . ',' . $params->other_forbidden_php;
		$this->src_params->areas['other']['forbidden_tags'] = $this->src_params->areas['default']['forbidden_tags'] . ',' . $params->other_forbidden_tags;

		foreach ($this->src_params->areas as $areaname => $area)
		{
			if ($area['enable_css'] == -1)
			{
				$this->src_params->areas[$areaname]['enable_css'] = $this->src_params->areas['default']['enable_css'];
			}
			if ($area['enable_js'] == -1)
			{
				$this->src_params->areas[$areaname]['enable_js'] = $this->src_params->areas['default']['enable_js'];
			}
			if ($area['enable_php'] == -1)
			{
				$this->src_params->areas[$areaname]['enable_php'] = $this->src_params->areas['default']['enable_php'];
			}
		}

		$this->src_params->currentarea = 'default';
	}

	/**
	 * onContentPrepare
	 */
	function onContentPrepare(&$article)
	{
		$area = isset($article->created_by) ? 'articles' : 'other';

		if (isset($article->created_by))
		{
			$user = JFactory::getUser();
			$table = $user->getTable();
			if ($table->load($article->created_by))
			{
				$user = JFactory::getUser($article->created_by);
			}
			$groups = $user->getAuthorisedGroups();
			array_unshift($groups, -1);

			// Set if security is passed
			// passed = creator is equal or higher than security group level
			$pass = array_intersect($this->src_params->areas[$area]['security'], $groups);
			$this->src_params->areas[$area]['security_pass'] = (!empty($pass));
			$pass = array_intersect($this->src_params->areas[$area]['security_css'], $groups);
			$this->src_params->areas[$area]['security_pass_css'] = (!empty($pass));
			$pass = array_intersect($this->src_params->areas[$area]['security_js'], $groups);
			$this->src_params->areas[$area]['security_pass_js'] = (!empty($pass));
			$pass = array_intersect($this->src_params->areas[$area]['security_php'], $groups);
			$this->src_params->areas[$area]['security_pass_php'] = (!empty($pass));
		}

		if (isset($article->text))
		{
			$this->replace($article->text, $area, $article);
		}
		if (isset($article->description))
		{
			$this->replace($article->description, $area, $article);
		}
		if (isset($article->title))
		{
			$this->replace($article->title, $area, $article);
		}
		if (isset($article->author))
		{
			if (isset($article->author->name))
			{
				$this->replace($article->author->name, $area, $article);
			}
			else if (is_string($article->author))
			{
				$this->replace($article->author, $area, $article);
			}
		}
	}

	/**
	 * onAfterDispatch
	 */
	function onAfterDispatch()
	{
		// PDF
		if (JFactory::getDocument()->getType() == 'pdf')
		{
			$buffer = JFactory::getDocument()->getBuffer('component');
			if (is_array($buffer))
			{
				if (isset($buffer['component'], $buffer['component']['']))
				{
					if (isset($buffer['component']['']['component'], $buffer['component']['']['component']['']))
					{
						$this->replaceInTheRest($buffer['component']['']['component'][''], 0);
					}
					else
					{
						$this->replaceInTheRest($buffer['component'][''], 0);
					}
				}
				else if (isset($buffer['0'], $buffer['0']['component'], $buffer['0']['component']['']))
				{
					if (isset($buffer['0']['component']['']['component'], $buffer['0']['component']['']['component']['']))
					{
						$this->replaceInTheRest($buffer['component']['']['component'][''], 0);
					}
					else
					{
						$this->replaceInTheRest($buffer['0']['component'][''], 0);
					}
				}
			}
			else
			{
				$this->replaceInTheRest($buffer);
			}
			JFactory::getDocument()->setBuffer($buffer, 'component');

			return;
		}

		// FEED
		if ((JFactory::getDocument()->getType() == 'feed' || $this->option == 'com_acymailing') && isset(JFactory::getDocument()->items))
		{
			for ($i = 0; $i < count(JFactory::getDocument()->items); $i++)
			{
				$this->onContentPrepare(JFactory::getDocument()->items[$i]);
			}
		}

		$buffer = JFactory::getDocument()->getBuffer('component');
		if (!empty($buffer))
		{
			if (is_array($buffer))
			{
				if (isset($buffer['component']) && isset($buffer['component']['']))
				{
					$this->tagArea($buffer['component'][''], 'SRC', 'component');
				}
			}
			else
			{
				$this->tagArea($buffer, 'SRC', 'component');
			}
			JFactory::getDocument()->setBuffer($buffer, 'component');
		}
	}

	/**
	 * onAfterRender
	 */
	function onAfterRender()
	{
		// not in pdf's
		if (JFactory::getDocument()->getType() == 'pdf')
		{
			return;
		}

		// Grab the body (but be gentle)
		$html = JResponse::getBody();

		$this->protect($html);
		$this->replaceInTheRest($html);
		NNProtect::unprotect($html);

		$this->cleanLeftoverJunk($html);

		// Throw the body back (less gentle)
		JResponse::setBody($html);
	}

	function replaceInTheRest(&$str)
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		// COMPONENT
		if (JFactory::getDocument()->getType() == 'feed' || $this->option == 'com_acymailing')
		{
			$s = '#(<item[^>]*>)#s';
			$str = preg_replace($s, '\1<!-- START: SRC_COMPONENT -->', $str);
			$str = str_replace('</item>', '<!-- END: SRC_COMPONENT --></item>', $str);
		}
		if (strpos($str, '<!-- START: SRC_COMPONENT -->') === false)
		{
			$this->tagArea($str, 'SRC', 'component');
		}

		if (in_array($this->option, $this->src_params->areas['components']['components']))
		{
			// For all components that are selected, set the 'enable' to false
			$this->src_params->areas['components']['enable'] = $this->src_params->areas['components']['enable_css'] = $this->src_params->areas['components']['enable_js'] = $this->src_params->areas['components']['enable_php'] = 0;
		}

		$components = $this->getTagArea($str, 'SRC', 'component');
		foreach ($components as $component)
		{
			$this->replace($component['1'], 'components', '');
			$str = str_replace($component['0'], $component['1'], $str);
		}

		// EVERYWHERE
		$this->replace($str, 'other');
	}

	function tagArea(&$str, $ext = 'EXT', $area = '')
	{
		if ($str && $area)
		{
			$str = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->' . $str . '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			if ($area == 'article_text')
			{
				$str = preg_replace('#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->\1<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->', $str);
			}
		}
	}

	function getTagArea(&$str, $ext = 'EXT', $area = '')
	{
		$matches = array();
		if ($str && $area)
		{
			$start = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			$end = '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			$matches = explode($start, $str);
			array_shift($matches);
			foreach ($matches as $i => $match)
			{
				list($text) = explode($end, $match, 2);
				$matches[$i] = array(
					$start . $text . $end,
					$text
				);
			}
		}

		return $matches;
	}

	function replace(&$str, $area = 'articles', $article = '')
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		$arr = $this->stringToSplitArray($str, $this->src_params->regex);
		$arr_count = count($arr);
		if ($arr_count > 1)
		{
			for ($i = 1; $i < $arr_count - 1; $i++)
			{
				if (fmod($i, 2))
				{
					$matches = preg_replace($this->src_params->regex, implode($this->src_params->splitter, array('\2', '\3', '\4', '\5', '\6', '\7')), $arr[$i]);
					$matches = explode($this->src_params->splitter, $matches);

					$html = $matches['3'];

					$uses_editor = ($matches['2'] == $this->src_params->syntax_start);
					if ($uses_editor)
					{
						$this->cleanText($html);
					}

					$this->replaceTags($html, $area, $article);

					if ($uses_editor)
					{
						// Restore leading/trailing paragraph tags if not both present
						if (!($matches['0'] && $matches['5']))
						{
							$html = $matches['0'] . $html . $matches['5'];
						}
					}
					else
					{
						$html = $matches['0'] . $matches['1'] . $html . $matches['4'] . $matches['5'];
					}

					$arr[$i] = $html;
				}
			}
		}
		$str = implode('', $arr);
	}

	function replaceTags(&$str, $area = 'articles', $article = '')
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		$this->replaceTagsByType($str, $area, 'php', $article);

		$this->replaceTagsByType($str, $area, 'all');
		$this->replaceTagsByType($str, $area, 'js');
		$this->replaceTagsByType($str, $area, 'css');
	}

	function replaceTagsByType(&$str, $area = 'articles', $type = 'all', $article = '')
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		$type_ext = '_' . $type;
		if ($type == 'all')
		{
			$type_ext = '';
		}

		$a = $this->src_params->areas[$area];
		$security_pass = isset($a['security_pass' . $type_ext]) ? $a['security_pass' . $type_ext] : 1;
		$enable = isset($a['enable' . $type_ext]) ? $a['enable' . $type_ext] : 1;

		switch ($type)
		{
			case 'php':
				$this->replaceTagsPHP($str, $enable, $security_pass, $article);
				break;
			case 'js':
				$this->replaceTagsJS($str, $enable, $security_pass);
				break;
			case 'css':
				$this->replaceTagsCSS($str, $enable, $security_pass);
				break;
			default:
				$this->replaceTagsAll($str, $enable, $security_pass);
				break;
		}
	}

	/**
	 * Replace any html style tags by a comment tag if not permitted
	 * Match: <...>
	 */
	function replaceTagsAll(&$str, $enabled = 1, $security_pass = 1)
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		if (!$enabled)
		{
			// replace source block content with HTML comment
			$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::_('SRC_CODE_REMOVED_NOT_ENABLED') . ' -->';
		}
		else if (!$security_pass)
		{
			// replace source block content with HTML comment
			$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_SECURITY', '') . ' -->';
		}
		else
		{
			$this->cleanTags($str);

			$a = $this->src_params->areas[$this->src_params->currentarea];
			$forbidden_tags_array = explode(',', $a['forbidden_tags']);
			$this->cleanArray($forbidden_tags_array);
			// remove the comment tag syntax from the array - they cannot be disabled
			$forbidden_tags_array = array_diff($forbidden_tags_array, array('!--'));
			// reindex the array
			$forbidden_tags_array = array_merge($forbidden_tags_array);

			$has_forbidden_tags = 0;
			foreach ($forbidden_tags_array as $forbidden_tag)
			{
				if (!(strpos($str, '<' . $forbidden_tag) == false))
				{
					$has_forbidden_tags = 1;
					break;
				}
			}

			if ($has_forbidden_tags)
			{
				// double tags
				$tag_regex = '#<\s*([a-z\!][^>\s]*?)(?:\s+.*?)?>.*?</\1>#si';
				if (preg_match_all($tag_regex, $str, $matches, PREG_SET_ORDER) > 0)
				{
					foreach ($matches as $match)
					{
						if (in_array($match['1'], $forbidden_tags_array))
						{
							$tag = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_TAG_REMOVED_FORBIDDEN', $match['1']) . ' -->';
							$str = str_replace($match['0'], $tag, $str);
						}
					}
				}
				// single tags
				$tag_regex = '#<\s*([a-z\!][^>\s]*?)(?:\s+.*?)?>#si';
				if (preg_match_all($tag_regex, $str, $matches, PREG_SET_ORDER) > 0)
				{
					foreach ($matches as $match)
					{
						if (in_array($match['1'], $forbidden_tags_array))
						{
							$tag = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_TAG_REMOVED_FORBIDDEN', $match['1']) . ' -->';
							$str = str_replace($match['0'], $tag, $str);
						}
					}
				}
			}
		}
	}

	/**
	 * Replace the PHP tags with the evaluated PHP scripts
	 * Or replace by a comment tag the PHP tags if not permitted
	 */
	function replaceTagsPHP(&$src_str, $src_enabled = 1, $src_security_pass = 1, $article = '')
	{
		if (!is_string($src_str) || $src_str == '')
		{
			return;
		}

		if ((strpos($src_str, '<?') === false) && (strpos($src_str, '[[?') === false))
		{
			return;
		}

		global $src_vars;

		// Match ( read {} as <> ):
		// {?php ... ?}
		// {? ... ?}
		$src_string_array = $this->stringToSplitArray($src_str, '-start-' . '\?(?:php)?[\s<](.*?)\?' . '-end-', 1);
		$src_string_array_count = count($src_string_array);

		if ($src_string_array_count > 1)
		{
			if (!$src_enabled)
			{
				// replace source block content with HTML comment
				$src_string_array = array();
				$src_string_array['0'] = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_NOT_ALLOWED', JText::_('SRC_PHP'), JText::_('SRC_PHP')) . ' -->';
			}
			else if (!$src_security_pass)
			{
				// replace source block content with HTML comment
				$src_string_array = array();
				$src_string_array['0'] = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_SECURITY', JText::_('SRC_PHP')) . ' -->';
			}
			else
			{
				// if source block content has more than 1 php block, combine them
				if ($src_string_array_count > 3)
				{
					for ($i = 2; $i < $src_string_array_count - 1; $i++)
					{
						if (fmod($i, 2) == 0)
						{
							$src_string_array['1'] .= "<!-- SRC_SEMICOLON --> ?>" . trim($src_string_array[$i]) . "<?php ";
						}
						else
						{
							$src_string_array['1'] .= $src_string_array[$i];
						}
						unset($src_string_array[$i]);
					}
				}

				// fixes problem with _REQUEST being stripped if there is an error in the code
				$src_backup_REQUEST = $_REQUEST;
				$src_backup_vars = array_keys(get_defined_vars());

				$src_script = trim($src_string_array['1']) . '<!-- SRC_SEMICOLON -->';
				$src_script = preg_replace('#(;\s*)?<\!-- SRC_SEMICOLON -->#s', ';', $src_script);

				$a = $this->src_params->areas[$this->src_params->currentarea];
				$src_forbidden_php_array = explode(',', $a['forbidden_php']);
				$this->cleanArray($src_forbidden_php_array);
				$src_forbidden_php_regex = '#[^a-z_](' . implode('|', $src_forbidden_php_array) . ')\s*\(#si';

				if (preg_match_all($src_forbidden_php_regex, ' ' . $src_script, $src_functions, PREG_SET_ORDER) > 0)
				{
					$src_functionsArray = array();
					foreach ($src_functions as $src_function)
					{
						$src_functionsArray[] = $src_function['1'] . ')';
					}
					$src_string_array['1'] = JText::_('SRC_PHP_FORBIDDEN') . ':<br /><span style="font-family: monospace;"><ul style="margin:0px;"><li>' . implode('</li><li>', $src_functionsArray) . '</li></ul></span>';
					$src_comment = JText::_('SRC_PHP_CODE_REMOVED_FORBIDDEN') . ': ( ' . implode(', ', $src_functionsArray) . ' )';
					if (JFactory::getDocument()->getType() == 'html')
					{
						$src_string_array['1'] = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . $src_comment . ' -->';
					}
					else
					{
						$src_string_array['1'] = '';
					}
				}
				else
				{
					if (!isset($Itemid))
					{
						$Itemid = JFactory::getApplication()->input->getInt('Itemid');
					}
					if (!isset($mainframe))
					{
						$mainframe = JFactory::getApplication();
					}
					if (!isset($app))
					{
						$app = JFactory::getApplication();
					}
					if (!isset($document))
					{
						$document = JFactory::getDocument();
					}
					if (!isset($doc))
					{
						$doc = JFactory::getDocument();
					}
					if (!isset($database))
					{
						$database = JFactory::getDBO();
					}
					if (!isset($db))
					{
						$db = JFactory::getDBO();
					}
					if (!isset($user))
					{
						$user = JFactory::getUser();
					}
					$src_script = '
					if (is_array($src_vars)) {
						foreach ($src_vars as $src_key => $src_value) {
							${$src_key} = $src_value;
						}
					}
					' . $src_script . ';
					return get_defined_vars();
					';
					$temp_PHP_func = create_function('&$src_vars, &$article, &$Itemid, &$mainframe, &$app, &$document, &$doc, &$database, &$db, &$user', $src_script);

					// evaluate the script
					// but without using the the evil eval
					ob_start();
					$src_new_vars = $temp_PHP_func($src_vars, $article, $Itemid, $mainframe, $app, $document, $doc, $database, $db, $user);
					unset($temp_PHP_func);
					$src_string_array['1'] = ob_get_contents();
					ob_end_clean();

					$src_diff_vars = array_diff(array_keys($src_new_vars), $src_backup_vars);
					foreach ($src_diff_vars as $src_diff_key)
					{
						if (!in_array($src_diff_key, array('src_vars', 'article', 'Itemid', 'mainframe', 'app', 'document', 'doc', 'database', 'db', 'user'))
							&& substr($src_diff_key, 0, 4) != 'src_'
						)
						{
							$src_vars[$src_diff_key] = $src_new_vars[$src_diff_key];
						}
					}
				}
			}
		}
		$src_str = implode('', $src_string_array);
	}

	/**
	 * Replace the JavaScript tags by a comment tag if not permitted
	 */
	function replaceTagsJS(&$str, $enabled = 1, $security_pass = 1)
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		// quick check to see if i is necessary to do anything
		if ((strpos($str, 'script') === false))
		{
			return;
		}

		// Match:
		// <script ...>...</script>
		$tag_regex =
			'(-start-' . '\s*script\s[^' . '-end-' . ']*?[^/]\s*' . '-end-'
			. '(.*?)'
			. '-start-' . '\s*\/\s*script\s*' . '-end-)';
		$arr = $this->stringToSplitArray($str, $tag_regex, 1);
		$arr_count = count($arr);

		// Match:
		// <script ...>
		// single script tags are not xhtml compliant and should not occur, but just incase they do...
		if ($arr_count == 1)
		{
			$tag_regex = '(-start-' . '\s*script\s.*?' . '-end-)';
			$arr = $this->stringToSplitArray($str, $tag_regex, 1);
			$arr_count = count($arr);
		}
		if ($arr_count > 1)
		{
			if (!$enabled)
			{
				// replace source block content with HTML comment
				$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_NOT_ALLOWED', JText::_('SRC_JAVASCRIPT'), JText::_('SRC_JAVASCRIPT')) . ' -->';
			}
			else if (!$security_pass)
			{
				// replace source block content with HTML comment
				$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_SECURITY', JText::_('SRC_JAVASCRIPT')) . ' -->';
			}
		}
	}

	/**
	 * Replace the CSS tags by a comment tag if not permitted
	 */
	function replaceTagsCSS(&$str, $enabled = 1, $security_pass = 1)
	{
		if (!is_string($str) || $str == '')
		{
			return;
		}

		// quick check to see if i is necessary to do anything
		if ((strpos($str, 'style') === false) && (strpos($str, 'link') === false))
		{
			return;
		}

		// Match:
		// <script ...>...</script>
		$tag_regex =
			'(-start-' . '\s*style\s[^' . '-end-' . ']*?[^/]\s*' . '-end-'
			. '(.*?)'
			. '-start-' . '\s*\/\s*style\s*' . '-end-)';
		$arr = $this->stringToSplitArray($str, $tag_regex, 1);
		$arr_count = count($arr);

		// Match:
		// <script ...>
		// single script tags are not xhtml compliant and should not occur, but just in case they do...
		if ($arr_count == 1)
		{
			$tag_regex = '(-start-' . '\s*link\s[^' . '-end-' . ']*?(rel="stylesheet"|type="text/css").*?' . '-end-)';
			$arr = $this->stringToSplitArray($str, $tag_regex, 1);
			$arr_count = count($arr);
		}

		if ($arr_count > 1)
		{
			if (!$enabled)
			{
				// replace source block content with HTML comment
				$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_NOT_ALLOWED', JText::_('SRC_CSS'), JText::_('SRC_CSS')) . ' -->';
			}
			else if (!$security_pass)
			{
				// replace source block content with HTML comment
				$str = '<!-- ' . JText::_('SRC_COMMENT') . ': ' . JText::sprintf('SRC_CODE_REMOVED_SECURITY', JText::_('SRC_CSS')) . ' -->';
			}
		}
	}

	function stringToSplitArray($str, $search, $tags = 0)
	{
		if ($tags)
		{
			foreach ($this->src_params->tags_syntax as $src_tag_syntax)
			{
				$tag_search = str_replace('-start-', $src_tag_syntax['0'], $search);
				$tag_search = str_replace('-end-', $src_tag_syntax['1'], $tag_search);
				$tag_search = '#' . $tag_search . '#si';
				$str = preg_replace($tag_search, $this->src_params->splitter . '\1' . $this->src_params->splitter, $str);
			}
		}
		else
		{
			$str = preg_replace($search, $this->src_params->splitter . '\1' . $this->src_params->splitter, $str);
		}

		return explode($this->src_params->splitter, $str);
	}

	function cleanTags(&$str)
	{
		foreach ($this->src_params->tags_syntax as $src_tag_syntax)
		{
			$tag_regex = '#' . $src_tag_syntax['0'] . '\s*(\/?\s*[a-z\!][^' . $src_tag_syntax['1'] . ']*?(?:\s+.*?)?)' . $src_tag_syntax['1'] . '#si';
			$str = preg_replace($tag_regex, '<\1\2>', $str);
		}
	}

	function cleanArray(&$array)
	{
		// trim all values
		$array = array_map('trim', $array);
		// remove dublicates
		$array = array_unique($array);
		// remove empty (or false) values
		$array = array_filter($array);
	}

	function cleanText(&$str)
	{
		// Load common functions
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

		// replace chr style enters with normal enters
		$str = str_replace(array(chr(194) . chr(160), '&#160;', '&nbsp;'), ' ', $str);

		// replace linbreak tags with normal linebreaks (paragraphs, enters, etc).
		$enter_tags = array('p', 'br');
		$regex = '#</?((' . implode(')|(', $enter_tags) . '))+[^>]*?>\n?#si';
		$str = preg_replace($regex, " \n", $str);

		// replace indent characters with spaces
		$str = preg_replace('#<' . 'img [^>]*/sourcerer/images/tab\.png[^>]*>#si', '    ', $str);

		// strip all other tags
		$regex = '#<(/?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?)>#si';
		$str = preg_replace($regex, "", $str);

		// reset htmlentities
		$str = NNText::html_entity_decoder($str);

		// convert protected html entities &_...; -> &...;
		$str = preg_replace('#&_([a-z0-9\#]+?);#i', '&\1;', $str);
	}

	/**
	 * Protect input and text area's
	 */
	function protect(&$str)
	{
		NNProtect::protectForm($str, array($this->src_params->syntax_start, $this->src_params->syntax_start_0, $this->src_params->syntax_end));
	}

	/**
	 * Just in case you can't figure the method name out: this cleans the left-over junk
	 */
	function cleanLeftoverJunk(&$str)
	{
		$str = preg_replace('#<\!-- (START|END): SRC_[^>]* -->#', '', $str);
	}
}
