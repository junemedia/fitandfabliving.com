<?php
/**
 * @version		$Id: default_j17.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

global $mainframe, $isJ25;

$rss		= $this->rss;
$configs	= $rss->configs;
$items		= $rss->items;

$params 	= $mainframe->getParams();
$pageNav	= $this->rss->pageNav;
$doc = JFactory::getDocument();
if ($params->get('menu-meta_keywords')!='') {
	$doc->setMetaData('keywords', $params->get('menu-meta_keywords'));
}
if ($params->get('menu-meta_description')!='') {
	$doc->setMetaData('description', $params->get('menu-meta_description'));
}
if ($params->get('show_number') == 1) {
	$colspan = 3;
} else {
	$colspan = 2;
}
ob_start(); ?>
	<table class="category table table-striped table-hover">
		<tbody>
			<?php
				$url_tmp	= parse_url(JURI::base());
				$url_base	= $url_tmp['scheme'].'://'.$url_tmp['host'];
				$readers	= array('google','yahoo','bloglines','newsgator','msn');
				$pathImg	=  JURI::base().'components/com_obrss/images/';
				$optionLink = 'index.php?option=com_obrss';
				$crow		= $pageNav->limitstart +1;
				$k 			= 1;
				
				// call itemshelper
				require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'itemshelper.php');
				for($i=0; $i < count($items); $i++) {
					$item	= $items[$i];
					$k	= ($k==2) ? 1:2;
					$format = itemsHelper::getFeedTypePrefix($item->feed_type);
					if($item->use_fb == 0 || $item->uri == ''){
						$feed_link	= obRSSUrl::Sef($optionLink."&task=feed&id=$item->id".':'.$item->alias.'&format='.$format);
					} else {
						$feed_link 	= "http://feeds.feedburner.com/".$item->uri;
					}
					$readerLink	= '';
					foreach ($readers as $reader){
						// get show/hide configuration value from preferences
// 						if($configs['button_'.$reader] ==0) {
						if ($params->get('button_'.$reader) == 0) {
							continue;
						}
						$aReader = $this->getLinkReder($reader, $pathImg, $url_base, $feed_link);
						$readerLink	.= '&nbsp;<span>'.$aReader.'</span>';
					}
				?>
			<tr class="cat-list-row<?php echo $k; ?>">
				<?php if ($params->get('show_number') == 1) : ?>
				<td>
					<?php echo $crow ++;?>
				</td>
				<?php endif; ?>
				<td>
					<?php echo '<a href="'.$feed_link.'">'.$item->name.'</a>'.($params->get('show_hits')==1?' (<span class="hasTip" title="'.JText::_('COM_OBRSS_COMPONENT_HITS_DESC').'">'.$item->hits.'</span>)':''); ?>
				</td>
				<td align="right">
					<?php echo $readerLink; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
		<thead>
			<th colspan="3"><?php echo $this->escape($params->get('obrss_page_subheading')); ?></th>
		</thead>
	</table>
<?php $listFeeds = ob_get_clean();?>
<div id="foobla">
	<div class="category-list<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<?php if ( $params->def( 'show_page_title', 1 ) ) : ?>
		<h3><?php echo $this->escape($params->get('page_title')); ?></h3>
		<?php endif; ?>
		<?php if ( $params->get('description_text') != '' && $rss->description!='') : ?>
		<div class="category-desc">
			<?php 
			$desc	= $rss->description;
			$inject	= is_array($desc) && isset($desc[1]);
			if ($inject) {
				echo $desc[0].$listFeeds.$desc[1];
			} else {
				echo $desc;
			}
			?>
			<div class="clr"></div>
		</div>
		<?php endif; ?>
		<?php if(!isset($inject) OR !$inject) echo $listFeeds; ?>
	</div>
</div>