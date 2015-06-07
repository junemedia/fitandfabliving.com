<?php
/**
 * @version		$Id: default_j15.php 732 2013-07-22 08:53:07Z tsvn $
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

$params 	= &$mainframe->getParams();
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
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr>
			<td class="sectiontableheader" colspan="<?php echo $colspan; ?>"><?php echo $this->escape($params->get('obrss_page_subheading')); ?></td>
		</tr>
		<?php
		$url_tmp	= parse_url(JURI::base());
		$url_base	= $url_tmp['scheme'].'://'.$url_tmp['host'];
		$readers	= array('google','yahoo','bloglines','newsgator','msn');
		$pathImg	=  JURI::base().'components/com_obrss/images/';
		$optionLink = 'index.php?option=com_obrss';
		$crow		= $pageNav->limitstart +1;
		$k			= 1;
		for($i=0; $i < count($items); $i++) {
			$item	= $items[$i];
			$k	= ($k==2) ? 1:2;
			$format = $this->getFeedTypePrefix($item->feed_type);
			if($item->use_fb == 0 || $item->uri == ''){
				$feed_link	= obRSSUrl::Sef($optionLink."&task=feed&id=$item->id".':'.$item->alias.'&format='.$format);
			} else {
				$feed_link 	= "http://feeds.feedburner.com/".$item->uri;
			}
			$readerLink	= '';
			foreach ($readers as $reader) {
				if($params->get('button_'.$reader) ==0) {
					continue;
				}
				$aReader = $this->getLinkReder($reader, $pathImg, $url_base, $feed_link);
				$readerLink	.= '&nbsp;<span>'.$aReader.'</span>';
			}
		?>
		<tr class="sectiontableentry<?php echo $k; ?>">
			<?php if ($params->get('show_number') == 1) : ?>
			<td align="right">
				<?php echo $crow ++;?>
			</td>
			<?php endif; ?>
			<td align="left" class="jlord-rss-feed-title">
				<?php echo '<a href="'.$feed_link.'">'.$item->name.'</a>'.($params->get('show_hits')==1?' (<span class="hasTip" title="'.JText::_('COM_OBRSS_COMPONENT_HITS_DESC').'">'.$item->hits.'</span>)':''); ?>
			</td> 
			<td align="right">
				<?php echo $readerLink; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php $listFeeds = ob_get_clean();?>
<?php if ( $params->def( 'show_page_title', 1 ) ) : ?>
<h2 class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->escape($params->get('page_title')); ?>
</h2>
<?php endif; ?>
<?php if ( $params->get('description_text') != 0 && $rss->description!='') : ?>
	<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td valign="top" class="contentdescription<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<?php 
			$desc	= $rss->description;
			$inject	= is_array($desc) && isset($desc[1]);
			if ($inject) {
				echo $desc[0].$listFeeds.$desc[1];
			} else {
				echo $desc;
			}
			?>
			</td>
		</tr>
	</table>
<?php endif; ?>
<?php if(!isset($inject) OR !$inject) echo $listFeeds; ?>