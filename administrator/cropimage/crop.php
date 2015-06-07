<?php 
error_reporting (E_ALL ^ E_NOTICE);
$access = $_SERVER['QUERY_STRING'];

if($access == '')
{
	exit;
}
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', $_SERVER[ 'DOCUMENT_ROOT' ] );
require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once( JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php' );
$mainframe =& JFactory::getApplication('site');
JClientHelper::setCredentialsFromRequest('ftp');
JPluginHelper::importPlugin('content');
$dispatcher	= JEventDispatcher::getInstance();

$baseurl = JURI::root();

$user = JFactory::getUser(); 

$db = JFactory::getDBO ();
$large_image_location = $thumb_image_location = '';

$imagePath = JRequest::getString('validate','');
if($imagePath == '')
{
	echo "Please select image first!";exit;
}
$filePath = dirname($imagePath);
$filename = basename($imagePath);
$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
$filecropname = basename($filename,'.'.$file_ext).'_300X300.'.$file_ext;
$formAction = $_SERVER["PHP_SELF"].'?validate='.$imagePath;
if($from != 'preview' && $imagePath != ''){
	$large_image_location = JPATH_ROOT.DS.$imagePath;
	$thumb_image_location = JPATH_ROOT.DS.$filePath.DS.$filecropname;	
}

/*print_r("large_image_location: ".$large_image_location);
print_r("<br>");
print_r("thumb_image_location: ".$thumb_image_location);
print_r("<br>");
print_r("imagePath: ".$imagePath);
print_r("<br>");
print_r("formAction: ".$formAction);
print_r("<br>");
print_r("filePath: ".$filePath);
print_r("<br>");
print_r("fileName: ".$filename);
print_r("<br>");
print_r("file_ext: ".$file_ext);
print_r("<br>");*/

#########################################################################################################
# CONSTANTS																								#
# You can alter the options below																		#
#########################################################################################################
$upload_dir = "upload"; 				// The directory for the images to be saved in
$upload_path = $upload_dir."/";				// The path to where the image will be saved
$max_file = "2"; 							// Maximum file size in MB
$max_width = "1024";							// Max width allowed for the large image
$crop_width = "300";						// Width of crop image
$crop_height = "300";						// Height of crop image
// Only one of these image types should be allowed for upload
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$image_ext = "";	// initialise variable, do not change this.
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}

//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

//Check to see if any images with the same name already exist
if (file_exists($large_image_location)){
	if(file_exists($thumb_image_location)){
		$thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Thumbnail Image\"/>";
	}else{
		$thumb_photo_exists = "";
	}
   	$large_photo_exists = "<img src=\"".$upload_path.$large_image_name.$_SESSION['user_file_ext']."\" alt=\"Large Image\"/>";
} else {
   	$large_photo_exists = "";
	$thumb_photo_exists = "";
}
$iscropped=false;
if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	//Scale the image to the crop_width set above
	$scale = $crop_width/$w;
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
	//Reload the page again to view the thumbnail
	$iscropped = true;
	//echo "Image cropped successfully.";
	//$mainframe->enqueueMessage('Recipe image successfully saved.', 'green');
	//$mainframe->redirect('http://'.$_SERVER['HTTP_HOST'].'/'.$return_url);

	//exit();
} ?>
<html>
<head>
	<link href="<?php echo $baseurl;?>css/jWindowCrop.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="<?php echo $baseurl;?>css/custom.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="<?php echo $baseurl;?>js/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery.jWindowCrop.js"></script>
	<script src="<?php echo $baseurl;?>js/ajaxfileupload.js" type="text/javascript"></script>
	<script src="<?php echo $baseurl;?>js/crop.js" type="text/javascript"></script>
</head>
<body>
<?php 
if($iscropped)
{
?>
<script>
window.parent.SqueezeBox.close();
</script>
<?php }?>
<div id="crop_zone" style="text-align:center;margin:0 auto;width:410px;height:440px;">
<div align="center" id="crop_content">
	<div class="crop_overlay">
		<div class="top_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="left_overlay"></div>
		<div class="right_overlay"></div>
	<img id="target3" class="crop_me" alt="" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$imagePath.'?v='.time();?>" />
	</div>
</div>

	<form name="thumbnail" action="<?php echo $formAction;?>" method="post"  class="submit_from">
			<input type="hidden" name="x1" value="" id="x1" />
			<input type="hidden" name="y1" value="" id="y1" />
			<input type="hidden" name="w" value="" id="w" />
			<input type="hidden" name="h" value="" id="h" />
			<input type="submit" name="upload_thumbnail" value="Save Picture" id="save_thumb" />
			
	</form>
	
	<iframe name="submitframe" style="display: none" id="iframe"></iframe>

</div>
<?php //} ?>

	

</body>
</html>