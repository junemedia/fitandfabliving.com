<?php
/**
 * @version		$Id: itemshelper.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
class itemsHelper {
	public static function filterDesc($text,$opts){
		$text = itemsHelper::stripTags($text,$opts->strip);
		if($opts->limit_text){$text=itemsHelper::getLimitText($text,$opts->text_length);}
		return $text;
	}
	public static function resizeImg($text,$size){
		$regex	= '/<img\s.*?\/>/i';
		preg_match_all($regex, $text, $matches_img);
		for ($i=0; $i<count($matches_img[0]); $i++){
			$newImg	= itemsHelper::changeSize($matches_img[0][$i],$size);
			$text = str_replace($matches_img[0][$i],$newImg, $text);
		}
		return $text;
	}
	public static function changeSize($img,$size){
		if ($size[0]!=0) {
			$img	= str_replace('<img ','<img width="'.$size[0].'" ',$img);
		}
		if ($size[1]!=0) {
			$img	= str_replace('<img ','<img height="'.$size[1].'" ',$img);
		}
		return $img;
	}
	public static function stripTags($text, $tags) {
		$base		= JURI::base();
		$protocols	= '[a-zA-Z0-9]+:';
		if(trim($tags)==''){
			$regex	= '#(src)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
			$text	= preg_replace($regex, "$1=\"$base\$2\"", $text);
			$regex	= '#(href)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
			$text	= preg_replace($regex, "$1=\"$base\$2\"", $text);
			return $text;
		}
		$script = false;
		$regex	= '/\{(.*?)\}/';
		preg_match_all($regex,$tags,$plugins);
		$tags	= preg_replace($regex,'',$tags);
		$tags	= preg_replace('/\s/','',$tags);
		preg_match('/\*/',$tags,$all);
		if ($all) {
			$allPlug = $allHtml = true;
		} else {
			preg_match('/html/',$tags, $allHtml);
			preg_match('/plugin/',$tags, $allPlug);
			preg_match('/script/',$tags, $script);
		}
		$plugins = $plugins[1]; 
	 	if ($allPlug) {
	 		$tags	= preg_replace('/,*plugin,*/',',',$tags);
			$text	= preg_replace('/\{.*?\}/', '',$text);
		} elseif ($plugins){
			for ($i=0; $i<count($plugins); $i++){
				$regex	= preg_quote($plugins[$i]);
				$regex	= preg_replace('/\s+/','\s+',$regex);
				$regex1	= '/\{'.$regex.'.*?\}.*?{\/'.$regex.'}/';
				$text	= preg_replace($regex1,'',$text);
				$regex2	= '/\{'.$regex.'.*?\}/';
				$text	= preg_replace($regex2,'',$text);
			}
		}
		$tags	= preg_replace('/(html)|(plugin)|(\*)/','',$tags);
		$tags	= preg_replace('/,+/',',',$tags);
		if($allHtml){
			$tagsAllow	= '<'.str_replace(',','><',$tags).'>';
			$text	= strip_tags($text,$tagsAllow);
		}else {
			$tagsArr = explode(',',$tags);
			if(in_array('img',$tagsArr)){
				$text	= preg_replace('/<img\s.*?\/>/', '',$text);
			}else{
				$regex	= '#(src)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
				$text	= preg_replace($regex, "$1=\"$base\$2\"", $text);
			}
			if(in_array('a',$tagsArr)){
				preg_match_all('/<a\s.*?\/a>/', $text, $matches_a);
				for ($i=0; $i<count($matches_a[0]); $i++){
					$text = str_replace($matches_a[0][$i],strip_tags($matches_a[0][$i],'<img>'),$text);
				}
			}else {
				$regex	= '#(href)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
				$text	= preg_replace($regex, "$1=\"$base\$2\"", $text);
			}
		}
		if($script){
			$text = self::filter_script($text);
		}
		return $text;
	}
	public static function filter_script($html) {
		$b = explode('</script>',$html);
		$d = array();
		for($i=0;$i<count($b);$i++){
			$c	= explode('<script',$b[$i]);
			$n	= (count($c)-1);
			if($n>0){
				$c[$n]='';
				$d[]= implode($c);
			}else $d[] = $b[$i];
		}
		$f	= implode($d);
		return $f;
	}
	public static function getLimitText($text,$limit){
		$oldText= $text;
		$total	= 0;
		$regex	= '/<img\s.*?\/>/';
 		preg_match_all($regex, $text, $matches_img);
 		for ($i=0; $i<count($matches_img[0]); $i++){
 			$text = str_replace($matches_img[0][$i]," s4rssImg_$i ", $text);
 		}
		$total = count($matches_img);
		$regex	= '/<a\s.*?\/a>/';
		preg_match_all($regex, $text, $matches_a);
		for ($i=0; $i<count($matches_a[0]); $i++){
 			$text = str_replace($matches_a[0][$i]," s4rssA_$i ", $text);
 		}
 		$regex_table = '/<table\s.*?<\/table>/';
 		preg_match_all($regex_table, $text, $matches_table);
		for ($i=0; $i<count($matches_table[0]); $i++){
 			$text = str_replace($matches_table[0][$i]," s4rssTable_$i ", $text);
 		}
		$text_array = explode(' ' , $text );
		$count = count( $text_array );
		if($count < $limit) return $oldText;
		$text = '';
		for ( $i = 0; $i < ($limit + $total)-1; $i++ ) {
			$text .= $text_array[$i]. ' ';
		}
		$text = trim( $text );
		for ($i=0; $i<count($matches_table[0]);$i++) {
			$text = str_replace("s4rssTable_$i",$matches_table[0][$i], $text);
		}
		for ($i=0; $i<count($matches_a[0]); $i++){ 
			$text = str_replace("s4rssA_$i",$matches_a[0][$i], $text);
		}
		for ($i=0; $i<count($matches_img[0]); $i++){ 
			$text = str_replace("s4rssImg_$i",$matches_img[0][$i], $text);
		}
		if ( $count > $limit ) {
			$text .= ' ...';
		}
		return $text;
	}
	/**
	 * Re-format the element name
	 * @param string $name
	 * @param boolean $toLower (optional)
	 * @param boolean$space (optional)
	 * @param boolean $onlyABC (optional)
	 * @return the new name of the element
	 */
	public static function formatElementName($name, $toLower = true, $space = false, $onlyABC = true) {
		if ($toLower) {
			$name = strtolower($name);
		}
		if (!$space) {
			$name = str_replace(" ", "_", $name);
		}
		/* if ($onlyABC) {
			preg_match("/^[a-zA-Z0-9]+$/", $name, $mathes);
			var_dump($mathes);
		} */
		return $name;
	}
	/**
	 * Split Author Value
	 * I.e: Kha Nguyen (khant@foobla.com)
	 * This function will be used very soon
	 * @param string $string
	 * @return array('author', 'authorEmail');
	 */
	public static function splitAuthorValue($string) {
		# remove all (, ) characters
		$new_string = str_replace(array('(', ')', '{', '}'), '', $string);
		$array = explode(' ', $new_string);
		return $array;
	}
	
	// Return .ext for the Feed
	public static function getFeedTypePrefix($feed_type) {
		$params = JComponentHelper::getParams('com_obrss');
		// 		var_dump($params->get('sef_json'));
		switch($feed_type) {
			case 'JSON':
				// 				$ext = 'json';
				$ext = $params->get('sef_json');
				break;
			case 'HTML':
				// 				$ext = 'html';
				$ext = $params->get('sef_html');
				break;
			case 'SITEMAP':
				$ext = $params->get('sef_sitemap');
				break;
			case 'ATOM':
			case 'ATOM03':
				// 				$ext = 'xml';
				$ext = $params->get('sef_atom');
				break;
			case 'RSS20':
			case 'RSS10':
			case 'RSS091':
			default:
				// 				$ext = 'rss';
				$ext = $params->get('sef_rss');
				break;
		}
		return $ext;
	}
}