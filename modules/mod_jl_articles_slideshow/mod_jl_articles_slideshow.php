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
if( !defined('DS') ){
	define('DS', DIRECTORY_SEPARATOR);
} 
// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'libraries'.DS.'images.php');
$GLOBALS['module'] = $module;
// get options : default content.
$jl_options = $params->get('jl_options','content');
if( empty($jl_options) ){
	return;
}
require_once (dirname(__FILE__).DS.'helper'.DS.$jl_options.'.php');
$className	= 'modJLArticlesSlideshow'.ucfirst($jl_options);
$class		= new $className();
$moduleId	= $module->id;
$list		= $class->getRows($params);
if( empty($list) ){
	return;
}
// check direction ltr
$direction				= JRequest::getVar('direction','1');
if( $direction=='ltr' && $params->get( 'navigator_pos')!='0' ){
	$params->set( 'navigator_pos','left');
}
$style					= JRequest::getVar('style','0');

$tmp					= $params->get( 'module_height', 'auto' );
$moduleHeight			= ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
$tmp					= $params->get( 'module_width', 'auto' );
$moduleWidth			= ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
$themeClass				= $params->get( 'theme' , '');

// overid theme
if( $style && empty($themeClass) ){
	$themeClass = $style;
}

$openTarget				= $params->get( 'open_target', 'parent' );
$class					= $params->get( 'navigator_pos', 'right' ) == "0" ? '':'jl-sn'.$params->get( 'navigator_pos', 'right' );

$enableBlockdescription = $params->get( 'enable_blockdescription' , 1 );
$enableImageLink		= $params->get( 'enable_image_link' , 0);
 

// navigator setting 
$navEnableThumbnail			= $params->get( 'enable_thumbnail', 1 );
$navEnableTitle				= $params->get( 'enable_navtitle', 1 );
$navEnableDate				= $params->get( 'enable_navdate', 1 );
$navEnableCate				= $params->get( 'enable_navcate', 1 );
$customSliderClass			= $params->get('custom_slider_class','');
$customSliderClass			= is_array($customSliderClass)?$customSliderClass:array($customSliderClass);
$enable_arrow				= $params->get( 'enable_arrow', 1 );
$enable_desc_on_navigation	= $params->get( 'enable_desc_on_navigation', 1 );
$show_price					= $params->get( 'show_price', '1' );
$width_desc_on_main         = (float)$params->get('width_desc_on_main','400');

$document = &JFactory::getDocument();
$document->addScript( JURI::root(true). '/modules/'.$module->module.'/assets/jlscript.js' );
$document->addStyleSheet( JURI::root(true). '/modules/'.$module->module.'/assets/jlstyle.css' );

require( JModuleHelper::getLayoutPath($module->module) );

?>
<script type="text/javascript">

  var _jlmain =  $('jlass<?php echo $module->id; ?>'); 
   var object = new JLArticleSlideshow( _jlmain,
                  { 
                    fxObject:{
					 transition:<?php echo $params->get( 'effect', 'Sine.easeInOut' );?>,
						duration:<?php echo (int)$params->get('duration', '700')?>
                    },
                    startItem:<?php echo (int)$params->get('start_item',0);?>,
                    interval:<?php echo (int)$params->get('interval', '3000'); ?>,
                    direction :'<?php echo $params->get('layout_style','opacity');?>', 
                    navItemHeight:<?php echo $params->get('navitem_height', 88) ?>,
                    navItemWidth:<?php echo $params->get('navitem_width', 300) ?>,
                    navItemsDisplay:<?php echo $params->get('max_items_display', 4) ?>,
                    navPos:'<?php echo $params->get( 'navigator_pos', 0 ); ?>',
					autoStart:<?php echo (int)$params->get('auto_start',1)?>,
					descOpacity:<?php echo (float)$params->get('desc_opacity',1); ?>
                  } );
  <?php if( $params->get('display_button', '') ): ?>
    object.registerButtonsControl( 'click', {next:_jlmain.getElement('.jl-next'),
                         previous:_jlmain.getElement('.jl-previous')} );
  <?php endif; ?>
  $$('.jl-previous,.jl-next,.jl-startstop').setStyle('opacity','0.5');
  $$('.jl-previous,.jl-next,.jl-startstop').addEvent('mouseenter', function() {
      this.setStyle('opacity','1');
  });
  $$('.jl-previous,.jl-next,.jl-startstop').addEvent('mouseleave', function() {
      this.setStyle('opacity','0.5');
  });
 
</script>

