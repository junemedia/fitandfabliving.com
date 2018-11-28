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
			<div class="sub-menu">
				<a href="#">Workouts</a> <font class="v_grey_line">|</font> <a href="#">Techniques</a> <font class="v_grey_line">|</font> <a href="#">Treads</a> <font class="v_grey_line">|</font> <a href="#">Gear</a>
			</div>
			<div class="breadcrumb">
				<a href="<?php echo $this->baseurl;?>" class="bread_home">HOME</a> / 
				<span>STAY FIT</span>
			</div>
			
			<?php endif; ?>
			<div class="rotator">
				<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/section_slide.jpg"/>
			</div>
			<div class="row-fluid">
				<div class="main_content">
					<div id="news">
						<div class="section_title"><h2 class="content_h2 f_left" >WHAT'S NEW</h2><a class="see_all" href="#">See all  &gt;&gt;</a></div>
						<div id="section_news_boxes">						
							<div id="box_1" class="box">
								<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/section_new1.jpg" class="section_box_img"/>
								<div class="wrap_box_title">A Total Body Makeover</div>
								<div class="box_content">Lorem ipsum dolor sit amet consectetur brevis estiam ui convictus este... <a href="#" class="read_more">read more...</a></div>
							</div>
							<div id="box_2" class="box">
								<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/section_new2.jpg" class="section_box_img"/>
								<div class="wrap_box_title">Tone Up Fast</div>
								<div class="box_content">Lorem ipsum dolor sit amet consectetur brevis estiam ui convictus este... <a href="#" class="read_more">read more...</a></div>
							</div>
							<div id="box_3" class="box">
								<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/section_new3.jpg" class="section_box_img"/>
								<div class="wrap_box_title">Love Your Run</div>
								<div class="box_content">Lorem ipsum dolor sit amet consectetur brevis estiam ui convictus este... <a href="#" class="read_more">read more...</a></div>
							</div>
						</div>
					</div>
					<div class="long_line"></div>
					<div id="work_out">
						<div class="section_title"><h2 class="content_h2 f_left" >WORKOUTS</h2><a class="see_all" href="#">See all  &gt;&gt;</a></div>
						<div class="work_out_box">
							<div class="box_left">
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/workout1.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Look Hot In Your Bikini</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/workout2.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Try Pilates!</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/workout3.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Total Body Workout</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/workout4.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Celebrity Get-Fit Secrets</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
							</div>
							<div class="box_right">
								<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/workout5.jpg"/>	
								<div class="workout_big">
									<h2 class="workout_big_title">No-Equipment Workouts</h2>								
									<div class="stay_box_brief">Lorem ipsum dolor sit amet vensquam et aeneatuconsecteturi brevis... <a href="#" class="read_more">read more...</a></div>
								</div>
							</div>
						</div>						
					</div>
					<div class="long_line"></div>
					<div id="techniques">
						<div class="section_title"><h2 class="content_h2 f_left" >TECHNIQUES</h2><a class="see_all" href="#">See all  &gt;&gt;</a></div>
						<div class="work_out_box">
							<div class="box_left">
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/tech1.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Look Hot In Your Bikini</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/tech2.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Try Pilates!</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/tech3.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Total Body Workout</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
								<div class="workout_item">
									<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/tech4.jpg"/>
									<div class="workout_item_content">
										<h2 class="workout_box_title">Celebrity Get-Fit Secrets</h2>
										<div class="stay_box_brief">Lorem ipsum dolor sit amet consecteturib.. <a href="#" class="read_more">read more...</a></div>
									</div>
								</div>
							</div>
							<div class="box_right">
								<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/tech5.jpg"/>	
								<div class="workout_big">
									<h2 class="workout_big_title">No-Equipment Workouts</h2>								
									<div class="stay_box_brief">Lorem ipsum dolor sit amet vensquam et aeneatuconsecteturi brevis... <a href="#" class="read_more">read more...</a></div>
								</div>
							</div>
						</div>						
					</div>
					<div class="long_line"></div>
					<div id="trends">
						<div class="section_title"><h2 class="content_h2 f_left" >TRENDS</h2><a class="see_all" href="#">See all  &gt;&gt;</a></div>
						<div class="tg_box">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/trends1.jpg" class="tg_box_img"/>
							<div class="tg_box_content">
								<h2 class="tg_box_title">What's The Difference Between Upward Facing Dog and CobraPose?</h2>
								<div class="tg_box_brief">Lorem ipsum dolor sit amet consectetur brevis estiam esu et aeneat mordumuesci belitum crasceat et moridu velit este memphis... <a href="#" class="read_more">read more...</a></div>							
							</div>
						</div>
						<div class="tg_box box_top_space">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/trends2.jpg"class="tg_box_img"/>
							<div class="tg_box_content">
								<h2 class="tg_box_title">39 Salads You Can Grill, The "Next Hunger Games" and More!</h2>
								<div class="tg_box_brief">Lorem ipsum dolor sit amet consectetur brevis estiam esu et aeneat mordumuesci belitum crasceat et moridu velit este memphis... <a href="#" class="read_more">read more...</a></div>							
							</div>
						</div>
					</div>
					<div class="long_line"></div>
					<div id="gear">
						<div class="section_title"><h2 class="content_h2 f_left" >GEAR</h2><a class="see_all" href="#">See all  &gt;&gt;</a></div>
						<div class="tg_box">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/gear1.jpg" class="tg_box_img"/>
							<div class="tg_box_content">
								<h2 class="tg_box_title">Pre-Order the New Tour de France Indoor Bike (or Win One From Us!)</h2>
								<div class="tg_box_brief">Lorem ipsum dolor sit amet consectetur brevis estiam esu et aeneat mordumuesci belitum crasceat et moridu velit este memphis... <a href="#" class="read_more">read more...</a></div>							
							</div>
						</div>
						<div class="tg_box box_top_space">
							<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/gear2.jpg"class="tg_box_img"/>
							<div class="tg_box_content">
								<h2 class="tg_box_title">Lose Weight on the Treadmill Without Running</h2>
								<div class="tg_box_brief">Lorem ipsum dolor sit amet consectetur brevis estiam esu et aeneat mordumuesci belitum crasceat et moridu velit este memphis... <a href="#" class="read_more">read more...</a></div>							
							</div>
						</div>
					</div>				
				</div>
				<div id="right_side">
					<!-- Begin Right Sidebar -->
					<div class="right_ads top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/ads2.jpg"/></div>
					<div class="subscribe top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/subscribe.jpg"/></div>
					<div class="right_ads top_space"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/ads4.jpg"/></div>
					<div class="right_featured_video top_space">
						<h1 class="right_featured_title">FEATURED VIDEO OF THE WEEK</h1>
						<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/temp/right_vidwo.jpg" />
						<div class="right_video_content">
							<h2>Wanna WorkOut Daily Like Me?</h2>
							<div class="right_vieo_brief">
							Lorem ipsum dolor sit amet consectetur brevisuum crasceat et moridu velit este... <a href="#" class="read_more">read more...</a>
							</div>
						</div>
					</div>
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
