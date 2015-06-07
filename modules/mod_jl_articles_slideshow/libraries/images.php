<?php

/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license		GNU/GPL, see LICENSE
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
if(class_exists('JLImageHelper') != true){
	class JLImageHelper extends JObject {
		static function getImageCreateFunction($type) {
			switch ($type) {
				case 'jpeg':
				case 'jpg':
					$imageCreateFunc = 'imagecreatefromjpeg';
					break;

				case 'png':
					$imageCreateFunc = 'imagecreatefrompng';
					break;

				case 'bmp':
					$imageCreateFunc = 'imagecreatefrombmp';
					break;

				case 'gif':
					$imageCreateFunc = 'imagecreatefromgif';
					break;

				case 'vnd.wap.wbmp':
					$imageCreateFunc = 'imagecreatefromwbmp';
					break;

				case 'xbm':
					$imageCreateFunc = 'imagecreatefromxbm';
					break;

				default:
					$imageCreateFunc = 'imagecreatefromjpeg';
			}

			return $imageCreateFunc;
		}

		static function getImageSaveFunction($type) {
			switch ($type) {
				case 'jpeg':
					$imageSaveFunc = 'imagejpeg';
					break;

				case 'png':
					$imageSaveFunc = 'imagepng';
					break;

				case 'bmp':
					$imageSaveFunc = 'imagebmp';
					break;

				case 'gif':
					$imageSaveFunc = 'imagegif';
					break;

				case 'vnd.wap.wbmp':
					$imageSaveFunc = 'imagewbmp';
					break;

				case 'xbm':
					$imageSaveFunc = 'imagexbm';
					break;

				default:
					$imageSaveFunc = 'imagejpeg';
			}

			return $imageSaveFunc;
		}

		static function resize($imgSrc, $imgDest, $dWidth, $dHeight, $crop = true) {
			$info = getimagesize($imgSrc, $imageinfo);
			$sWidth = $info[0];
			$sHeight = $info[1];

			if ($sHeight / $sWidth > $dHeight / $dWidth) {
				$width = $sWidth;
				$height = round(($dHeight * $sWidth) / $dWidth);
				$sx = 0;
				$sy = round(($sHeight - $height) / 3);
			}
			else {
				$height = $sHeight;
				$width = round(($sHeight * $dWidth) / $dHeight);
				$sx = round(($sWidth - $width) / 2);
				$sy = 0;
			}

			if (!$crop) {
				$sx = 0;
				$sy = 0;
				$width = $sWidth;
				$height = $sHeight;
			}

			//echo "$sx:$sy:$width:$height";die();

			$imageCreateFunc = self::getImageCreateFunction(str_replace('image/', '', $info['mime']));
			$imageSaveFunc = self::getImageSaveFunction(JFile::getExt($imgDest));

			$sImage = $imageCreateFunc($imgSrc);
			$dImage = imagecreatetruecolor($dWidth, $dHeight);
			imagecopyresampled($dImage, $sImage, 0, 0, $sx, $sy, $dWidth, $dHeight, $width, $height);

			if( $imageSaveFunc=='imagepng' ){
				$scaleQuality = round((100/100) * 9);
				$invertScaleQuality = 9 - $scaleQuality;
				$imageSaveFunc($dImage, $imgDest, $invertScaleQuality);
			}else{
				$imageSaveFunc($dImage, $imgDest, 100);
			}
		}
		static function createImage($imgSrc, $imgDest, $width, $height, $crop = true) {
			if (JFile::exists($imgDest)) {
				$info = getimagesize($imgDest, $imageinfo);

				// Image is created
				if (($info[0] == $width) && ($info[1] == $height)) {
					return;
				}
			}
			if (JFile::exists($imgSrc)) {
				self::resize($imgSrc, $imgDest, $width, $height, $crop);
			}
		}
	}
}
?>
