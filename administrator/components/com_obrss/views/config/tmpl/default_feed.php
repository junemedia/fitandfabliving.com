<?php
/**
 * @version		$Id: default_feed.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$editor =& JFactory::getEditor();
$boolean_array = array (
	JHTML::_('select.option', '0', JText::_('OBRSS_SETTINGS_HIDE')),
	JHTML::_('select.option', '1', JText::_('OBRSS_SETTINGS_SHOW'))
);
?>
<script type="text/javascript">
    function loadButtonRss(elem) {
		document.getElementById("image_rssconfig").src = '<?php echo (JURI::root()."images/"); ?>' + elem.value;
	}
</script>
<!-- Guide Box goes here -->
<!-- 
<div class="col width-100">
	Help goes here
</div>
 -->
<!-- End Guide Box -->
<div class="col width-60 fltlft" style="clear:both;">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'OBRSS_SETTINGS_LEDEND_DESCRIPTION' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
				<td class="key" valign="top"><?php echo JText::_('OBRSS_SETTINGS_IMAGE'); ?></td>
				<td>
					<?php echo $this->lists['image']; ?>
					<br /><br /> 
					<img id="image_rssconfig" src="<?php echo (JURI::root()."images/" . $this->image); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_IMAGE_ALIGN'); ?></td>
				<td>
					<?php echo $this->lists['image_position']; ?>
				</td>
			</tr>
			<tr>
				<td class="key" valign="top"><?php echo JText::_('OBRSS_SETTINGS_DESCRIPTION_TEXT'); ?></td>
				<td>
					<p><?php echo JText::_( 'COM_OBRSS_DESCRIPTION_NOTE' ); ?></p>
					<?php
						echo $editor->display('description_text', $this->description_text, '400', '300', '60', '20', false);
					?>
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<span for="alias" class="editlinktip hasTip" title="<?php echo JText::_( 'OBRSS_SETTINGS_SEF_CORE_URL_DESC');?>">
						<?php echo JText::_( 'OBRSS_SETTINGS_SEF_CORE_URL' ); ?>
					</span>
				</td>
				<td>
					<input class="text_area" type="text" name="component" id="component" size="28" maxlength="250" value="<?php echo $this->component;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="col width-40 fltrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'OBRSS_SETTINGS_FRONTEND_LAYOUT' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_NUMBER'); ?></td>
				<td>
					<?php echo JHTML::_('select.genericlist', $boolean_array, 'show_number', 'class="inputbox ob_switch"', 'value', 'text', $this->show_number); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_HITS'); ?></td>
				<td>
					<?php echo JHTML::_('select.genericlist', $boolean_array, 'show_hits', 'class="inputbox ob_switch"', 'value', 'text', $this->show_hits); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_XML'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'button_xml', 'class="inputbox ob_switch"', 'value', 'text', $this->button_xml); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_GOOGLE'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'button_google', 'class="inputbox ob_switch"', 'value', 'text', $this->button_google); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_YAHOO'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'button_yahoo', 'class="inputbox ob_switch"', 'value', 'text', $this->button_yahoo); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_MSN'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'button_msn', 'class="inputbox ob_switch"', 'value', 'text', $this->button_msn); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_NEWSGATOR'); ?></td>
				<td>
				<?php echo JHTML::_('select.genericlist', $boolean_array, 'button_newsgator', 'class="inputbox ob_switch"', 'value', 'text', $this->button_newsgator); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_SHOW_BLOGLINES'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'button_bloglines', 'class="inputbox ob_switch"', 'value', 'text', $this->button_bloglines); ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OBRSS_SETTINGS_USE_FEEDBURNER_URL'); ?></td>
				<td><?php echo JHTML::_('select.genericlist', $boolean_array, 'view_feedburner', 'class="inputbox ob_switch"', 'value', 'text', $this->view_feedburner); ?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'OBRSS_SETTINGS_MISC' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
				<td class="key hasTip" title="<?php echo JText::_('OBRSS_SETTINGS_FEED_AUTHOR_DESC'); ?>"><?php echo JText::_('OBRSS_SETTINGS_FEED_AUTHOR'); ?></td>
				<td>
					<input class="text_area" type="text" name="feed_author" id="feed_author" size="40" maxlength="250" value="<?php echo $this->feed_author;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div style="clear: both"></div>
