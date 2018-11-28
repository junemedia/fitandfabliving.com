<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript('templates/' .$this->template. '/js/template.js');

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/article.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Add current user information
$user = JFactory::getUser();

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
}
else
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<?php
	// Use of Google Font
	if ($this->params->get('googleFont'))
	{
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName'));?>', sans-serif;
			}
		</style>
	<?php
	}
	?>
	<?php
	// Template color
	if ($this->params->get('templateColor'))
	{
	?>
	<style type="text/css">
		a
		{
			color: <?php echo $this->params->get('templateColor');?>;
		}
		.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover,
		.btn-primary
		{
			background: <?php echo $this->params->get('templateColor');?>;
		}
		.navbar-inner
		{
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
		}
	</style>
	<?php
	}
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">

	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<!-- Header -->
			<div class="header" role="banner">
				<div id="logo">
					<a href="<?php echo $this->baseurl; ?>"><?php echo $logo;?></a>
				</div>
				
				<div class="header-inner clearfix">					
					<div class="pull-right"><span class="register">Subscribe </span> <font class="menu_sp">|</font> <span class="register"> Sign In</span></div>
					<br/><br/>
					<div class="pull-right"  style="margin-top:35px;">
						<div id="search"><input type="text" value=""></div> 
						<div id="social_media">
							<a href="#" class="social_a"><img src="<?php echo JUri::root().'/images/facebook.png'?>" class="social_img"/></a>
							<a href="#" class="social_a"><img src="<?php echo JUri::root().'/images/twitter.png'?>" class="social_img"/></a>
							<a href="#" class="social_a"><img src="<?php echo JUri::root().'/images/pinterest.png'?>" class="social_img"/></a>
							<a href="#" class="social_a"><img src="<?php echo JUri::root().'/images/rss.png'?>" class="social_img"/></a>
						</div>
					</div>
				</div>
			</div>
			<?php if ($this->countModules('position-1')) : ?>
			<div class="navigation" role="navigation">
				<a href="#">STAY FIT</a> <font class="v_line" >|</font> 
				<a href="#">EAT HEALTHY</a> <font class="v_line" >|</font> 
				<a href="#">LOSE WEIGHT</a> <font class="v_line" >|</font> 
				<a href="#">FEEL GOOD</a> <font class="v_line" >|</font> 
				<a href="#">LOOK FAB</a> <font class="v_line" >|</font> 
				<a href="#" class="menu_last">THE BLOG</a>
			</div>
			<?php endif; ?>
			<div class="breadcrumb">
				<a href="<?php echo $this->baseurl;?>" class="bread_home">HOME</a> / 
				<span>STAY FIT</span>
			</div>
			<div class="row-fluid">
				<div class="main_content">
					<div class="search_input">
						<input type="text" value="Purse" id="search_value" name="search_value" class="typein"/>
						<input type="submit" value="SEARCH" id="search_btn" name="search_btn" class="search_btn"/>
					</div>
					<div class="count_result">Results for <span>Purse</span>, showing 1-10 of 100</div>
					<div class="search_result">
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result1.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">A Peek Inside Lisa Ling's Purse</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result2.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">The Best Cold-Fighting Products</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result3.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">Common Fashion Injuries</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result4.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">The Best Cold-Fighting Products</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result1.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">A Peek Inside Lisa Ling's Purse</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result2.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">The Best Cold-Fighting Products</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result3.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">Common Fashion Injuries</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>		
						<div class="result_item">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/search_result4.jpg" class="result_item_img"/>
							<div class="result_content">
								<h2 class="result_title">The Best Cold-Fighting Products</h2>
								<div class="result_brief">Quisque eget ligula et nisi lacinia ultrices. Fusce dolor velit, commodo ut malesuada a, consectetur in libero. Nam posuere libero a velit varius pretium... <a href="#" class="read_more">read more...</a></div>
								<!--<div class="result_tips"><img class="comment_img" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/comment.png"/>32 <font class="slash">/</font> LOOK FAB</div>-->								
							</div>
						</div>
					</div>
				</div>
				<div id="right_side">
					<!-- Begin Right Sidebar -->
					<div class="related_article">
						<h2 class="content_h2">RELATED ARTICLES</h2>
						<div class="s_line" style="width:290px"></div>
						<div class="related_detail">
							<div class="article_item">
								<a href="#"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/article_related1.jpg"/>
								<span>Tips To Get Toned Arms Faster</span></a>
							</div>
							<div class="grey_line"></div>
							<div class="article_item">
								<a href="#"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/article_related2.jpg"/>
								<span>The Ultimate Leg And Arms Workouts</span></a>
							</div>
							<div class="grey_line"></div>
							<div class="article_item">
								<a href="#"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/article_related3.jpg"/>
								<span>The Tank Top Arms Workout</span></a>
							</div>
							<div class="grey_line"></div>
							<div class="article_item">
								<a href="#"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/article_related4.jpg"/>
								<span>Get Sexy Shoulder In One Month</span></a>
							</div>						
						</div>
					</div>
					<div class="right_ads top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/ads2.jpg"/></div>
					<div class="subscribe top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/subscribe.jpg"/></div>
					<div class="right_ads top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/ads4.jpg"/></div>
					<!-- End Right Sidebar -->
				</div>

			</div>
			<!-- Footer -->
			<div class="footer" role="contentinfo">
				<div class="sites_container">
					<a href="http://www.recipe4living.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/r4l.png"/></a>
					<a href="http://www.workitmom.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/wim.png"/></a>
					<a href="http://www.chewonthatblog.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/cot.png"/></a>
				</div>
				<div class="footer_nav">               
					<a href="#" class="footer_link">Write for FitandFabLiving!</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">About Us</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">Privacy Policy</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">Terms of Use</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">Unsubscribe</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">Contact Us</a> <font class="menu_sp">|</font> 
					<a href="#" class="footer_link">Site Map</a>			
				</div>
				<p class="copyright">&copy; <?php echo date('Y');?> June Media Inc. All rights reserved</p>
			</div>
		</div>		
	</div>
	
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
