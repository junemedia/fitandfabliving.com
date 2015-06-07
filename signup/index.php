<?php

$message = "";
$email = "";
if (isset($_POST['submit']) && $_POST['submit'] == 'Go') {
	$email = addslashes(trim($_POST['email']));
	
	if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $email)) { $message = "<script>alert('Invalid email address!');</script>"; }
	
	list($prefix, $domain) = split("@",$email);
	if (!getmxrr($domain, $mxhosts)) { $message = "<script>alert('Invalid email address!');</script>"; }
	
	include_once("functions.php");
	if (LookupImpressionWise($email) == false) { $message = "<script>alert('Invalid email address!');</script>"; }
	if (BullseyeBriteVerifyCheck($email) == false) { $message = "<script>alert('Invalid email address!');</script>"; }
	
	if ($message == '') {
		// email is good, process
		$ipaddr = trim($_SERVER['REMOTE_ADDR']);
		
		$sPostingUrl = "http://fitfab.popularliving.com/fitfab_api.php?email=$email&sublists=411,410,448&subcampid=3436&ipaddr=$ipaddr&keycode=kfdj49358gkj359gjk55";
		$response = strtolower(file_get_contents($sPostingUrl));
		
		$pixel = "<!-- Google Tag Manager -->
				<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=GTM-WRKVZZ\"
				height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-WRKVZZ');
				dataLayer.push({'event': 'formsubscribefitandfabliving'});</script>
				<!-- End Google Tag Manager -->";
		
		$message = "<script>alert('Thank you for signing up!');</script>".$pixel;
		$email = '';
	} else {
		// email is bad, give error
	}
}

?>
<html>
<head>
<title>Signup for Newsletters</title>
<style>
* { margin:0px; padding:0px;color:#060606;font-weight:bold;font-size:12px;font-face:Verdana; }
body {/*background-image:url('/templates/protostar/images/subscribe.jpg');background-repeat:no-repeat;*/}
.subscribe_box{
background:url('/templates/protostar/images/FFsubscribe-banner.png') no-repeat;
clear: both;
    float: left;
    height: 50px;
    padding-top: 18px;
    width: 638px;
}
.subscribe_box {
font-family:Arial,"Helvetica Neue",Helvetica,sans-serif;
}
.subscribe_box a{
	color:#E4057C;
}
</style>
<script>
function check_fields() {
	var email = document.getElementById('email').value;
	var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
	var chkFlag = pattern.test(email);
	if(!pattern.test(email)) {
		alert("Please enter a valid e-mail address!");
		document.getElementById('email').focus();
		return false;
	}
}
window.scroll(0,0); // horizontal and vertical scroll targets
</script>
</head>
<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="form1" name="form1" method="POST">
<div class="subscribe_box">
<input type="submit" name="submit" value="Go" style="background-color: rgb(228, 0, 121); width: 30px; height: 30px; color: white; font-size: 14px; float: right; margin-right: 15px;" onclick="return check_fields();">
<input type="text" maxlength="100" size="30" name="email" id="email" value="<?php echo $email; ?>" style="float: right; height: 30px; margin-right: 3px; width: 162px;" onfocus="if(this.value=='Your Email Address')this.value=''" onblur="if(this.value=='')this.value='Your Email Address'">
</div>
</form>
<?php echo $message; ?>
<script>
if (document.getElementById('email').value == '') { document.getElementById('email').value='Your Email Address'; }
</script>
</body>
</html>
