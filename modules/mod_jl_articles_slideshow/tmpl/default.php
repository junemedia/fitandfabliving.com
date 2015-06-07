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
if( $params->get('enable_li_bg','0')==1 ){
	$jl_li_bg = $params->get('jl_li_bg','#F3F8FB');
}
if( isset($jl_li_bg) ){
	echo '<style>
			#jlass'.$module->id.' .jl-navigator li div.jlcontent{
				background:'.$jl_li_bg.'!important;
			}
	</style>';
}
if( isset($width_desc_on_main) && $width_desc_on_main ){
	echo '<style>
			#jlass'.$module->id.' .jl-description{
				width:'.$width_desc_on_main.'px!important;
			}
	</style>';
}
?>
<!------------------------------------- THE CONTENT ------------------------------------------------->
<div id="jlass<?php echo $module->id; ?>" class="jl-ass<?php echo $params->get('moduleclass_sfx','');?> " style="height:<?php echo $moduleHeight;?>; width:<?php echo $moduleWidth;?>">
<div class="jlass-container jl-css3 <?php echo $themeClass ;?> <?php echo $class;?>">
    
    <?php if( $params->get("preload",1) ){ ?>
    <div class="preload"><div></div></div>
    <?php } ?>
    <?php if(  $params->get( 'enable_playstop' , 1) ){ ?>
    <div class="jl-startstop"><div></div></div>
    <?php } ?>
     
     <!-- MAIN CONTENT --> 
      <div class="jl-main-wapper" style="height:<?php echo (int)$params->get('main_height',300);?>px;width:<?php echo (int)$params->get('main_width',661);?>px;">
      	
          <?php foreach( $list as $no => $row ){ ?>
            <div class="jl-main-item<?php echo(isset($customSliderClass[$no])? " ".$customSliderClass[$no]:"" );?>">
					<?php
						if( !$enableImageLink ) {
							echo '<img src="' . $row->realimage . '" alt="'.$row->title.'"/>';
						}else{
					?>
					<a target="_<?php echo $openTarget ;?>" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>">
						<img src="<?php echo $row->realimage; ?>" alt="<?php echo $row->title;?>"/>
					</a>
					<?php } ?>
				
                 
					 <?php if( $enableBlockdescription ){  ?>
					 <div class="jl-description">
						<h4>
							<a target="_<?php echo $openTarget ;?>" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>"><?php echo $row->title;?></a>
							<?php 
								if( $show_price && $jl_options=='virtuemart' ){
									echo '<span class="jl_price">'.$row->jl_product_price.'</span>';
								}
							?>
						</h4>
						<?php if( !empty($row->desc) ){ ?>
						<p><?php echo $row->desc;?></p>
						<?php } ?>
					 </div>
					 <?php } ?>
            </div> 
            <?php } ?>
        
      </div>
      <!-- END MAIN CONTENT --> 
      <!-- NAVIGATOR -->
      <?php if( $params->get('display_button',1) ){ ?>
                <div class="jl-buttons-control">
                  <a href="" onclick="return false;" class="jl-previous"><?php echo JText::_('Previous');?></a>
                  <a href="" class="jl-next"  onclick="return false;"><?php echo JText::_('Next');?></a>
                </div>
      <?php } ?>
        <?php if( $class ){ ?>
              <div class="jl-navigator-outer">
                    <ul class="jl-navigator">
                    <?php foreach( $list as $row ){ ?>
                        <li>
                            <div class="jlcontent">
								<div class="inner-jlcontent">
                                <?php if( $navEnableThumbnail ){ ?>
								 <a target="_<?php echo $openTarget ;?>" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>">
                                 <?php echo '<img src="' . $row->createdThumb . '" alt="'.$row->title.'"/>'; ?>
								 </a>
                                 <?php } ?>
                                 <?php if( $navEnableTitle ){ ?>
										
										<h4><a style="color:#000000;" target="_<?php echo $openTarget ;?>" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>"><?php echo $row->subtitle;?></a></h4>
										
                                 <?php } ?>
                                 <?php if( $navEnableDate ){ ?>
										<span><?php echo $row->date; ?></span>
                                 <?php } ?>
								<?php if( $enable_desc_on_navigation ){?>
										 <p><?php echo @$row->subdesc;?></p>
                                <?php } ?>
                                 <?php if( $navEnableCate ){?>
										 <p><i><?php echo JText::_("JL_ARTICLES_SLIDESHOW_PUBLISHED");?></i>
										<a href="<?php echo $row->catlink;?>"><i><?php echo $row->category_title;?></i></a></p>
                                <?php } ?>
								</div>
                            </div>
							<?php
								if( $enable_arrow ){
									if( $params->get( 'navigator_pos', 'right' )=='left' ){
										echo '<div class="nav-arrow-left"></div>';
									}else{
							?>
								<div class="nav-arrow"></div>
							<?php
									}
								}
							?>
                        </li>
                     <?php } ?>
                    </ul>
              </div>
       <?php } ?>
  </div>
  
 </div> 