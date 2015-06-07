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

// Add current user information
$user = JFactory::getUser();


// Logo file
if ($params->get('logoFile'))
{
	$logo = JUri::root() . $params->get('logoFile');
}
else
{
	$logo = $this->baseurl . "/templates/" . $this->template . "/images/logo.png";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->title; ?> <?php echo $this->error->getMessage();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo $this->language; ?>" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

	<?php
		$debug = JFactory::getConfig()->get('debug_lang');
		if ((defined('JDEBUG') && JDEBUG) || $debug)
		{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
	<?php
		}
	?>
	<?php
	// If Right-to-Left
	if ($this->direction == 'rtl')
	{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-rtl.css" type="text/css" />
	<?php
	}
	// Use of Google Font
	if ($params->get('googleFont'))
	{
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $params->get('googleFontName');?>' rel='stylesheet' type='text/css'>
	<?php
	}
	?>
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<?php
	// Template color
	if ($params->get('templateColor'))
	{
	?>
	<style type="text/css">
		body.site
		{
			background-color: <?php echo $params->get('templateBackgroundColor');?>
		}
		a
		{
			color: <?php echo $params->get('templateColor');?>;
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
<table width="965px" border="0" align="center">
 			<tbody><tr><td valign="top" height="100px" align="center" style="height:100px;">
						<div class="moduletableCustom">
						<center><!-- Javascript tag  -->
			<!-- begin ZEDO for channel:  New Fit&Fab 728x90 top , publisher: AmpereMedia , Ad Dimension: Super Banner - 728 x 90 -->
			<script language="JavaScript">
			var zflag_nid="608"; var zflag_cid="60/54/4"; var zflag_sid="1"; var zflag_width="728"; var zflag_height="90"; var zflag_sz="14"; var zflag_click="[INSERT_CLICK_TRACKER_MACRO]"; 
			</script>
			<script language="JavaScript" src="http://c5.zedo.com/jsc/c5/fo.js"></script>
			<!-- end ZEDO for channel:  New Fit&Fab 728x90 top , publisher: AmpereMedia , Ad Dimension: Super Banner - 728 x 90 --></center>
						</div>	
			</td></tr>
			</tbody>
		</table>
	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<div class="header" role="banner">
				<div id="logo">
					<a href="<?php echo JURI::base(); ?>"><img src="<?php echo $logo;?>" alt="<?php echo $sitename; ?>" /></a>
				</div>
				
				<div class="header-inner clearfix">					
					<!--<div class="pull-right">
					<?php if($user->id == 0){?>
					<a href="./index.php/register"><span class="register">Subscribe </span></a> <font class="menu_sp">|</font> 
					<a href="<?php echo JRoute::_("index.php?option=com_users&view=login");?>"><span class="register"> Sign In</span></a>
					<?php } else {
					$return = base64_encode($this->baseurl);
					$url = JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . $return, false);
					?>
					<a href="/index.php/submit-an-article"><span class="register">Submit An Article</span></a><font class="menu_sp">|</font> 
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile');?>"><span class="register"><?php echo $user->name;?></span></a><font class="menu_sp">|</font> 
					<a href="<?php echo $url; ?>"><span class="register"> Sign Out</span></a>
					<?php }?>
					</div>
					<br/><br/>-->
					<div class="pull-right"  style="margin-top:35px;">
						<div id="search">
						<?php
						// Display position-0 modules
						echo $doc->getBuffer('modules', 'position-0', array('style' => 'none'));
						?>
						</div> 
						<div id="social_media">
							<a href="http://on.fb.me/12Tvw58" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/facebook.png'?>" class="social_img"/></a>
							<a href="http://bit.ly/174qXCp" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/twitter.png'?>" class="social_img"/></a>
							<a href="http://bit.ly/16a8IQv" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/pinterest.png'?>" class="social_img"/></a>
							<a href="#" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/rss.png'?>" class="social_img"/></a>
						</div>
					</div>
				</div>
			</div>
			<div class="navigation">
				<?php
				// Display position-1 modules
				echo $doc->getBuffer('modules', 'position-1', array('style' => 'none'));
				?>
			</div>
			<!-- Banner -->
			<div class="banner">
				<?php echo $doc->getBuffer('modules', 'banner', array('style' => 'xhtml')); ?>
			</div>
			<div class="row-fluid">
				<div id="content" class="span12">
					<!-- Begin Content -->
					<h1 class="page-header"><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
					<div class="well">
						<div class="row-fluid">
							<div class="span6">
								<p><strong><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
								<p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
								<ul>
									<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
								</ul>
							</div>
							<div class="span6">
								<?php if (JModuleHelper::getModule('search')) : ?>
									<p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
									<p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
									<?php echo $doc->getBuffer('module', 'search'); ?>
								<?php endif; ?>
								<p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
								<p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn"><i class="icon-home"></i> <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
							</div>
						</div>
						<hr />
						<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
						<blockquote>
							<span class="label label-inverse"><?php echo $this->error->getCode(); ?></span> <?php echo $this->error->getMessage();?>
						</blockquote>
					</div>
					<!-- End Content -->
				</div>
			</div>
			<!-- Footer -->
			<div class="footer" role="contentinfo">
			<!--<jdoc:include type="modules" name="footer" style="none" />-->
				<div class="sites_container">
					<a href="http://www.recipe4living.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/r4l.png"/></a>
					<a href="http://www.workitmom.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/wim.png"/></a>
					<a href="http://www.chewonthatblog.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/cot.png"/></a>
					<a href="http://www.runningwithmascara.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/rwm.png"/></a>
				</div>
				<div class="footer_nav">               
					<a href="/write-for-us" class="footer_link">Write for FitandFabLiving!</a> <font class="menu_sp">|</font> 
					<a href="/aboutus" class="footer_link">About Us</a> <font class="menu_sp">|</font> 
					<a href="/privacy-policy" class="footer_link">Privacy Policy</a> <font class="menu_sp">|</font> 
					<a href="/terms-of-use" class="footer_link">Terms of Use</a> <font class="menu_sp">|</font> 
					<a href="/subctr" target=_blank class="footer_link">Unsubscribe</a> <font class="menu_sp">|</font> 
					<a href="/contact-us" class="footer_link">Contact Us</a> <font class="menu_sp">|</font> 
					<a href="/site-map" class="footer_link">Site Map</a>			
				</div>
				<p class="copyright">&copy; <?php echo date('Y');?> June Media Inc. All rights reserved</p>				
			</div>
		</div>
	</div>
	
	<?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
</body>
</html>
