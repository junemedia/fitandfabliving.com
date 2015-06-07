<?php
/**
 * @version		$Id: common.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
class ObRssCommon
{
	public static function loadElement($addon)
	{
		$path	= JPATH_COMPONENT_SITE.DS.'addons'.DS.$addon.DS.'elements'.DS;
		if (!is_dir($path)) {
			return;
		}
		$elements	= JFolder::files($path, '.php$');
		if (!is_array($elements) && count($elements) < 1) {
			return;
		}
		foreach ($elements as $el) {
			include_once $path.$el;
		}
	}
	function getUrlContent($url,$dates=null)
	{
		if (function_exists('curl_init')) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_TIMEOUT, 20);
				curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				$content = curl_exec($curl);
				curl_close($curl);
		} else {
			$content = file_get_contents($url);
		}
		return $content;
	}
}