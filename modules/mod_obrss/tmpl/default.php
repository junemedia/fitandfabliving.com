<?php
/**
 * @version		$Id: default.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE.DS.'components'.DS.'com_obrss'.DS.'helpers'.DS.'router.php';
// define style sheet
$ob_rss_css = $params->get('obrss_css');
if ($ob_rss_css!='') {
	$html = '
	<style type="text/css">
	'.$ob_rss_css.'
	</style>
	';
	$document = JFactory::getDocument();
	$document->addCustomTag($html);
}

$feed_link_dt = 'index.php?option=com_obrss&task=feed&id=';
$type = $params->def('type', 1);
if ($type == 1) { ?>
<ul id="jlord-rss" class="mod_obrss<?php echo $params->get('moduleclass_sfx'); ?>"><?php
	$imgLivemarks = JURI::root().'components/com_obrss/images/feeds_16.png';
	for ($i=0;$i<count($rows);$i++){
		$row	= $rows[$i];
		if($row->view == 'none'){
			$feed_link	= obRSSUrl::Sef($feed_link_dt.$row->id.':'.$row->alias);
		} else {
			$feed_link = 'http://feeds.feedburner.com/'.$row->uri;
		}
		$imgRss	= "<img src=\"$imgLivemarks\" alt=\"$row->name\" width=\"16\" height=\"16\" align=\"top\" border=\"0\"/>";
		?>
		<li>
			<a href="<?php echo $feed_link ?>" title="<?php echo $row->name;?>">
				<?php echo $imgRss.'&nbsp;'.$row->name;?>
			</a>
			<?php if ($params->get('hits', 1)) {echo "(".$row->hits.")";} ?>
		</li>
	<?php } ?>
</ul>
<?php } else { ?>
<div align="center"><?php 
	$imgBt = JURI::root() . 'components/com_obrss/images/buttons/';
	for ($i=0;$i<count($rows);$i++){
		$row	= $rows[$i];
		$feed_link	= obRSSUrl::Sef($feed_link_dt.$row->id.':'.$row->alias);
		?><div align="center">
			<a href="<?php echo $feed_link ?>">
				<img src="<?php if($row->feed_button != ""){echo $imgBt.$row->feed_button;} ?>" alt="<?php echo $row->name; ?>"  title="<?php echo $row->name;?>" > 
			</a> 
			<?php if ($params->get('hits', 1)) {echo "(".$row->hits.")";} ?>
		</div>
<?php } ?>
</div>
<?php }?>