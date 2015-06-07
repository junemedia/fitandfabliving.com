<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Gallery Module 1.0.0
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe_gallery/styles/mod_yoorecipe_gallery'.$params->get('moduleclass_sfx').'.css');
$document->addStyleSheet('media/mod_yoorecipe_gallery/styles/_web.css');
$document->addStyleSheet('media/mod_yoorecipe_gallery/styles/style.css');
$document->addScript('media/mod_yoorecipe_gallery/js/_class.noobSlide.packed.js');

// Parameters
$layout			= $params->get('layout', 'player');
$thumbSize 		= $params->get('thumbnail_size', 240);
$autoplay		= $params->get('autoplay', 1);
$interval		= $params->get('interval', 5000);
$duration		= $params->get('duration', 1000);
//$transition		= $params->get('transition');
$slider_width	= $params->get('slider_width', 240);
$exerpt_width	= $params->get('exerpt_width', 240);
$use_watermark	= $params->get('use_watermark', 1);

require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php';
require_once JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php';
require_once JPATH_SITE.'/components/com_yoorecipe/router.php';

// Variables
$nbItems 	= count($items);
$csvItems 	= array();
$sampleObjectItems = array();

foreach ($items as $key=>$item) :
	$csvItems[] = $key;
	$sampleObjectItems[] = '{title:' . json_encode($item->shortTitle) . ", link:'" .JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug) . "'}";
endforeach;

$scriptDeclaration = "";
if ($layout == 'player3') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var nS1 = new noobSlide({
				box: $('box1'),
				items: [" . implode(",", $csvItems) . "],
				size: " . $slider_width . ",
				autoPlay: " . $autoplay . "
			});
		});";
}
else if ($layout == 'player') {
	
	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var nS2 = new noobSlide({
				box: $('box2'),
				items: [" . implode(",", $csvItems) . "],
				interval: " . $interval . ",
				autoPlay: " . $autoplay . ",
				fxOptions: {
					duration: " . $duration . ",
					transition: Fx.Transitions.Bounce.easeOut,
					wait: false
				}";
		if ($params->get('show_player', 1)) {		
			$scriptDeclaration .= ",
				addButtons: {
					previous: $('prev1'),
					play: $('play1'),
					stop: $('stop1'),
					next: $('next1')
				}";
		}
		$scriptDeclaration .= "
			});
		});";
}
else if ($layout == 'player2') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var nS3 = new noobSlide({
				box: $('box3'),
				items: [" . implode(",", $csvItems) . "],
				interval: " . $interval . ",
				startItem: 0,
				autoPlay: " . $autoplay;

		if ($params->get('show_player', 1)) {		
			$scriptDeclaration .= ",
				
				addButtons: {
					playback: $('playback3'),
					stop: $('stop3'),
					play: $('play3')
				}";
		}
		$scriptDeclaration .= "
			});
		});";
}
else if ($layout == 'exerpt') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var nS4 = new noobSlide({
				box: $('box4'),
				items: $$('#box4 div'),
				size: " . $exerpt_width . ",
				autoPlay: " . $autoplay . ",
				handles: $$('#handles4 span'),
				onWalk: function(currentItem,currentHandle){
					$('info4').set('html',currentItem.getFirst().innerHTML);
					this.handles.removeClass('active');
					currentHandle.addClass('active');
				}
			});
		});";
}
else if ($layout == 'legendplayer') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var info5 = $('info5').set('opacity',0.5);
			var sampleObjectItems =[" . implode(",", $sampleObjectItems) . "];
			var nS5 = new noobSlide({
				mode: 'vertical',
				box: $('box5'),
				size: 180,
				autoPlay: " . $autoplay . ",
				items: sampleObjectItems,";
				
		if ($params->get('show_player', 1)) {		
			$scriptDeclaration .= "
				addButtons: {
					previous: $('prev5'),
					play: $('play5'),
					stop: $('stop5'),
					next: $('next5')
				},";
		}
		$scriptDeclaration .= "
				onWalk: function(currentItem){
					info5.empty();
					new Element('h4').set('html','<a href=\"'+currentItem.link+'\">".JText::_('MOD_YOORECIPE_GALLERY_LINK')."</a>'+currentItem.title).inject(info5);
				}
			});
		});";
}
else if ($layout == 'vslide') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var info6 = $('box6').getNext().set('opacity',0.5);
			var sampleObjectItems =[" . implode(",", $sampleObjectItems) . "];
			var nS6 = new noobSlide({
				mode: 'vertical',
				box: $('box6'),
				items: sampleObjectItems,
				size: 180,
				autoPlay: " . $autoplay . ",
				handles: $$('#handles6_1 div').extend($$('#handles6_2 div')),
				handle_event: 'mouseenter',
				addButtons: {
					previous: $('prev6'),
					play: $('play6'),
					stop: $('stop6'),
					playback: $('playback6'),
					next: $('next6')
				},
				button_event: 'click',
				fxOptions: {
					duration: " . $duration . ",
					transition: Fx.Transitions.Back.easeOut,
					wait: false
				},
				onWalk: function(currentItem,currentHandle){
					info6.empty();
					new Element('h4').set('html','<a href=\"'+currentItem.link+'\">".JText::_('MOD_YOORECIPE_GALLERY_LINK')."</a>'+currentItem.title).inject(info6);
					this.handles.set('opacity',0.3);
					currentHandle.set('opacity',1);
				}
			});
			//walk to next item
			nS6.next();
		});";
}
else if ($layout == 'hslide_thumbs') {

	$scriptDeclaration = "
		window.addEvent('domready',function(){
			var startItem = 0;
			var thumbs_mask7 = $('thumbs_mask7').setStyle('left',(startItem*60-568)+'px').set('opacity',0.8);
			var fxOptions7 = {property:'left',duration:" . $duration . ", transition:Fx.Transitions.Back.easeOut, wait:false}
			var thumbsFx = new Fx.Tween(thumbs_mask7,fxOptions7);
			var nS7 = new noobSlide({
				box: $('box7'),
				items: [" . implode(",", $csvItems) . "],
				handles: $$('#thumbs_handles7 span'),
				autoPlay: " . $autoplay . ",
				fxOptions: fxOptions7,
				onWalk: function(currentItem){
					thumbsFx.start(currentItem*60-568);
				},
				startItem: startItem
			});
			//walk to first with fx
			nS7.walk(0);
		});";
}
else if ($layout == 'exerpt2') {
	
	$scriptDeclaration = "
		window.addEvent('domready',function(){
			//var handles8_more = $$('#handles8_more span');
			var nS8 = new noobSlide({
				box: $('box8'),
				items: $$('#box8 h3'),
				size: " . $exerpt_width . ",
				handles: $$('#handles8 span'),
				autoPlay: " . $autoplay . ",";
		if ($params->get('show_player', 1) || $params->get('show_navigation', 1) ) {
		
			$btnParams;
			if ($params->get('show_player', 1)) {
				$btnParams[] = "play: $('play8'), stop: $('stop8'), playback: $('playback8')";
			}
			if ($params->get('show_navigation', 1)) {
				$btnParams[] = "previous: $('prev8'), next: $('next8')";
			}
			$scriptDeclaration .= "
				addButtons: {" . implode(',', $btnParams) . "}, ";
		}
	$scriptDeclaration .= "
				onWalk: function(currentItem,currentHandle){
					//style for handles
					//$$(this.handles,handles8_more).removeClass('active');
					//$$(currentHandle,handles8_more[this.currentIndex]).addClass('active');";

	if ($params->get('show_navigation', 1)) :				
		$scriptDeclaration .= "		
					$('prev8').set('html','&lt;&lt; '+this.items[this.previousIndex].innerHTML);
					$('next8').set('html',this.items[this.nextIndex].innerHTML+' &gt;&gt;');
				";
	endif;

	$scriptDeclaration .=					
				"
			}
		});";
		
	if ($params->get('show_navigation', 1)) :	
	
		$scriptDeclaration .=	"//more \"previous\" and \"next\" buttons
		nS8.addActionButtons('previous',$$('#box8 .prev'));
		nS8.addActionButtons('next',$$('#box8 .next'));";
	endif;
		$scriptDeclaration .= "//more handle buttons
		//nS8.addHandleButtons(handles8_more);
		//walk to item 3 witouth fx
		nS8.walk(3,false,true);
		});";
}

$document->addScriptDeclaration($scriptDeclaration);
JHtmlBehavior::framework();

?><div id="cont">
<?php
	if (strlen($params->get('intro_text')) > 0) :
?>
<div class="description">
	<p><?php echo $params->get('intro_text'); ?></p>
</div>
<?php
	endif;
?>

<?php
	if (count($items) > 0) {

	if ($layout == 'player3') { ?>
<!-- BASIC HORIZONTAL SLIDING WITH 2 THUMBNAILS -->
<div class="sample">
	<div class="mask1" style="width:<?php echo $slider_width; ?>px;height:<?php echo $slider_width; ?>px">
		<div id="box1">
<?php	foreach ($items as $item) : ?>
			<span class="gallery">
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" alt="<?php echo htmlspecialchars($item->title); ?>" title="<?php echo htmlspecialchars($item->title); ?>" />
				</a>
			</span>
<?php 	endforeach; ?>
		</div>
	</div>
</div>
<?php 
	} 
	else if ($layout == 'player') {
?>
<!-- BASIC HORIZONTAL SLIDESHOW -->
<div class="sample">
	<div class="mask2">
		<div id="box2">
<?php	foreach ($items as $item) : ?>
			<span class="gallery">
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" alt="<?php echo htmlspecialchars($item->title); ?>" title="<?php echo htmlspecialchars($item->title); ?>"/>
				</a>
			</span>
<?php	endforeach; ?>
		</div>
	</div>
	<?php if ($params->get('show_player', 1)) : ?>
	<p class="buttons">
		<span id="prev1">&lt;&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_PREVIOUS'); ?></span>
		<span id="play1"><?php echo JText::_('MOD_YOORECIPE_GALLERY_PLAY'); ?> &gt;</span>
		<span id="stop1"><?php echo JText::_('MOD_YOORECIPE_GALLERY_STOP'); ?></span>
		<span id="next1"><?php echo JText::_('MOD_YOORECIPE_GALLERY_NEXT'); ?> &gt;&gt;</span>
	</p>
	<?php endif; ?>
</div>
<?php 
	} 
	else if ($layout == 'player2') {
?>
<!-- HORIZONTAL SLIDE SHOW 2 -->
<div class="sample">
	<div class="mask2">
		<div id="box3">
<?php	foreach ($items as $item) :?>
			<span class="gallery">
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" alt="<?php echo htmlspecialchars($item->title); ?>" title="<?php echo htmlspecialchars($item->title); ?>"/>
				</a>
			</span>
<?php	endforeach; ?>
		</div>
	</div>
	<?php if ($params->get('show_player', 1)) : ?>
	<p class="buttons">
		<span id="playback3">&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_REWIND'); ?></span>
		<span id="stop3"><?php echo JText::_('MOD_YOORECIPE_GALLERY_STOP'); ?></span>
		<span id="play3"><?php echo JText::_('MOD_YOORECIPE_GALLERY_PLAY'); ?> &gt;</span>
	</p>
	<?php endif; ?>
</div>
<?php 
	} 
	else if ($layout == 'exerpt') {
?>
<!-- TEXTE HORIZONTAL SLIDER -->
<div class="sample">
	<div class="mask3" style="width:<?php echo $exerpt_width; ?>px">
		<div id="box4">
<?php 	foreach ($items as $item) : ?>
			<div style="width:<?php echo $exerpt_width; ?>px">
				<h3>
					<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
						<?php echo htmlspecialchars($item->title); ?>
					</a>
				</h3>
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" alt="<?php echo htmlspecialchars($item->title); ?>" />
				</a>
				<p><?php echo $item->preparation; ?></p>
			</div>
<?php 	endforeach; ?>
		</div>
	</div>
	<h4><?php echo JText::_('MOD_YOORECIPE_GALLERY_DISPLAYED');?>: <span id="info4"></span></h4>
	<?php if ($params->get('show_navigation', 1)) : ?>
		<p class="buttons" id="handles4">
		<?php $i = 1; ?>
		<?php foreach ($items as $item) : ?>
			<span><?php echo $i . '. ' . $item->shortTitle; $i++; ?></span>
		<?php endforeach; ?>
		</p>
	<?php endif; ?>
</div>
<?php 
	}
	else if ($layout == 'legendplayer') {
?>
<!-- VERTICAL SLIDER WITH WATERMARK -->
<div class="sample">
	<div class="mask2">
		<div id="box5">
<?php 	foreach ($items as $item) : ?>
			<span class="gallery">
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" height="180px" alt="<?php echo htmlspecialchars($item->title); ?>" />
				</a>
			</span>
<?php 	endforeach; ?>
		</div>
		<div id="info5" class="info"></div>
	</div>
	<?php if ($params->get('show_player', 1)) : ?>
		<p class="buttons">
			<span id="prev5">&lt;&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_PREVIOUS'); ?></span>
			<span id="play5"><?php echo JText::_('MOD_YOORECIPE_GALLERY_PLAY'); ?> &gt;</span>
			<span id="stop5"><?php echo JText::_('MOD_YOORECIPE_GALLERY_STOP'); ?></span>
			<span id="next5"><?php echo JText::_('MOD_YOORECIPE_GALLERY_NEXT'); ?> &gt;&gt;</span>
		</p>
	<?php endif; ?>
</div>
<?php 
	}
	else if ($layout == 'vslide') {
?>
<!-- VERTICAL SLIDER WITH 2 COLS -->
<div class="sample sample6" style="width:340px">
	<div class="thumbs" id="handles6_1">
<?php	for ($i = 0 ; $i < count($items)/2; $i++) : ?>
			<div><img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" alt="<?php echo $items[$i]->title; ?>" /></div>
<?php 	endfor; ?>
	</div>
	<div class="mask6">
		<div id="box6">
<?php 	foreach ($items as $item) : ?>
			<span class="gallery"><img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" alt="<?php echo htmlspecialchars($item->title); ?>" /></span>
<?php 	endforeach; ?>
		</div>
		<div class="info"></div>
	</div>
	<div class="thumbs" id="handles6_2">
		<?php for ($i = count($items)/2 ; $i < count($items); $i++) : ?>
			<div><img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" alt="<?php echo $items[$i]->title; ?>" /></div>
<?php 	endfor; ?>
	</div>
	<?php if ($params->get('show_player', 1)) : ?>
		<p class="buttons">
			<span id="prev6">&lt;&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_PREVIOUS'); ?></span>
			<span id="playback6">&lt;<?php echo JText::_('MOD_YOORECIPE_GALLERY_REWIND'); ?></span>
			<span id="stop6"><?php echo JText::_('MOD_YOORECIPE_GALLERY_STOP'); ?></span>
			<span id="play6"><?php echo JText::_('MOD_YOORECIPE_GALLERY_PLAY'); ?> &gt;</span>
			<span id="next6"><?php echo JText::_('MOD_YOORECIPE_GALLERY_NEXT'); ?> &gt;&gt;</span>
		</p>
	<?php endif; ?>
</div>
<?php 
	}
	else if ($layout == 'hslide_thumbs') {
?>
<!-- HORIZONTAL SLIDER WITH THUMBNAILS -->
<div class="sample">
	<div class="mask6">
		<div id="box7">
<?php 	foreach ($items as $item) : ?>
			<span class="gallery">
				<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
					<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" width="240px" alt="<?php echo htmlspecialchars($item->title); ?>" title="<?php echo htmlspecialchars($item->title); ?>" />
				</a>
			</span>
<?php 	endforeach; ?>
		</div>
	</div>

	<div id="thumbs7">
		<div class="thumbs">
		<?php foreach ($items as $item) : ?>
			<div><img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>" alt="<?php echo htmlspecialchars($item->title); ?>" /></div>
		<?php endforeach; ?>
		</div>

		<div id="thumbs_mask7"></div>

		<p id="thumbs_handles7">
		<?php foreach ($items as $item) :  ?>
			<span></span>
		<?php endforeach; ?>
		</p>
	</div>
</div>
<?php 
	} 
	else if ($layout == 'exerpt2') {
?>
<!-- SAMPLE 8 -->
<div class="sample sample8">
	<p class="buttons" id="handles8">
	<?php foreach ($items as $key=>$item) :  ?>
		<span><?php echo $key . ' ' . $item->shortTitle; ?></span>
	<?php endforeach; ?>
	</p>

	<div class="mask8" style="width:<?php echo $exerpt_width; ?>px">
		<div id="box8">
		<?php foreach ($items as $key=>$item) :  ?>
			<div style="width:<?php echo $exerpt_width; ?>px">
			<?php if ($params->get('show_navigation', 1)) : ?>
				<p class="buttons"><span class="prev">&lt;&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_PREVIOUS'); ?></span> <span class="next"><?php echo JText::_('MOD_YOORECIPE_GALLERY_NEXT'); ?> &gt;&gt;</span></p>
			<?php endif; ?>
				<h3>
					<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
						<?php htmlspecialchars($item->title); ?>
					</a>
				</h3>
				<p>
					<a href="<?php echo JHtmlYooRecipeHelperRoute::getRecipeRoute($item->slug); ?>">
						<img src="<?php echo ($use_watermark) ? JHtml::_('yoorecipeutils.watermarkImage', $item->picturePath, 'Copyright ' . juri::base()) : $item->picturePath; ?>"/>
					</a>
				</p>
				<?php
				if ($params->get('show_ingredients', 1)) :
					if (count($item->ingredients) > 0) : 

						$descriptions;
						foreach ($item->ingredients as $ingredient) :
							$descriptions[] = htmlspecialchars($ingredient->description);
						endforeach;
						
						echo '<p><span class="ingredientsTitle">' . JText::_('MOD_YOORECIPE_GALLERY_INGREDIENTS') . ': </span>';
						echo '<span class="ingredientsList">' . implode(',', $descriptions) . '</span>';
					endif;
				endif;
				?>
				
				<p><?php echo $item->preparation; ?></p>
			</div>
		<?php endforeach; ?>
		</div>
	</div>

	<?php if ($params->get('show_navigation', 1)) : ?>
	<p class="buttons">
		<span id="prev8">&lt;&lt; <?php echo JText::_('MOD_YOORECIPE_GALLERY_PREVIOUS'); ?></span> | <span id="next8"><?php echo JText::_('MOD_YOORECIPE_GALLERY_NEXT'); ?> &gt;&gt;</span>
	</p>
	<?php endif; ?>
	
	<?php if ($params->get('show_player', 1)) : ?>
	<p class="buttons">
		<span id="playback8">&lt;<?php echo JText::_('MOD_YOORECIPE_GALLERY_REWIND'); ?></span>
		<span id="stop8"><?php echo JText::_('MOD_YOORECIPE_GALLERY_STOP'); ?></span>
		<span id="play8"><?php echo JText::_('MOD_YOORECIPE_GALLERY_PLAY'); ?> &gt;</span>
	</p>
	<?php endif; ?>

	<!--p class="buttons" id="handles8_more">
		<php foreach ($items as $key=>$item) :  ?>
		<span><php echo $key; ?></span>
	<php endforeach; ?>
	</p-->

</div>
<?php 
	} // End ifelseif
} // End if isset
?>

<?php
	if (strlen($params->get('outro_text')) > 0) :
?>
<div class="description">
	<p><?php echo $params->get('outro_text'); ?></p>
</div>
<?php
	endif;
?>

</div>