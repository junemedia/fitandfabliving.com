<?php

include_once("config.php");

$message = '';
$error = '';
$signup_success = false;
$today = date('Y-m-d');
$new_email = true;
$error_type='';

if ($submit == 'Submit') {
	if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $email)) {
		$error = "Invalid Email Address";
		$error_type='email_format_error';
	} else {
		// Check DNS records corresponding to a given domain
		// Get MX records corresponding to a given domain.
		list($prefix, $domain) = split("@",$email);
		if (!getmxrr($domain, $mxhosts)) {
			$error = "Invalid Email Address";
			$error_type='mx_error';
		} else {
			if ($error == '') {
				if (LookupImpressionWise($email) == false) {
					$error = "Invalid Email Address";
					$error_type='impressionwise_error';
				}
			}
			if ($error == '') {
				if (BullseyeBriteVerifyCheck($email) == false) {
					$error = 'Invalid Email Address';
					$error_type='briteverify_error';
				}
			}
		}
	}
	
	if ($error != '') {
		$message = $error;
		$signup_success = false;
		$saveResult = saveReportDetails($linkid,$subcampid,$error_type,$email,$message);
	} else {
		$signup_success = true;
		setcookie("EMAIL_ID", $email, time()+642816000, "/", ".fitandfabliving.com");

		$posting_url = "http://fitfab.popularliving.com/ff_flow.php?email=$email&ipaddr=".trim($_SERVER['REMOTE_ADDR'])."&keycode=gjhk5487gdfhkjg9438&sublists=$listid&subcampid=$subcampid&subsource=$linkid";
		$response = file_get_contents($posting_url);
		
		$message = "Thank you for signing up!"."";
		
		//Only for a brand new email address that we have never seen do we fire the call to google analytics
		if (strstr($response, 'is_newemail:true')) {
			$result = mysql_query("SELECT * FROM report WHERE dateAdded = \"$today\" AND linkid = \"$linkid\"");
			if (mysql_num_rows($result) == 0) {
				$result = mysql_query("INSERT IGNORE INTO report (dateAdded,linkid,signup) VALUES (\"$today\",\"$linkid\",\"1\")");
			} else {
				$result = mysql_query("UPDATE report SET signup=signup+1 WHERE dateAdded = \"$today\" AND linkid = \"$linkid\"");
			}
			$saveResult = saveReportDetails($linkid,$subcampid,'signup',$email,$response);
		}else
		{
			$new_email = false;
			$saveResult = saveReportDetails($linkid,$subcampid,'signup_exist',$email,$response);
		}
		$email = '';
	}
} else {
	$result = mysql_query("SELECT * FROM report WHERE dateAdded = \"$today\" AND linkid = \"$linkid\"");
	if (mysql_num_rows($result) == 0) {
		$result = mysql_query("INSERT IGNORE INTO report (dateAdded,linkid,display) VALUES (\"$today\",\"$linkid\",\"1\")");
	} else {
		$result = mysql_query("UPDATE report SET display=display+1 WHERE dateAdded = \"$today\" AND linkid = \"$linkid\"");
	}
	$saveResult = saveReportDetails($linkid,$subcampid,'display');
}

function saveReportDetails($linkid,$subcampid,$actionType,$email=false,$severResponse='')
{
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$today = date('Y-m-d H:m:s');
	
	$result = mysql_query('INSERT INTO report_details (linkid,subcampid,actionType,email,dateAdded,serverResponse,ipaddress) VALUES ("'.$linkid.'",'.$subcampid.',"'.$actionType.'", "'.$email.'", "'.$today.'","'.$severResponse.'","'.$ipaddress.'")');
	return $result;
}
?>
<html>
<head>
<title></title>
<script language="JavaScript">
function closethis() {
	parent.FFDhtml.fancybox.close();
}
function check_fields() {
	document.form1.email.style.backgroundColor="";
	var str = '';
	var response = '';
	
	var email = document.form1.email.value;
	var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
	var chkFlag = pattern.test(email);
	if(!pattern.test(email)) {
		str += "Please enter valid email address.";
		document.form1.email.style.backgroundColor="#ED008C";
	}
	
	if (str == '') {
		return true;
	} else {
		alert (str);
		return false;
	}
}
</script>
<style type="text/css">
#divBG {
background-image:url('http://pics.fitandfabliving.com/dhtml/ExclusiveContent.jpg');
background-repeat: no-repeat;
border:0px;
font-size:11px;
font-family: verdana;
height: 450px;
width: 720px;
position: relative;
}
#emailRow {
position: absolute;
top: 330px;
left: 150px;
font-family: arial,helvetica;
font-size:16px;
color:#004AB2;
font-weight:bold;
}
#emailConf {
position: absolute;
top: 310px;
left: 220px;
font-family: arial,helvetica;
font-size:14px;
color:red;
}
</style>
</head>
<body>
<form name="form1" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return check_fields();">
<input type="hidden" name="usercontrol" value="<?php echo $usercontrol; ?>">
<input type="hidden" name="submit" value="Submit">
<input type="hidden" name="listid" value="<?php echo $listid; ?>">
<input type="hidden" name="subcampid" value="<?php echo $subcampid; ?>">
<input type="hidden" name="linkid" value="<?php echo $linkid; ?>">
<input type="hidden" name="source" value="<?php echo $source; ?>">
<div id="divBG">
	<div id="emailConf"><b><?php echo $message; ?></b></div>
	<div id="emailRow">
		<input style="background-color: white;height:43px;font-size:20px;" type="text" name="email" id="email" value="<?php echo $email; ?>" size="25" maxlength="100" onfocus="if(this.value=='enter email address')this.value='';" onblur="if(this.value=='')this.value='enter email address';">
		&nbsp;&nbsp;&nbsp;
		<INPUT style="vertical-align:bottom;" TYPE="image" SRC="http://pics.fitandfabliving.com/dhtml/FFL-signupbutton.png" BORDER="0" ALT="Submit Form" />
	</div>
</div>
</form>
<script>
if (document.getElementById('email').value == '') { document.getElementById('email').value='enter email address'; }
</script>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10900002-1");
pageTracker._trackPageview();
} catch(err) {}
</script>
<!-- F&F PPC Analytics Code -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-47125932-1', 'fitandfabliving.com');
  ga('send', 'pageview');
</script>
<?php
if ($signup_success == true && strtolower($source) == 'google'&& $new_email==true) {
	// FIRE GOOGLE PIXEL HERE UPON SUCCESS SIGNUP
	echo '';
}
if ($signup_success == true && $usercontrol == 'N'&& $new_email==true) {
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
	echo "<script>window.setTimeout('closethis();', 2000);</script>";
}
if($new_email==false)
{
	echo "<script>window.setTimeout('closethis();', 2000);</script>";
}
?>
</body>
</html>
