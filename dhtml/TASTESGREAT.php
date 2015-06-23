<?php

include_once("functions.php");

$subcampid = '3826';
$listid = '411';
$message = '';
$new_email = true;
if ($_POST['submit'] == 'Y') {
	$email = trim($_POST['email']);
	
	// process sign up request...
	$user_ip = trim($_SERVER['REMOTE_ADDR']);
		
		
	$sPostingUrl = "http://fitfab.popularliving.com/ff_api.php?email=$email&sublists=$listid&subcampid=$subcampid&ipaddr=$user_ip&keycode=ggjig592fkg785kscm8473&source=FFSqueeze";
	$response = strtolower(file_get_contents($sPostingUrl));
	
	//Only for a brand new email address that we have never seen do we fire the call to google analytics
	if (strstr($response, 'is_newemail:true')) {
	}else
	{
		$new_email = false;
	}

	$message = 'success';
	setcookie("EMAIL_ID", $email, time()+642816000, "/", ".savvyfork.com");
	$plant_cookie = "";
	$email = '';
	$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=s&f=FFSqueeze$subcampid' width='0' height='0' border='0' />";
} else {
	$email = trim($_GET['email']);
	
	if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $email)) { $email = ''; }
	list($prefix, $domain) = split("@",$email);
	if (!getmxrr($domain, $mxhosts)) { $email = ''; }
	
	$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=d&f=FFSqueeze$subcampid' width='0' height='0' border='0' />";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title>Mailing List Sign Up System</title>
		<style>
		body {text-align:left;margin-left:auto;margin-right:auto;}
		* {margin:0; padding:0; font:10px Helvetica,sans-serif; color:#333; border:none;width:300px;}
		input {padding:.1em; width:142px; font-size:1.3em;border:none;}
		/*#response {color:yellow; font-style:italic; font-size:12px;width:300px;border:none;}*/
		</style>
	</head>
	<body style="background-image:url('http://pics.fitandfabliving.com/dhtml/FF_squeezepage_tastesgreat.png');background-repeat:no-repeat;padding-top:130px;">
		 <form id="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding-left:25px;">
	 		<input type="hidden" name="email" id="email" value="<?php echo $email; ?>">
	 		<input type="hidden" name="submit" id="submit" value="Y">
			<INPUT style="position:relative;left:250px;top:160px;width:124px;height:50px;border:none;" TYPE="image" SRC="http://pics.fitandfabliving.com/dhtml/FF_squeezepage_signup.png" BORDER="0" ALT="Submit Form" />
		</form>
		<span id="response">
		<?php
			echo $pixel;
			if (strstr($message,'success')) {
				echo $plant_cookie;
				if($new_email==true){
				echo "<!-- Google Tag Manager -->
<noscript><iframe src='//www.googletagmanager.com/ns.html?id=GTM-WRKVZZ'
height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WRKVZZ');
dataLayer.push({'event': 'squeezepagesubscribefitandfabliving'});</script>
<!-- End Google Tag Manager -->";
				echo "<script>setTimeout(function(){parent.closethis();},2000);</script>";
				}
			}
			
			if($new_email==false)
			{
				echo "<script>setTimeout(function(){parent.closethis();},2000);</script>";
			}
		?>
		</span>
	</body>
</html>
