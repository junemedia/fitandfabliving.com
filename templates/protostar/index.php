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

$menu = $app->getMenu();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<script type="text/javascript">
    var mobile_domain ="m.fitandfabliving.com";
    // Set to false to not redirect on iPad.
    var ipad = false;
    // Set to false to not redirect on other tablets (Android , BlackBerry, WebOS tablets).
    var other_tablets = false;
    document.write(unescape("%3Cscript src='"+location.protocol+"//s3.amazonaws.com/me.static/js/me.redirect.min.js' type='text/javascript'%3E%3C/script%3E"));
	</script>

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />

	<?php
	// Use of Google Font
	if ($this->params->get('googleFont')) { ?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName'));?>', Arial, Helvetica, sans-serif;
			}
		</style>
	<?php } ?>

	<?php
	// Template color
	if ($this->params->get('templateColor')) { ?>
    <style type="text/css">
      a {
        color: #E4067C<?php //echo $this->params->get('templateColor');?>;
      }
      .navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover,
      .btn-primary {
        background: #E40079<?php //echo $this->params->get('templateColor');?>;

      }
      .navbar-inner {
        -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
      }
    </style>
	<?php } ?>

	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->

  <!-- Include the Union Tracker, include jquery if necessary -->
  <?php
  $document = JFactory::getDocument();
  $document->addScript('/media/jui/js/jquery.min.js');
  $document->addScriptDeclaration("
      var site_name = 'fitandfabliving';
      var site_path = window.location.href;
      var site_domain = '.fitandfabliving.com;path=/';
      jm_checkCookie(site_name, site_domain);

      var site_guest_id = jm_getCookie('SITE_GUEST_ID');
      var email = jm_getCookie('EMAIL_ID') ? jm_getCookie('EMAIL_ID'): 'NO_EMAIL' ;


      jm_push(site_name, site_path, site_guest_id, email);
      jQuery.noConflict(); ");
  ?>

  <?php include 'partials/ads/tynt.php'; ?>
  <?php include 'partials/ads/yieldbot.php'; ?>

</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>" >

<!--INFOLINKS_OFF-->

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRKVZZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WRKVZZ');</script>
<!-- End Google Tag Manager -->

	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">

			<!-- Header -->
			<div class="header" role="banner">
				<div id="logo">
					<a href="<?php echo $this->baseurl; ?>"><?php echo $logo;?></a>
				</div>

				<div class="header-inner clearfix">
					<?php //if($view !='home'){?>
					<table width="965px" border="0" align="center" style=" float: right; width: 734px;">
						<tbody><tr><td valign="top" height="100px" align="center" style="height:100px;">
							<div class="moduletableCustom">
                <center>
                  <?php include 'partials/ads/728x90atf.php'; ?>
								</center>
							</div>
						</td></tr>
						</tbody>
					</table>
					<?php //}?>
					<div class="pull-right">
					<?php if($user->id == 0){?>
					<!--<a href="./index.php/register"><span class="register">Subscribe </span></a> <font class="menu_sp">|</font>
					<a href="<?php echo JRoute::_("index.php?option=com_users&view=login");?>"><span class="register"> Sign In</span></a> -->
					<?php } else {
					$return = base64_encode($this->baseurl);
					$url = JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . $return, false);
					?>
					<a href="/index.php/submit-an-article"><span class="register">Submit An Article</span></a><font class="menu_sp">|</font>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile');?>"><span class="register"><?php echo $user->name;?></span></a><font class="menu_sp">|</font>
					<a href="<?php echo $url; ?>"><span class="register"> Sign Out</span></a>
					<?php }?>
					</div>
					<br/><br/>
					<div class="pull-right"  style="margin-top:10px;">
						<div id="search">
						<jdoc:include type="modules" name="position-0" style="none" />
						</div>
						<div id="social_media">
							<a href="http://on.fb.me/12Tvw58" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/facebook.png'?>" class="social_img"/></a>
							<a href="http://bit.ly/174qXCp" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/twitter.png'?>" class="social_img"/></a>
							<a href="http://bit.ly/16a8IQv" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/pinterest.png'?>" class="social_img"/></a>
							<a href="/index.php?option=com_obrss&task=feed&id=1:rss-feed" target="_blank" class="social_a"><img src="<?php echo JUri::root().'/images/rss.png'?>" class="social_img"/></a>
						</div>
					</div>
				</div>
			</div>
			<?php if ($this->countModules('position-1')) : ?>
			<div class="navigation" role="navigation">
				<!--<a href="<?php echo JRoute::_('index.php?option=com_content&view=category&id=8');?>">STAY FIT</a> <font class="v_line" >|</font>
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=category&id=9');?>">EAT HEALTHY</a> <font class="v_line" >|</font>
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=category&id=10');?>">LOSE WEIGHT</a> <font class="v_line" >|</font>
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=category&id=11');?>">FEEL GOOD</a> <font class="v_line" >|</font>
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=category&id=12');?>">LOOK FAB</a> <font class="v_line" >|</font>
				<a href="http://www.chewonthatblog.com/" class="menu_last" target="_blank">THE BLOG</a>-->
				<jdoc:include type="modules" name="position-1" style="none" />
			</div>
			<?php endif; ?>

			<jdoc:include type="modules" name="banner" style="xhtml" />
			<?php if($this->countModules('position-2')) : ?>
			<div class="breadcrumb">
				<jdoc:include type="modules" name="position-2" style="none" />
			</div>
			<?php endif;?>
			<?php //echo "<pre>";print_r($view.' '.$option);echo "</pre>";
				if ($option == 'com_content' && $view =='home') {?>
				<div class="rotator">
				<jdoc:include type="modules" name="position-9" style="none" />
				<SCRIPT>jQuery.noConflict();</SCRIPT>
				</div>
			<?php } ?>
			<?php if($option == 'com_content' && $view =='category' && $layout=='blog'){?>
			<div class="rotator">
				<jdoc:include type="modules" name="position-13" style="none" />
				<SCRIPT>jQuery.noConflict();</SCRIPT>
			</div>
			<?php }?>
			<div class="row-fluid">
				<?php if ($this->countModules('position-8')) : ?>
				<!-- Begin Sidebar -->
				<div id="sidebar" class="span3">
					<div class="sidebar-nav">
						<jdoc:include type="modules" name="position-8" style="xhtml" />
					</div>
				</div>
				<!-- End Sidebar -->
				<?php endif; ?>
				<div class="main_content" <?php if ($itemid =='797') { /* Giveaway page */ echo ' style="width:100%;" ';} ?>>
					<!-- Begin Content -->

					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<!-- End Content -->
                                        <div id="pubexchange_below_content"></div>

				</div>



				<?php if ($itemid !='797') { /* Giveaway page */ ?>
				<div id="right_side">
					<!-- Begin Right Sidebar -->
					<?php if ($this->countModules('position-7')) : ?>
					<jdoc:include type="modules" name="position-7" style="well" />
					<?php endif; ?>


					<?php if(($option == 'com_search' && $view =='search') || ($option == 'com_content' && $view == 'article')){?>

            <div class="right_ads top_space">
              <?php include 'partials/ads/300x250atf.php'; ?>
            </div>

            <div class="right_ads top_space">
                <div id="pubexchange_rail_by_partner_1"></div>
            </div>

            <div class="right_ads top_space">
              <?php include 'partials/ads/300x250btf.php'; ?>
            </div>

            <div class="right_ads top_space">
              <!-- Netseer ads BEGIN -->
              <div style="padding:10px 0px">
                <script type="text/javascript">
                  netseer_tag_id = "15417";
                  netseer_ad_width = "175";
                  netseer_ad_height = "100";
                  netseer_task = "ad";
                  netseer_imp_type = "1";
                  netseer_imp_src = "2";
                </script>
                <script src="http://cl.netseer.com/dsatserving2/scripts/netseerads.js" type="text/javascript"></script>
              </div>
              <!-- Netseer ads END -->
            </div>

					<?php } else { ?>
            <div class="right_ads top_space">
              <?php include 'partials/ads/300x250atf.php'; ?>
            </div>

            <div class="right_ads top_space">
              <div id="pubexchange_rail_by_partner_1"></div>
            </div>

            <div class="right_ads top_space">
              <?php include 'partials/ads/300x250btf.php'; ?>
            </div>

            <div class="right_ads top_space">
              <!-- Netseer ads BEGIN -->
              <div style="padding:10px 0px">
                <script type="text/javascript">
                  netseer_tag_id = "15417";
                  netseer_ad_width = "175";
                  netseer_ad_height = "100";
                  netseer_task = "ad";
                  netseer_imp_type = "1";
                  netseer_imp_src = "2";
                </script>
                <script src="http://cl.netseer.com/dsatserving2/scripts/netseerads.js" type="text/javascript"></script>
              </div>
              <!-- Netseer ads END -->
            </div>
					<?php } ?>
					<!-- End Right Sidebar -->
				</div>
				<?php } ?>

			</div>



			<!-- Footer -->
			<div class="footer" role="contentinfo">
			<!--<jdoc:include type="modules" name="footer" style="none" />-->
				<div class="sites_container">
					<div align="center" style="padding-top:10px;padding-bottom:20px;">
            <?php include 'partials/ads/728x90btf.php'; ?>
					</div>

					<a href="http://www.recipe4living.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/r4l.png"/></a>
					<a href="http://www.workitmom.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/wim.png"/></a>
					<a href="http://www.savvyfork.com/" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/cot.png"/></a>
					<!--<a href="http://www.runningwithmascara.com" class="footer_site_link" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/rwm.png"/></a>-->
				</div>
				<div class="footer_nav">
					<a href="/write-for-us" class="footer_link">Write for FitandFabLiving!</a> <font class="menu_sp">|</font>
					<a href="/aboutus" class="footer_link">About Us</a> <font class="menu_sp">|</font>
					<a href="/privacy-policy" class="footer_link">Privacy Policy</a> <font class="menu_sp">|</font>
					<a href="/terms-of-use" class="footer_link">Terms of Use</a> <font class="menu_sp">|</font>
					<a href="/subctr" class="footer_link">Unsubscribe</a> <font class="menu_sp">|</font>
					<a href="/contact-us" class="footer_link">Contact Us</a> <font class="menu_sp">|</font>
					<a href="/site-map" class="footer_link">Site Map</a>
				</div>
				<p class="copyright">&copy; <?php echo date('Y');?> June Media Inc. All rights reserved</p>

                                <!-- Start of pubexchange ads tags-->
                                <script>(function(d, s, id) {
                                    var js, pjs = d.getElementsByTagName(s)[0];
                                    if (d.getElementById(id)) return;
                                    js = d.createElement(s); js.id = id; js.async = true;
                                    js.src = "//cdn.pubexchange.com/modules/partner/fit__fab_living";
                                    pjs.parentNode.insertBefore(js, pjs);
                                  }(document, "script", "pubexchange-jssdk"));
                                </script>
                                <!-- End of pubexchange ads tags-->

			</div><!--End of footer-->
		</div>
	</div>

	<jdoc:include type="modules" name="debug" style="none" />
	<?php if (isset($_GET['cid'])) { include_once($_SERVER['DOCUMENT_ROOT']."/dhtml/dhtml.php"); } ?>

<!-- +SWOOP -->
<script type="text/javascript">
(function addSwoopOnce(domain) {
  var win = window;
  try {
    while (!(win.parent == win || !win.parent.document)) {
      win = win.parent;
    }
  } catch (e) {
    /* noop */
  }
  var doc = win.document;
  if (!doc.getElementById('swoop_sdk')) {
    var serverbase = doc.location.protocol + '//ardrone.swoop.com/';
    var s = doc.createElement('script');
    s.type = "text/javascript";
    s.src = serverbase + 'js/spxw.js';
    s.id = 'swoop_sdk';
    s.setAttribute('data-domain', domain);
    s.setAttribute('data-serverbase', serverbase);
    doc.head.appendChild(s);
  }
})('SW-10152718-3');
</script>
<!-- -SWOOP -->

<?php include 'partials/ads/underdog.php'; ?>
<?php include 'partials/ads/liveramp.php'; ?>

<?php
// no idea what this check is about...
if (!in_array($app->input->getCmd('id', ''), array('7427','7425'))) {
  include 'partials/ads/infolinks.php';
} ?>

</body>
</html>
