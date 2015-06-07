<?php
/**
 * @version		$Id: fb_statistics.php 732 2013-07-22 08:53:07Z tsvn $
 * @package	foobla RSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author: foobla.com
 * @license: GNU/GPL, see LICENSE
 */
// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');
JHTML::_( 'behavior.calendar' ); 
$img_src = JURI::root(true).DS.'templates'.DS.'system'.DS.'images'.DS.'calendar.png';
?>
<script  type="text/javascript">
<!--
window.addEvent('domready', function() {Calendar.setup({
        inputField     :    "fromDate",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "fromdate_img",  // trigger for the calendar (button ID)
        align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
// "Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
        singleClick    :    true
});});
window.addEvent('domready', function() {Calendar.setup({
    inputField     :    "toDate",     // id of the input field
    ifFormat       :    "%Y-%m-%d",      // format of the input field
    button         :    "todate_img",  // trigger for the calendar (button ID)
    align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
//"Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
    singleClick    :    true
});});
function checkDates(form){
	var fromdate = form.fromdate.value;
	var todate 	 = form.todate.value;
	re = /^\d{4}\-\d{1,2}\-\d{1,2}$/; 
	if(!fromdate.match(re)||!todate.match(re)){
		alert("<?php echo JText::_('Invalid date format'); ?>");
		return false;
	}
	if(fromdate >= todate){
		alert ("<?php echo JText::_('Date values are not correct'); ?>");
		form.todate.focus();
		return false;
	}
	return true;
}
//-->
</script>
<form action="index.php?option=com_obrss&controller=feed" method="post" name="adminForm" id="adminForm">
<div  style="margin:10px;font-size:22px;font-weight:bold"><?php echo $this->data->feedname." - ".JText::_('OBRSS_STATS_TITLE'); ?></div>
<div style="width=100%">
	<table>
		<tr>
			<td>Select data by: 
			</td>
			<td>
				<?php echo $this->data->list_view;?>
			</td>
				<td align="right"><?php echo JText::_('OBRSS_STATS_FROMDATE').":"?>
			</td>
			<td align="left">
				<input name="fromdate" id="fromDate" type="text" value="<?php echo $this->data->fromdate;?>" /> 
			</td>
			<td align="left">
				<img class="calendar" src="<?php echo $img_src;?>" alt="calendar" id="fromdate_img" / >
			</td>
			<td align="right"><?php echo JText::_('OBRSS_STATS_TODATE').":"?> 
			</td>
			<td align="left">
				<input name="todate" id="toDate" type="text" value="<?php echo $this->data->todate;?>"/> 
			</td>
			<td align="left">
				<img class="calendar" src="<?php echo $img_src;?>" alt="calendar" id="todate_img" / >
			</td>
			<td>Slect chart type:
			</td>
			<td>
				<?php echo $this->data->list_chart;?>
			</td>
			<td>
				<input type="submit" value="Select" onclick="return checkDates(adminForm);" <?php if(!$this->data->number_record) echo "disabled";?>/>
			</td>
		</tr>
	</table> 
</div>
<div width="100%;">
<div style="width:50%;float:left">
		<table class="adminlist" align="center" >
			<thead>
				<tr>
					<th>
					#
					</th>
					<th class="title" width="40%">
					<?php 
					switch ($this->data->view){
						case 'daily' 	: echo JText::_('OBRSS_STATS_DAILY');
							break;
						case 'weekly'	: echo JText::_('OBRSS_STATS_WEEKLY');
							break;
						case 'monthly'  : echo JText::_('OBRSS_STATS_MONTHLY');
							break;
						case 'annually' : echo JText::_('OBRSS_STATS_ANNUALLY');
							break;
						default			: echo JText::_('OBRSS_STATS_DAILY');
							break;
					} ?>
					</th>
					<th class="title" width="15%"><?php echo JText::_('OBRSS_STATS_SUBCRIBERS')?>
					</th>
					<th class="title" width="15%"><?php echo JText::_('OBRSS_STATS_REACH')?>
					</th>
					<th class="title" width="15%"><?php echo JText::_('OBRSS_STATS_HITS')?>
					</th>
					<th class="title" width="15%"><?php echo JText::_('OBRSS_STATS_DOWNLOADS')?>
					</th>
				</tr>
			</thead>
		<?php 
			if(!$this->data->number_record)
			{
				echo "<tr><td align='center' colspan=6>".JText::_('OBRSS_STATS_NODATA')."</td></tr>";
			}
			$k = 0;
			$row = $this->data; 
			if(count($row)!=0){
			for($i = $row->number_record - 1, $j=1; $i>=0; $i--,$j++){
		?>
			<tr class="<?php echo "row$k";?>">
				<td align="center"><?php echo $j; ?>
				</td>
				<td align="center"><?php echo $row->time[$i];?>
				</td>
				<td align="center"><?php echo $row->circulation[$i];?>
				</td>
				<td align="center"><?php echo $row->reach[$i];?>
				</td>
				<td align="center"><?php echo $row->hits[$i];?>
				</td>
				<td align="center"><?php echo $row->downloads[$i];?>
				</td>
			</tr>
		<?php 
			$k = 1 - $k;} }
		?>
	</table>
</div>
<div style="float:left">
	<script type="text/javascript" src="<?php echo $this->data->swfobject_file;?>"></script>
	<div id="flashcontent">
		<strong><?php echo JText::_('You need to upgrade your Flash Player'); ?></strong>
	</div>
	<script type="text/javascript">
		var so = new SWFObject("<?php echo $this->data->chart->swf_file;?>", "<?php echo $this->data->chart->name;?>", "520", "400", "8", "#ffffff");
		so.addVariable("path", "<?php echo $this->data->chart->path;?>");
		so.addVariable("settings_file", encodeURIComponent("<?php echo $this->data->chart->setting;?>?<?php echo mktime();?>"));         
		so.addVariable("data_file", encodeURIComponent("<?php echo $this->data->chart->data;?>"));
		so.write("flashcontent");
	</script>
</div>
</div>
<input type="hidden" name="cid" value="<?php echo $row->id; ?>" />
<input type="hidden" name="task" value="view_stats_fb"/>
<input type="hidden" name="tmpl" value="component" />
</form>