<?php

//	unsub link in newsletter
//	http://www.fitandfabliving.com/unsubscribe.php?lid=583&jid=123&e=samirp@junemedia.com

$listid = $_REQUEST['lid'];
$jobid = $_REQUEST['jid'];
$email = $_REQUEST['e'];

$url = "http://www.fitandfabliving.com/unsubscribe2/";
$query = "lid=$listid&jid=$jobid";

header("Location:$url?$query");
exit;


?>
