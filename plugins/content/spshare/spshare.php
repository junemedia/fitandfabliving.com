<?php
/**
* @author    JoomShaper http://www.joomshaper.com
* @copyright Copyright (C) 2010 - 2013 JoomShaper
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2
*/

// no direct access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.plugin.plugin');

class plgContentSPShare extends JPlugin
{	
	var $_path;
	var $_url;
	var $_text;
	var $_image;
	var $position;
	
	// plugin core
	function plgContentSPShare(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		parent::__construct($subject, $config);
		$this->plugin = JPluginHelper::getPlugin('content', 'spshare');
		$this->position 	= $this->params->get('position');	
	}

	function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{
		$mainframe = JFactory::getApplication();
		if ($mainframe->isAdmin()) {
			return '';
		}
		if ($this->position == 2)
			return $this->getSPShare($context, $article);
	}	
	
	public function onContentAfterTitle($context, &$article, &$params, $page = 0){
		$mainframe = JFactory::getApplication();
		if ($mainframe->isAdmin()) {
			return '';
		}	
		if ($this->position == 3 || $this->position == 4) 
			return $this->getSPShare($context, $article);		
	}
		
	// function to work when preparing the content on frontpage or listing pages
	function onContentBeforeDisplay($context, &$article, &$params, $page=0)
	{
		$mainframe = JFactory::getApplication();
		if ($mainframe->isAdmin()) {
			return '';
		}		
		if (($this->getParam('show_intro',1)==1) && ($this->position == 3 || $this->position == 4)) {
			$params->set('show_intro',0);
			$article->params->set('show_intro',0);
		}//disable show_intro
				
		if ($this->position == 1)
			return $this->getSPShare($context, $article);
	}
	
	function getSPShare($context, &$article)
	{
		global $option;

		if (!isset($article->catid)) {
			$article->catid = 0;
		}

		$option 	= JRequest::getCmd('option');
		
		//Joomla category
		$categories 		= array();
		$cats 				= $this->getParam('catids');
		
		if (is_array($cats)) {
			$categories 	= $cats;
		} else {
			$categories[] 	= $cats;
		}
		
		//K2 category
		$k2categories 		= array();
		$k2cats 			= $this->getParam('k2catids');
		
		if (is_array($k2cats)) {
			$k2categories 	= $k2cats;
		} else {
			$k2categories[] = $k2cats;
		}	

		//Parameters
		$layout_style		= $this->getParam('layout_style');
		$linkedin			= $this->getParam('linkedin');
		$twitter			= $this->getParam('twitter');
		$gplus				= $this->getParam('gplus');
		$pinterest			= $this->getParam('pinterest');
		$digg				= $this->getParam('digg');
		$like_btn			= $this->getParam('like_btn');
		$fb_width 			= $this->getParam('fb_width');
		
		//Button Style
		$linkedin_style		= ($layout_style=='button_count') ? "right" : "top";
		$twitter_style		= ($layout_style=='button_count') ? "horizontal" : "vertical";
		$gplus_style		= ($layout_style=='button_count') ? "medium" : "tall";
		$pin_style			= ($layout_style=='button_count') ? "horizontal" : "vertical";
		$digg_style			= ($layout_style=='button_count') ? "DiggCompact" : "DiggMedium";
		$like_btn_style		= ($layout_style=='button_count') ? "button_count" : "box_count";
		
		// enable commenting if everything is alright
		if (in_array($article->catid, $categories) || (in_array($article->catid, $k2categories) && !$this->isEmptyArr($k2categories))) {
			$output = "";
			
			if ($option == 'com_content') {
				$this->_path 	= ContentHelperRoute::getArticleRoute($article->id.':'.$article->alias, $article->catid.':'.$article->category_alias);
				$this->_image = JURI::base() . $this->getImage($article->introtext, $article->images);
			} elseif($option == "com_k2") {
				$this->_path = K2HelperRoute::getItemRoute($article->id.':'.urlencode($article->alias), $article->catid.':'.urlencode($article->category->alias));
				$this->_image = JURI::base() . $this->getK2Image($article->id, $article->introtext);
			} 
			
			/*Article Link*/
			$url 			= JURI::base();
			$url 			= new JURI($url);
			$root 			= $url->getScheme() . '://' . $url->getHost();
			$link 			= JRoute::_($this->_path);
			$this->_url 	= $root . $link;
			/*End Article Link*/
			
			$this->_text = $article->title;
			
			//Javascript
			$doc = JFactory::getDocument();
			
			if ($linkedin)
				$doc->addScript('http://platform.linkedin.com/in.js');//Javascript for Linkedin share button
				
			if ($twitter)
				$doc->addScript('http://platform.twitter.com/widgets.js');//Javascript for Twitter Button
				
			if ($gplus)
				$doc->addScript('https://apis.google.com/js/plusone.js');//Javascript for The + Button
				
			if ($digg)
				$doc->addScriptDeclaration("
					(function() {
					  var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
					  s.type = 'text/javascript';
					  s.async = true;
					  s.src = 'http://widgets.digg.com/buttons.js';
					  s1.parentNode.insertBefore(s, s1);
					})();				
				");//Javascript for digg Button

			if ($like_btn != 3) {
				$doc->addScriptDeclaration("
					(function(d){
					  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
					  js = d.createElement('script'); js.id = id; js.async = true;
					  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
					  d.getElementsByTagName('head')[0].appendChild(js);
					}(document));				
				");
				}//Javascript for Facebook Send Button
						
			//Output
			$output .="<div class='spshare'>";
			if ($linkedin)
				$output .= "<div class='sp_linkedin spshare_fltlft'><script type='IN/Share' data-url=" . $this->_url . " data-counter='" . $linkedin_style . "'></script></div>";
			
			if ($twitter)
				$output .= "<div class='sp_twitter spshare_fltlft'><a href='https://twitter.com/share' class='twitter-share-button' data-text='" . $article->title. "' data-url='" . $this->_url. "' data-count='" . $twitter_style . "'>Tweet</a></div>";

			if ($gplus)
				$output .= "<div class='sp_plusone spshare_fltlft'><g:plusone href='" . $this->_url . "' size='" . $gplus_style . "'></g:plusone></div>";
			
			if ($pinterest)
				$output .= "<div class='sp_pinterest spshare_fltlft'><a href='http://pinterest.com/pin/create/button/?url=" . $this->_url . "&amp;media=" . $this->_image . "&amp;description=" . $this->_text . "' class='pin-it-button' count-layout='" . $pin_style ."'><img border='0' src='//assets.pinterest.com/images/PinExt.png' title='Pin It' /></a></div>";
			
			if ($digg)
				$output .= "<div class='sp_digg spshare_fltlft'><a class='DiggThisButton " . $digg_style . "' href='http://digg.com/submit?url='".$this->_url."'></a></div>";
							
			if ($like_btn == 1) {
				$output .= "<div class='sp_fblike spshare_fltlft'><div class='fb-like' data-href='" . $this->_url . "' data-send='true' data-layout='" . $like_btn_style . "' data-width='" . $fb_width . "' data-show-faces='flase'></div></div>";
			} else if ($like_btn == 2) {
				$output .= "<div class='sp_fblike spshare_fltlft'><div class='fb-like' data-href='" . $this->_url . "' data-send='false' data-layout='" . $like_btn_style . "' data-width='" . $fb_width . "' data-show-faces='flase'></div></div>";			
			}
			
			$output .="<div style='clear:both'></div></div>";
			$this->getStyle();			
			
			return $output;		
		}

	}
	
	function getImage ($text, $image_src="") {
		$image_path = '';
		if (JVERSION>=2.5 && @$image_src->image_intro) {
			$image_path = $image_src->image_intro;
		} elseif (JVERSION>=2.5 && @$image_src->image_fulltext) {
			$image_path = $image_src->image_fulltext;
		}else {
			preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);
			if (isset($matches[1])) {
				$image_path = $matches[1];
			}		
		}
		return $image_path;
	}

	function getK2Image($id, $text) {
		$image_path = '';
		if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image" . $id) . '_XL.jpg')) {
			$image_path 	= 'media/k2/items/cache/' . md5("Image" . $id) . '_XL.jpg';
		} else {
			preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);	
			if (isset($matches[1])) {
				$image_path = $matches[1];
			}		
		}
		return $image_path;		
	}	
	
	// short function to receive $_params value
	function getParam($param) {
		return $this->params->get($param);
	}
	
	function isEmptyArr($arr = array())
	{
		if(!empty($arr))
		{
			$count = count($arr);
			$check = 0;
			foreach($arr as $id=>$item)
			{
				if(empty($item)) $check++;
			}
			if($check != $count) return false;
		}
		return true;
	}
	
	// after preparing the <body> tag set the disqus comments count JS script
	function onAfterRender() {
		$pinterest			= $this->getParam('pinterest');
		$option 			= JRequest::getVar('option');
		$view 				= JRequest::getVar('view');	
		if (($pinterest) &&($option == 'com_content' && ($view == "category" || $view == "featured")) || ($option == 'com_k2' && $view == "itemlist")) {
			$body 			= JResponse::getBody();
			$body 			= str_replace('</body>', '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>'."\n</body>", $body);
			JResponse::setBody($body);
		}	
	}	
	
	//Get styles
	function getStyle() {
		if(defined('_SPSHARE')) 
			return;
		define ('_SPSHARE',1);	
		$doc 				 = JFactory::getDocument();
		$layout_style		 = $this->getParam('layout_style');
		$fb_width 			 = $this->getParam('fb_width');
		$styles 	     	 = ".spshare_fltlft {display:inline-block}";
		if ($this->position==4) {
			$styles 		.= ".spshare {float:right; margin:10px 0 10px 10px}";
		} else {
			$styles 		.= ".spshare {margin:10px 0}";		
		}	
		$styles				.= ".sp_fblike {width:{$fb_width}px}";
		$styles 			.= ".sp_pinterest, .sp_linkedin,.sp_digg {margin-right:10px}";
		$styles 			.= ".sp_pinterest a {float:left}";
		$styles 			.= ($layout_style=='button_count') ? ".sp_plusone {width:70px}" : ".sp_plusone {width:62px}";
		$styles 			.= ($layout_style=='button_count') ? ".sp_twitter {width:106px}" : ".sp_twitter {width:66px}";
		$doc->AddStyledeclaration ($styles);
	}
}