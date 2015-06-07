<?php
error_reporting (E_ALL ^ E_NOTICE);
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', $_SERVER[ 'DOCUMENT_ROOT' ] );
require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once( JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php' );

$error = "";
$msg = "";
$imageN = JRequest::getVar('imageN','');

$fileElementName = 'imageupload'.$imageN;
$recipeImage = JRequest::getString('recipe','');
$baseDir = dirname(JPATH_ROOT.DS.$recipeImage);
if (!file_exists($baseDir))
{
	jimport('joomla.filesystem.folder');
	JFolder::create($baseDir);
}

$file = JRequest::getVar($fileElementName, null, 'files', 'array');

if(!empty($file['error']))
{
	switch($file['error'])
	{

		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;

		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}elseif(empty($file['tmp_name']) || $file['tmp_name'] == 'none')
{
	$error = 'No file was uploaded..';
}else 
{
		$filename = JFile::makeSafe($file['name']);

		if($file['size'] > $max) $msg = JText::_('ONLY_FILES_UNDER').' '.$max;
		//Set up the source and destination of the file
		$uploadedFileNameParts = explode('.',$filename);
		$uploadedFileExtension = array_pop($uploadedFileNameParts);
		$src = $file['tmp_name'];
		//$filename = sha1($userId.uniqid()).'.'.$uploadedFileExtension;
		
		$validFileExts = explode(',', 'jpeg,jpg,png,gif');

		$dest = $recipeImage;

		$result = false;
		//First check if the file has the right extension, we need jpg only
		if (in_array($uploadedFileExtension,$validFileExts)) {
		   if ( $dest !='' && is_writeable($baseDir) && move_uploaded_file($src, JPATH_ROOT.DS.$recipeImage) ) {                   		
				$result = JFile::compress_image($dest,$dest,90);
				$msg = "Recipe image successfully updated.";
		   } else {
				  //Redirect and throw an error message
				$msg = "Recipe image update failed.";	
		   }
		} else {
		   //Redirect and notify user file is not right extension
				$msg = JText::_('FILE_TYPE_INVALID');
		}
}
//for security reason, we force to remove all uploaded file
@unlink($_FILES['recipeimage']);		
		
echo "{";
echo				"error: '" . $error . "',\n";
echo				"msg: '" . $msg . "'\n";
echo "}";
?>