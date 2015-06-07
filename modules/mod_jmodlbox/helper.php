<?php
/* @package Jmodules LikeBox for Joomla 3.0!  
 * @link       http://jmodules.com/ 
 * @copyright (C) 2012- Sean Casco
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html 
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class jmodlbox{
	public static function getinput(&$params ){		
		$link_to_page	= $params->get('link_to_page', 'http://www.facebook.com/joomla');
		$boxcolor	= $params->get('boxcolor');
		$include_stream	= $params->get('include_stream');
		$fbheader	= $params->get('fbheader');
		$boxwidth	= $params->get('boxwidth', '300');
		$boxheight	= $params->get('boxheight', '550');
		$fbborder	= $params->get('fbborder', '000000');		
		$fbfans	= $params->get('fbfans');	
		
		if($boxcolor == '1')
		{ 	$boxcolor	= 'light';	}
		else				
		{	$boxcolor	= 'dark';	}
		
		if($fbfans == '1')
		{	$fbfans	= 'true';		}
		else
		{	$fbfans	= 'false';		}
			
		if($include_stream == '1'){
			$include_stream	= 'true';
		}else{
			$include_stream	= 'false';
		}
		
		if($fbheader == '1'){
			$fbheader	= 'true';
		}else{
			$fbheader	= 'false';
		}
				
		$jmodlpage	="http://www.facebook.com/plugins/likebox.php?href=".$link_to_page."&amp;width=".$boxwidth .
					"&amp;colorscheme=".$boxcolor."&amp;show_faces=".$fbfans .
					"&amp;border_color=%23".$fbborder."&amp;stream=".$include_stream."&amp;header=".$fbheader."&amp;height=".$boxheight;

		$input ='';
		$input = '<iframe src="'.$jmodlpage.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$boxwidth.'px; height:'.$boxheight.'px;"></iframe>';
		return $input;
	}
}
