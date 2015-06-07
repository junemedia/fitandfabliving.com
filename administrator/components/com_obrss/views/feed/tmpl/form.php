<?php
/**
 * @version		$Id: form.php 732 2013-07-22 08:53:07Z tsvn $
 * @package		obRSS Feed Creator for Joomla.
 * @copyright	(C) 2007-2012 foobla.com. All rights reserved.
 * @author		foobla.com
 * @license		GNU/GPL, see LICENSE
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
global $isJ25;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$boolean_array = array (
	JHTML::_('select.option', '0', JText::_('OBRSS_SETTINGS_NO')),
	JHTML::_('select.option', '1', JText::_('OBRSS_SETTINGS_YES'))
);
JFilterOutput::objectHTMLSafe( $this->jlord_rss, ENT_QUOTES );
$detail	= $this->jlord_rss->components;
echo "<script type=\"text/javascript\"> var addons = Array('".implode('\',\'',$this->addons->lists)."');</script>";
?>
<script type="text/javascript">
<!--
	if (typeof(Joomla) === 'undefined') {
		var Joomla = {};
	}
	function submitbutton(pressbutton){
		obSubmitbutton(pressbutton,true);
	}
	Joomla.submitbutton = function(pressbutton) {
		if (pressbutton == 'preview') {
			var obrssid = document.adminForm.id.value;
			var obrss_alias = document.adminForm.alias.value;
			window.open('<?php echo JURI::root(); ?>index.php?option=com_obrss&controller=feed&task=feed&id='+obrssid+':'+obrss_alias);
			return false;
		}
		var form = document.adminForm;
		if (pressbutton != '') {
			if (pressbutton == 'cancel') {
				Joomla.submitform( pressbutton );
			} else {
				if (form.name.value == ""){
					form.name.focus();
					alert( "Item must have a name" );
				} else {
					Joomla.submitform(pressbutton);
				}
			}
		}
	}
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton != '') {
			if (pressbutton == 'preview') {
				var obrssid = document.adminForm.id.value;
				window.open('<?php echo JURI::root(); ?>index.php?option=com_obrss&controller=feed&task=feed&id='+obrssid);
				return false;
			}
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
			} else {
				if (form.name.value == ""){
					form.name.focus();
					alert( "Item must have a name" );
				} else {
					submitform(pressbutton);
				}
			}
		}
	}
	function loadButton(elem) {
		document.getElementById("feedButton").src =  '<?php echo JURI::root(); ?>components/com_obrss/images/buttons/' + elem.value;
	}
	function showComParamater() {
		var catNamelist = document.getElementById('components').value;
		var arrCatName = new Array();
		var length = catNamelist.length;
		catName = 'obrss_addon_'+catNamelist;
		for(i = 0;i < addons.length; i++) {
			if(catName == addons[i]) {
				document.getElementById(catName).style.display = "block";
				document.getElementById('detail').value = catName;
			}else {
				document.getElementById(addons[i]).style.display = "none";
			}
		}
	}
	function HSFeedParam(me,el){
		var gid = function(id){return document.getElementById(id);}
		var fpr = gid(el).style;
		if(fpr.display=='none'){
			fpr.display = 'block';
			me.className	= 'title pane-toggler-down';
		}else{
			fpr.display = 'none';
			me.className	= 'title pane-toggler';
		}
	}
	function obRssResetHits(){
		var obrssid = document.adminForm.id.value;
		document.location	= 'index.php?option=com_obrss&controller=feed&task=resethits&id='+obrssid;
	}
	function obDoSwitch(switcher){
		var el = switcher.getParent().getFirst(".ob_switch");
		//var vl = el.getParent().getFirst(".ob_switch").value;
		if(el.value == 1){
			switcher.setProperty("class","switcher-off");
			el.value = 0;
		}else{
			switcher.setProperty("class","switcher-on");
			el.value = 1;
		}
	}

	// Bootstrap nav-tabs
	jQuery(document).ready(function ($){
		$('#myTab a').click(function (e)
		{
			e.preventDefault();
			$(this).tab('show');
		});
		(function($){
			
			// Turn radios into btn-group
		    $('.radio.btn-group label').addClass('btn');
		    $(".btn-group label:not(.active)").click(function() {
		        var label = $(this);
		        var input = $('#' + label.attr('for'));

		        if (!input.prop('checked')) {
		            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
		            if(input.val()== '') {
		                    label.addClass('active btn-primary');
		             } else if(input.val()==0) {
		                    label.addClass('active btn-danger');
		             } else {
		            label.addClass('active btn-success');
		             }
		            input.prop('checked', true);
		        }
		    });
		    $(".btn-group input[checked=checked]").each(function() {
				if($(this).val()== '') {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
		        } else if($(this).val()==0) {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
		        } else {
		            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
		        }
		    });
		})(jQuery);
	});

	
//-->
</script>
<?php if(!$isJ25){ ?>
<style type="text/css">
fieldset.adminform label.radiobtn-no {
	width:40px;
}
label.radiobtn-no,
label.radiobtn-yes{
	clear: none;
	display: inline;
}
</style>
<?php } ?>
<div id="foobla">
<form id="adminForm" name="adminForm" action="index.php?option=com_obrss&controller=feed" method="post" class="form-horizontal">
	<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span10">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active dropdown">
					<a href="#general" data-toggle="tab"><?php echo JText::_('OBRSS_FEED_DETAILS');?></a>
					<!--<a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown trigger<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<li class=""><a href="#general" data-toggle="tab"><?php echo JText::_('OBRSS_FEED_DETAILS');?></li>
						<li class="divider"></li>
						<li class=""><a href="/obexts/j30/administrator/index.php?option=com_login&amp;task=logout&amp;ee92d45b30733a0e14f2e8a8b0ed9e0f=1">**Logout**</a></li>
					</ul>
				--></li>
				<li><a href="#feedparams" data-toggle="tab"><?php echo JText::_('OBRSS_FEED_PARAMATERS');?></a></li>
			</ul>
			
			<div class="tab-content">
				<!-- Begin Tabs -->
				<div class="tab-pane active" id="general">
					<div class="control-group">
						<div class="control-label">
							<label id="name-lbl" for="name" class="hasTip required">
								<?php echo JText::_('OBRSS_NAME' );?>
								<span class="star">&nbsp*</span>
							</label>
						</div>
						<div class="controls">
							<input type="text" class="inputbox" name="name" size="40" value="<?php echo $this->jlord_rss->name;?>"/>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<label>
								<?php echo JText::_( 'OBRSS_ALIAS' ); ?>
							</label>
						</div>
						<div class="controls">
							<input class="text_area" class="inputbox" type="text" name="alias" id="alias" size="40" maxlength="250" value="<?php echo $this->jlord_rss->alias;?>" />
						</div>
					</div>
					
					
					<div class="control-group">
						<div class="control-label">
							<label>
								<?php echo JText::_( 'OBRSS_DESCRIPTION' ); ?>
							</label>
						</div>
						<div class="controls">
							<textarea rows="5" cols="40" name="description"><?php echo $this->jlord_rss->description; ?></textarea>
						</div>
					</div>
					
					<div class="">
						<span class="hasTip" title="<?php echo JText::_( 'OBRSS_CONTENT_DATA_SOURCE' ).'::'.JText::_('OBRSS_CONTENT_DATA_SOURCE_DESC')?>"><?php echo $this->lists1['components']; ?></span>
						<span class="hasTip" title="<?php echo JText::_('OBRSS_CONTENT_DATA_SOURCE_DESC_EXTRA')?>"><a href="http://foob.la/obRSSstore" target="_blank"><img src="components/com_obrss/assets/images/obstore_48.png" width="48" /></a></span>
					</div>
					
					<div class="">
						<div class="accordion-inner"><?php echo $this->addons->params;?></div>
					</div>
				</div>
				
				<div class="tab-pane" id="feedparams">
					<?php echo $this->params->render();?>
				</div>
				<!-- End Tabs -->
			</div>
		</div>
		<!-- End Content -->
		<!-- Begin Sidebar -->
		<div class="span2">
			<h4><?php echo JText::_('JDETAILS');?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="control-label">
						<label id="published-lbl" for="jform_published" class="hasTip" title=""><?php echo JText::_('OBRSS_PUBLISHED' ); ?></label>
					</div>
					<?php echo JHTML::_('obinputs.radiolist', $boolean_array, 'published', 'class="radio btn-group"', 'value', 'text', $this->jlord_rss->published); ?>
					<?php //echo JHTML::_('select.genericlist', $boolean_array, 'published', 'class="radio btn-group"', 'value', 'text', $this->jlord_rss->published); ?>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="use_feedburner-lbl" for="use_feedburner" class="hasTip" title="">
							<?php echo JText::_('OBRSS_USE_URL_FEEDBURNER' ); ?>
						</label>
					</div>
					<?php
					$tuse_feedburner_opt = array (
						JHTML::_('select.option', '2', JText::_('JGLOBAL_USE_GLOBAL')),
						JHTML::_('select.option', '0', JText::_('JNO')),
						JHTML::_('select.option', '1', JText::_('JYES'))
					); 
// 					echo JHTML::_('obinputs.radiolist', $tuse_feedburner_opt, 'use_feedburner', 'class="radio btn-group"', 'value', 'text', $this->jlord_rss->use_feedburner );
					echo JHTML::_('select.genericlist', $tuse_feedburner_opt, 'use_feedburner', 'class="span12" size="1"', 'value', 'text', $this->jlord_rss->use_feedburner ); ?>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="ordering-lbl" for="ordering" class="hasTip" title="">
							<?php echo JText::_('OBRSS_ORDERING' ); ?>
						</label>
					</div>
					<div class="controls">
						<?php echo $this->lists1['ordering']; ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="hits-lbl" for="hits" class="hasTip" title="">
							<?php echo JText::_('OBRSS_HITS' ); ?>
						</label>
					</div>
					<div class="controls">
						<?php
						$hist	= (int)$this->jlord_rss->hits;
						echo $hist;
						if($hist>0){
							echo '&nbsp;&nbsp;<input style="float: none;" name="reset_hits" type="button" class="btn btn-inverse btn-small inputbox" value="'.JText::_( 'Reset' ).'" onclick="obRssResetHits();" />';
						}
						?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="type_feed-lbl" for="type_feed" class="hasTip" title="">
							<?php echo JText::_('OBRSS_TYPE_FEED' ); ?>
						</label>
					</div>
					<div class="controls">
						<?php echo($this->lists1['feedButtons']);?>&nbsp;&nbsp;
						<img id="feedButton" src="<?php echo (JURI::root() . "components/com_obrss/images/buttons/" . $this->button); ?>" valign="middle" />
					</div>
				</div>
				
				<div class="control-group">
					<label id="feeded-lbl" for="feeded" class="hasTip" title="">
						<?php echo JText::_('OBRSS_FEED_HEADTAG' ); ?>
					</label>
					<div class="controls">
						<?php // echo JHTML::_('select.genericlist', $boolean_array, 'feeded', 'class="span12"', 'value', 'text', $this->jlord_rss->feeded); ?>
						<?php echo JHTML::_('obinputs.radiolist', $boolean_array, 'feeded', 'class="radio btn-group"', 'value', 'text', $this->jlord_rss->feeded); ?>
					</div>
				</div>
				
				<div class="control-group">
					<label id="display_feed_module-lbl" for="display_feed_module" class="hasTip" title="">
						<?php echo JText::_('OBRSS_DISPLAY_FEED' ); ?>
					</label>
					<div class="controls">
						<?php //echo JHTML::_('select.genericlist', $boolean_array, 'display_feed_module', 'class="span12"', 'value', 'text', $this->jlord_rss->display_feed_module); ?>
						<?php echo JHTML::_('obinputs.radiolist', $boolean_array, 'published', 'class="radio btn-group"', 'value', 'text', $this->jlord_rss->published); ?>
					</div>
				</div>
			</fieldset>
		</div>
		<!-- End Sidebar -->
	</div>
<input type="hidden" name="id" value="<?php echo $this->jlord_rss->id;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="detail"  id="detail" value = "<?php echo $detail;?>"/>
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>