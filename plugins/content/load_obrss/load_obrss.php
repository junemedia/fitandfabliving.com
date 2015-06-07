<?php
/**
 * @version		$Id: load_obrss.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport( 'joomla.html.parameter' );
class plgContentLoad_obrss extends JPlugin
{
	/**
	 * For Joomla 1.5
	 * @param unknown_type $context
	 * @param unknown_type $article
	 * @param unknown_type $params
	 * @param unknown_type $limitstart
	 */
	public function onPrepareContent(&$article, &$params, $limitstart = 0) {
		#echo "123";
		$context = '';
		$this->helloObRSS($context, $article, $params, $limitstart = 0);
	}
	
	/**
	 * For Joomla 2.5
	 * @param unknown_type $context
	 * @param unknown_type $article
	 * @param unknown_type $params
	 * @param unknown_type $limitstart
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart = 0) {
		$this->helloObRSS($context, $article, $params, $limitstart = 0);
	}
	
	public function helloObRSS($context, &$article, &$params, $limitstart = 0) {
		//$app = JFactory::getApplication();
// 		echo '<h1>aaaa</h1><pre>';
// 		print_r($article);
// 		echo '</pre>';
		$db = JFactory::getDBO();
		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $article->text, 'rss' ) === false ) {
			return true;
		}

		// Get plugin info
		$plugin = JPluginHelper::getPlugin('content', 'load_obrss');

	 	// expression to search for
	 	$regex = '/{rss\s*.\d*?}/i';

	 	$pluginParams = new JRegistry( $plugin->params );

		// check whether plugin has been unpublished
		if ( !$pluginParams->get( 'enabled', 1 ) ) {
			$article->text = preg_replace( $regex, '', $article->text );
			return true;
		}

	 	// find all instances of plugin and put in $matches
		preg_match_all( $regex, $article->text, $matches );

		// Number of plugins
	 	$count = count( $matches[0] );

	 	// plugin only processes if there are any instances of the plugin in the text
	 	if ( $count ) {
	 		$this->processFeedsCode( $article, $matches, $count, $regex);
		}
	}
	function processFeedsCode ( &$article, &$matches, $count, $regex){
		$db	= JFactory::getDBO();
	 	require_once JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'router.php';
		$livemarks = JURI::base().'administrator/components/com_obrss/assets/images/icons/feeds_16.png';
	 	for ( $i=0; $i < $count; $i++ ){
	 		$load = str_replace( 'rss', '', $matches[0][$i] );
	 		$load = str_replace( '{', '', $load );
	 		$load = str_replace( '}', '', $load );
	 		$id   = (int) $load ;

			$query 		= "SELECT `id`,`name`,`alias`, `uri`, `use_feedburner` FROM `#__obrss` WHERE `published` = 1 AND `id` = $id";
			$db->setQuery( $query );
			$feed = $db->loadObject(); 		
			if(!$feed) continue;
			if ($feed->use_feedburner == 1 && $feed->uri != '') {
				$feed_link	= 'http://feeds.feedburner.com/'.$feed->uri;
			} else {
				$feed_link	= obRSSUrl::Sef("index.php?option=com_obrss&task=feed&id=".$feed->id.':'.$feed->alias);
			}
			$img		= '<img src="'.$livemarks.'" alt="'.$feed->name.'" title="'.$feed->name.'" width="16" height="16" align="top" border="0"/>';
			$itemsA	= '<a href="'.$feed_link.'">'.$img.'</a>&nbsp;<a href="'.$feed_link.'">'.$feed->name.'</a>';
			$article->text 	= preg_replace( '{'. $matches[0][$i] .'}', $itemsA, $article->text );
		}
	  	// removes tags without matching module positions
		$article->text = preg_replace( $regex, '', $article->text );
	}
}