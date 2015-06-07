<?php

$message = '';
$pixel = '';

if (isset($_POST['submit']) && $_POST['submit'] == 'Enter to Win!') {
	$guid = strtoupper(trim($_POST['guid']));
	$EMAIL = trim($_POST['EMAIL']);
	$FNAME = trim($_POST['FNAME']);
	
	if ($EMAIL == '') {
		$message = 'Email address is invalid';
	}
	
	if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $EMAIL)) { $message = 'Email address is invalid'; }
	list($prefix, $domain) = split("@",$EMAIL);
	if (!getmxrr($domain, $mxhosts)) { $message = 'Email address is invalid'; }
	
	include_once("functions.php");
	if (LookupImpressionWise($EMAIL) == false) { $message = "Invalid email address!"; }
	if (BullseyeBriteVerifyCheck($EMAIL) == false) { $message = "Invalid email address!"; }
	
	if ($message == '') {
		
		switch($guid) {
			case '7173914311CC4DABB4C023DD53817A4B':
				$subcampid = '3497';
				break;
			case '1C63079B477A49FAA318655E0990F91E':
				$subcampid = '3498';
				break;
			case 'E0817722B67F4147BFF01C1DC8319F89':
				$subcampid = '3595';
				break;
			case '8C5FA101A6BB478486CDB870D67AA600':
				$subcampid = '4326';	// Shop At Home 0615
				break;
			case 'A31CE44999AC48FE920D61E9774C7AE2':
				$subcampid = '3641';	// Facebook paid search
				break;
			case '9CB41935EFFC43B68753D4B6562EC716':
				$subcampid = '3779';	// Adjump
				break;
			case 'AA7AB7FDD5484E44980E4F504BC96C54':
				$subcampid = '3605';
				break;
			case '5D419A5D0F124FBEAA4A2DEBAB01DA6F':
				$subcampid = '3628';	// Quick Rewards
				break;
			default:
				$subcampid = '4342';	// FF Default Giveaway 0615
		}
		
		$ipaddr = trim($_SERVER['REMOTE_ADDR']);
		
		$FNAME = addslashes($FNAME);
		
		$fire_cake_pixel = "";
		// check for dupes before signing up...
		$dupes_response = strtoupper(file_get_contents("http://r4l.popularliving.com/check_record.php?email=$EMAIL&type=emailpluslistid&listid=410,448,411"));
		if (strstr($dupes_response, 'TRUE')) {
			$fire_cake_pixel = "<iframe src='http://sinettrk.com/p.ashx?o=13330&t=$EMAIL' height='1' width='1' frameborder='0'></iframe>";
		}
		
		$sPostingUrl = "http://fitfab.popularliving.com/fitfab_api_giveaway.php?email=$EMAIL&sublists=410,448,411&subcampid=$subcampid&ipaddr=$ipaddr&keycode=kfdj49358gkj359gjk55&fname=$FNAME";
		$response = strtolower(file_get_contents($sPostingUrl));
		
		$site_domain = trim($_SERVER['SERVER_NAME']);
		
		setcookie("EMAIL_ID", $EMAIL, time()+642816000, "/", ".fitandfabliving.com");
		
		$gtm_pixel = "<!-- Google Tag Manager -->
				<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=GTM-WRKVZZ\"
				height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-WRKVZZ');
				dataLayer.push({'event': 'giveawayfitandfabliving'});</script>
				<!-- End Google Tag Manager -->";
		
		$message = 'Success! Check your email to confirm sign up.'.'<iframe frameborder="0" width="1" height="1" src="http://'.$site_domain.'/signup/giveaway-thankyou.html"></iframe>'."<img src='http://jmtkg.com/plant.php?email=$EMAIL' width=0 height=0></img>".$fire_cake_pixel.$gtm_pixel;
		
		$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=s&f=FFGiveaway$guid' width='0' height='0' border='0' />";
		$style = "color:green;font-weight:bold;";
		$EMAIL = '';
		$FNAME = '';
		$LNAME = '';
	} else {
		$style = "color:red;font-weight:bold;";
	}
} else {
	$guid = strtoupper(trim($_GET['guid']));
	$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=d&f=FFGiveaway$guid' width='0' height='0' border='0' />";
}

if (false && date('m') == 03 || date('m') == 04) {
	$giveaway_title = "Win A LifeTrak Zone C410 Fitness Tracker From LifeTrak!";
	$giveaway_text = "Kick start your health and fitness program by winning the LifeTrak Zone C410 fitness tracker. We are giving one of these fitness trackers away to one lucky winner. This wearable device tracks your activity during the day and night and all of the information can be synced to your personal devices. All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until April 30, 2014 at 11:59 PM CST. Good luck!";
	$giveaway_top_img = "http://pics.fitandfabliving.com/giveaway/FF_giveaway_LifeTrak_main.jpg";
	$giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/FF_giveaway_LifeTrak_img.png";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.fitandfabliving.com/giveaway/FF_giveaway_LifeTrak_logo.png">';
}

if (date('m') == 05 || date('m') == 06) {
	$giveaway_title = "";
	$giveaway_text = "Evolution Fresh is a company committed to serving healthy, cold-pressed, high pressure processed (HPP) juices full of fruits and vegetables. They are all about creating closest to nature, delicious offerings in so many different flavors. When a drink like this is cold-pressed, it means there is very little standing between you and those essential nutrients. We've teamed up with Evolution Fresh for an amazing giveaway this May. One lucky winner will receive a $50 Whole Foods gift card good for all Evolution Fresh juices and smoothies. What are you waiting for? Enter to win now!";
	$giveaway_top_img = "";
	$giveaway_right_img = "";
	$giveaway_extra_right_img = '<img src="http://pics.fitandfabliving.com/giveaway/FF_Juices-1.JPG" width="120%">';
}

if (false&&date('m') == 07) {
	$giveaway_title = "Win A Gaiam Balance Ball Chair!";
	$giveaway_text = "Do you sit in a chair all day long at work? Well I do too, and I was tired of feeling completely inactive all day at work. We are giving one of these chairs away to one lucky winner. The Gaiam Balance Ball Chair comes with a guide which includes several work-friendly mini workouts and stretches. All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until July 31, 2014 at 11:59 PM CST. Good luck!";
	$giveaway_top_img = "images/Gaiam-banner.png";
	$giveaway_right_img = "images/Gaiam-product.png";
	$giveaway_extra_right_img = '<br><br><img src="images/Gaiam-logo.png" width="50%">';
}

if (false&&date('m') == 8) {
	$giveaway_title = "Win A Fitmark Sport Tote!";
	$giveaway_text = "Do you feel like you are always on the go? I know I do. Instead of bring three bags with me everywhere I go, now I just use a Fitmark Sport Tote. It has several different compartments and pockets to keep everything organized from the office to the gym. It even comes with a laundry bag to keep your dirty clothes or gym shoes in. We're giving one of these fantastic bags away to one lucky winner.  All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until August 31, 2014 at 11:59 PM CST. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/Fitmark_product.png";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.fitandfabliving.com/giveaway/Fitmark_Logo.png" width="50%">';
}

if (false&&date('m') == 9 || date('m') == 10) {
	$giveaway_title = "Win A Crabtree and Evelyn Gift! - EXTENDED Through October!";
	$giveaway_text = "Do you feel like you're always over-paying when you get your nails done? Stop breaking the bank and start giving yourself a manicure and pedicure at home! This month, Crabtree and Evelyn will be giving away one gift basket filled with fabulous goodies that will satisfy all of your manicure and pedicure needs. All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until October 31, 2014 at 11:59 PM CST. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/Crabtree_and_Evelyn_Product.png";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.fitandfabliving.com/giveaway/Crabtree_and_Evelyn_Logo.png" style="max-width:400px;">';
}

if (false&&date('m') == 11 || date('m') == 12) {
	$giveaway_title = "Win A Rowenta Pro Auto Sensor Hair Dryer!";
	$giveaway_text = "Have you been trying to grow out your hair for what seems like forever?  Well stop dreaming of long locks and start changing your bad habits. If you use a hair dryer or other styling tools you are probably contributing to the problem. Don't worry, you don't have to start going all natural, but there is a solution! Our friends at Rowenta and Aviva have teamed up to create the most fabulous haircare duo. Lucky for you, they are giving away a prize pack including a Rowenta Pro Auto Sensor Hair Dryer and a two month supply of Aviva Advanced Hair Nutrition. All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until December 31, 2014 at 11:59 PM CST. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/Rowenta-product.jpg";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.fitandfabliving.com/giveaway/Rowenta-logo.jpg" style="max-width:400px;">';
}

if (false&&date('m') == "01" || date('m') == "02") {
    $giveaway_title = "Win A Rowenta Pro Auto Sensor Hair Dryer!";
    $giveaway_text = "Have you been trying to grow out your hair for what seems like forever? Well stop dreaming of long locks and start changing your bad habits. If you use a hair dryer or other styling tools you are probably contributing to the problem. Don't worry, you don't have to start going all natural, but there is a solution! Our friends at Rowenta and Aviva have teamed up to create the most fabulous haircare duo. Lucky for you, they are giving away a prize pack including a Rowenta Pro Auto Sensor Hair Dryer and a two month supply of Aviva Advanced Hair Nutrition. All you have to do is sign up below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until February 28, 2015 at 11:59 PM CST. Good luck!";
    $giveaway_top_img = "";
    $giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/Rowenta-product.jpg";
    $giveaway_extra_right_img = '<br><br><img src="http://pics.fitandfabliving.com/giveaway/Rowenta-logo.jpg" style="max-width:400px;">';
}

if (false && date('m') == 3) {
	$giveaway_title = "Win Two Large Yankee Candles From The New Spring Collection!";
	$giveaway_text = "This month our giveaway is featuring everyone's favorite premium scented candle brand! The Yankee Candle Company, is giving away two large candles from the newest collection to one lucky winner. Start your spring cleaning early this year and embrace these fresh scents in the comfort of your home.<br>
The winner of the giveaway will win one large Red Raspberry candle, and one large Picnic in the Park candle. All you have to do is sign up and below and enter to win. Plus, you'll be an insider to all the new beauty, food, and fitness content we have to offer! Entries will be accepted until March 31, 2015 at 11:59 PM CST. Good luck!
";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.fitandfabliving.com/giveaway/YankeeCandle.jpg";
	$giveaway_extra_right_img = '';
}
?>
<html>
<head>
<title>FitandFabLiving.com Giveaway - <?php echo $giveaway_title; ?></title>
<script language="JavaScript">
function check_fields() {
	if (document.getElementById('EMAIL').value == '') {
		alert ("* Please enter your email address.\n");
		return false;
	}
	if (document.getElementById('AGREE').checked == false) {
		alert ("* You must agree to terms and conditions.\n");
		return false;
	}
	return true;
}
</script>
<style>
* {
	font: 12px Arial, Helvetica, sans-serif;
	line-height: 1.25em; /* = 20px */
	color: #4e4e4e;
}
</style>
</head>
<body>
<table width="750px">
<tr>
	<td colspan="2">
		<h2 style="font-size:20px;height:27px;text-aling:left;padding-top:5px;"><?php echo $giveaway_title; ?></h2>
		<?php if($giveaway_top_img != ""){ ?><p><img src="<?php echo $giveaway_top_img; ?>"></p><?php }?>
		<p style="font: 12px Arial, Helvetica, sans-serif;width:645px;">
			<?php echo $giveaway_text; ?>
		</p>
		<p></p>
	</td>
</tr>
<tr>
	<td valign="top" align="left">
	<!-- form starts -->
			<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup{background:#E8E8E8; clear:left; font:14px Helvetica,Arial,sans-serif;  width:300px;}
				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
				#mc_embed_signup .asterisk {color:#c60; font-size:125%;}
			</style>
			<div id="mc_embed_signup">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<input type="hidden" name="guid" id="guid" value="<?php echo $guid; ?>">
			<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
			<div class="mc-field-group">
				<label for="mce-FNAME">First Name  
			</label>
				<input type="text" value="<?php echo $FNAME; ?>" name="FNAME" class="" id="FNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
			</label>
				<input type="email" value="<?php echo $EMAIL; ?>" name="EMAIL" id="EMAIL">
			</div>
			<div class="mc-field-group input-group">
			    <strong></strong>
			    <ul><li><input type="checkbox" value="1" name="AGREE" id="AGREE"><label for="AGREE">
			    I understand that by subscribing, I will also receive special offers from third party partners, and agree to Fit and Fab Living's  
					<a href="/2-uncategorised/504-terms-of-use" target="_blank">Terms of Use</a>, and <a href="/2-uncategorised/497-privacy-policy" target="_blank">Privacy Policy</a>.
			    </label></li>
			</ul>
			</div>
				<div id="mce-responses" class="clear">
					<div style="<?php echo $style; ?>"><?php echo $message;echo $pixel; ?><br><br></div>
				</div>	<div class="clear"><input type="submit" value="Enter to Win!" name="submit" id="mc-embedded-subscribe" class="button" onclick="return check_fields();"></div>
			</form>
			</div>
			<!--End mc_embed_signup-->
	<!-- form ends -->
	</td>
        <td align="left" valign="top">
            <?php if($giveaway_right_img!=''){ echo '<img src="$giveaway_right_img">'; }?>
            <?php if($giveaway_extra_right_img!=''){ echo $giveaway_extra_right_img; }?>
        </td>
</tr>
</table>
</body>
</html>
