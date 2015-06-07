<?php

if (isset($_GET['cid'])) {
	$linkid = addslashes(trim($_GET['cid']));
	include_once("config.php");
	
	// set defaults
	$delay = '0';	$opacity = '.10';	$width = '735';	$height = '465';	$listid = '410,411';	$subcampid = '3588';
	$month_year = strtolower(date('M_Y'));
	
	$result = mysql_query("SELECT * FROM links WHERE linkid=\"$linkid\" AND isActive='Y' LIMIT 1");
	$iCount = mysql_num_rows($result);
	while ($row = mysql_fetch_object($result)) {
		$opacity = $row->opacity / 100;
		$delay = $row->delay * 1000;
		$userControl = $row->usercontrol;
		$template = strtoupper($row->template);
		$source = trim(strtolower($row->source));
	}
	
	if ($iCount == 1) {
		$result = mysql_query("SELECT * FROM templates WHERE templateName=\"$template\" LIMIT 1");
		while ($row = mysql_fetch_object($result)) {
			$listid = $row->listid;
			$width = $row->width;
			$height = $row->height;
		}
		
		$result = mysql_query("SELECT subcampid FROM links_subcampid WHERE linkid=\"$linkid\" AND month_year=\"$month_year\" LIMIT 1");
		while ($row = mysql_fetch_object($result)) {
			$subcampid = $row->subcampid;
		}
		
		$querystring = "?listid=$listid&subcampid=$subcampid&linkid=$linkid&source=$source&usercontrol=N";	// change user control to N because we want to auto close upon successful signup
		
		
		?>
			<style>
			#fancybox-overlay { position:absolute;top:0;left:0;width:100%;z-index:1100;display:none; }
			#fancybox-wrap { position:absolute;z-index:1101;outline:none;display:none;position:absolute;top:230px !important; }
			#fancybox-outer { position:relative;width:100%;height:100%;background:#fff; }
			<?php if ($userControl == 'Y') { ?>
			#fancybox-close { position:absolute;top:-15px;right:-15px;width:30px;height:30px;cursor:pointer;z-index:1103;display:none;
				background:transparent url('http://pics.fitandfabliving.com/fancybox/fancy_close.png'); }
			<?php } ?>
			#fancybox-frame { width:100%;height:100%;border:none;display:block; }
			</style>
			<!--<script type="text/javascript" src="/dhtml/jquery.min.js"></script>-->
			<script type="text/javascript" src="/dhtml/jquery.fancybox-1.3.4.pack.js"></script>
			<script type="text/javascript" src="/dhtml/jquery.cookie.js"></script>
			<script type="text/javascript">var FFDhtml = jQuery.noConflict();</script>
			<script type="text/javascript">
			function callFancyBoxiFrame() {
				FFDhtml(document).ready(function() {
					FFDhtml.fancybox({
						'width'					: <?php echo $width; ?>,
						'height'				: <?php echo $height; ?>,
						'autoScale'				: false,
						'transitionIn'			: 'elastic',
						'transitionOut'			: 'elastic',
						'type'					: 'iframe',
						'scrolling'				: 'no',
						'padding'				: 0,
						'hideOnOverlayClick'	: false,
						'href'					: '<?php echo "/dhtml/$template.php".$querystring; ?>',
						'overlayColor'			: '#000',
						'overlayOpacity'		: <?php echo $opacity; ?>
					});
				});
			}
			window.setTimeout("callFancyBoxiFrame();", <?php echo $delay; ?>);
			</script>
		<?php
	}
}
?>