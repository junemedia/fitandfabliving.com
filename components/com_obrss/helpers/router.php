<?php
/**
 * @version		$Id: router.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class obRSSUrl{
	function makeSefRss(){
		jimport('joomla.filesystem.file');
		$htaccPath	= JPATH_ROOT.DS.'.htaccess';
		if(!is_file($htaccPath)){
			$htaccPath	= JPATH_ROOT.DS.'htaccess.txt';
			if(!is_file($htaccPath)) {
				return false;
			}
		}
		$htacc	= JFile::read($htaccPath);
		if(!$htacc) {
			return false;
		}
		$regex	= '/RewriteCond.+REQUEST_URI.+\\\.rss/';
		preg_match($regex,$htacc,$rss);
		if($rss) {
			return true;
		}
		$regex	= '/RewriteCond.+REQUEST_URI.+\\\.pdf/';
		preg_match($regex,$htacc,$files);
		$pdf	= $files[0];
		$htaccNew	= str_replace($str,$pdf.'|\.rss',$htacc);
		$time = date('Y.m.d.H.e.s');
		if(!JFile::write(JPATH_ROOT.DS.'htaccess_bak_'.$time,$htacc)) {
			return false;
		}
		if(!JFile::write(JPATH_ROOT.DS.'.htaccess',$htaccNew)) {
			return false;
		}
		return true;
	}
	
	public static function Sef($url) {
		$url	= JRoute::_($url);
		return $url; 
		//return str_replace('.html','.rss',$url);
	}
}
?>