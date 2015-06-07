<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$app = JFactory::getApplication();
// get some menu parameters
$menu = $app->getMenu()->getActive();
$this->defcontact = (int)$menu->params->get('defcontact');
$this->showpageheading = (int)$menu->params->get('show_page_heading', 1);
$this->pageheading = $menu->params->get('page_heading');
//Escape strings for HTML output
$this->pageclass_sfx = htmlspecialchars($menu->params->get('pageclass_sfx'));

$user = JFactory::getUser();
$document = JFactory::getDocument();
$params = JComponentHelper::getParams( 'com_alfcontact' );
	
$emailto_id = 99;
$extravalues = '';
$title = $menu->params->get('title', '');
$header = $menu->params->get('header', '');
$footer = $menu->params->get('footer', '');
$copyme = $params->get('copytome', 1);
$icons = $params->get('icons', 0);
$resetbtn = $params->get('resetbtn', 1);
$autouser = $params->get('autouser', 1);
$captcha = $params->get('captcha', 0);
$captchatype = $params->get('captchatype', 1);
$captchatheme = $params->get('captchatheme', 'red');
$captchalng = $params->get('captchalng', 'en');
$publickey = $params->get('publickey', '');
$privatekey = $params->get('privatekey', '');
$captchas_user = $params->get('captchas_user', 'demo');
$captchas_key = $params->get('captchas_key', 'secret');
$captchas_alphabet = $params->get('captchas_alphabet', 'abcdefghijklmnopqrstuvwxyz');
$captchas_chars = $params->get('captchas_chars', '6');
$captchas_width = $params->get('captchas_width', '240');
$captchas_height = $params->get('captchas_height', '80');
$captchas_color = $params->get('captchas_color', '000000');
$captchas_audiolink = $params->get('captchas_audiolink', 0);
$captchas_audiolng = $params->get('captchas_audiolng', 'en');
$captchas_random_path = JPATH_SITE . '/tmp/captchasnet-random-strings';
	
if ($captchatype == 1){
	require_once(JPATH_COMPONENT_SITE . '/captchasdotnet.php');
	$captchas = new CaptchasDotNet ($captchas_user, $captchas_key, $captchas_random_path, '3600',
                               $captchas_alphabet, $captchas_chars, $captchas_width, $captchas_height, $captchas_color);
	$audiolink = $captchas->audio_url();
	$audiolink = $audiolink	. '&language=' . $captchas_audiolng ;
}

if($menu->route =='write-for-us')
{
	$this->subject='Write for Fit and Fab Living';
}
?>
		
<script language="javascript" type="text/javascript">
	<!--
	var $j = jQuery;
	
	$j(document).ready(function(){
		
		//At the opening of the form
		updateForm();
		//And whenever changing the selectorbox
		$j("#emailid").change(function(){
			updateForm();
		});
		//Before the form submits, get the extra values 
		$j('#submit_btn').click(function()
		{
			var e_values = '';
			$j('.extra_field').each(function(index) {
				e_values = e_values + '\n' + $j(this).val();
			});
			$j('#extravalues').val(e_values); 
		});
	});
	
	function resetFormValues() {
		$j('#name').val('');
		$j('#email').val('');
		$j('#contact-form-message').val('');
		$j('.optfield').find('input:text').val('');
		$j('input[name=copy]').attr('checked', false);
		$j('#emailid').get(0).selectedIndex = 0;
		updateForm();
	}
	
	function updateForm(){
             
        //get the data from the selectorbox
		var contactdata = $j('#emailid').val();
		//split the data into an array: 0=id, 1=optional fields, 2=default subject
		var dataarray = contactdata.split(",");
		//seperate the optional fields
		var optfields = dataarray[1].split("\n");
		//Values of the optional fields
		//var extravalues = $j('#extravalues').val();		
		
		// setting the correct values
        $j('#subject').val(dataarray[2]);
		$j('#emailto_id').val(dataarray[0]);
						
		//Delete all optional fields
		$j('.optfield').remove();
		$j('#break').remove();
		
		//Create each optional field
		if (!optfields[0] == ''){
			$j('<div id="break"><br></div>').appendTo('.startfields');
			$j.each(optfields, function(i, value){			
				$j('<div class="control-group optfield">' + 
						'<div class="control-label">' + 
							'<label for="extra' + i + '">' + value + '</label>' + 
						'</div>' +
						'<div class="controls">' +
							'<input class="extra_field" type="text" id="extra' + i + '" name="extra' + i + '" value="" />' + 
						'</div>' + 
					'</div>').appendTo('.startfields');
			});
		}
		
	}
	
	var RecaptchaOptions = {theme : '<?php echo $captchatheme; ?>', lang : '<?php echo $captchalng; ?>'};
	//-->
</script>

<div class="item-page<?php echo $this->pageclass_sfx?>">
	<?php if ($title != '') : ?>
		<div class="page-header">
			<h2><?php echo $title; ?></a></h2>
		</div>	
	<?php endif; ?>
	
	<p><?php echo $header; ?></p>

	<div class="contact-form">
		<form action="<?php echo JRoute::_('index.php?option=com_alfcontact'); ?>" method="post" name="adminForm" id="contact-form" class="form-validate form-horizontal">

		<div class="control-group">
			<div class="control-label">
				<label for="name" class="hasTip required"><?php echo htmlspecialchars(JText::_('COM_ALFCONTACT_FORM_FROM')); ?></label>
			</div>
			<div class="controls">
				<?php if (!$autouser OR ($autouser AND !$user->name)) { ?> 
					<input class="required" name="name" id="name" type="text" value="<?php echo htmlspecialchars(isset($this->name) ? $this->name : ''); ?>"/>
				<?php } else { ?>
					<span><?php echo htmlspecialchars($user->name); ?></span>
					<input type="hidden" name="name" id="name" value= "<?php echo htmlspecialchars($user->name); ?>" /> 
				<?php } ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<label for="email" class="hasTip required"><?php echo htmlspecialchars(JText::_('COM_ALFCONTACT_FORM_EMAIL')); ?></label>
			</div>
			<div class="controls">
				<?php if (!$autouser OR ($autouser AND !$user->email)) { ?> 
					<input class="required validate-email" name="email" id="email" type="text" value="<?php echo htmlspecialchars(isset($this->email) ? $this->email : ''); ?>"/>
				<?php } else { ?>
					<span><?php echo htmlspecialchars($user->email); ?></span>
					<input type="hidden" name="email" id="email" value= "<?php echo htmlspecialchars($user->email); ?>" />
				<?php } ?>
			</div>
		</div>	
		<div>
			<hr />
		</div>
		<div class="control-group">
			<div class="control-label">
				<label for="emailid"><?php echo htmlspecialchars(JText::_('COM_ALFCONTACT_FORM_TO')); ?></label>
			</div>
			<div class="controls">
				<?php if (count($this->items) > 1) { ?>
                    <select name="emailid" id="emailid">
                        <?php foreach ($this->items as $i => $item) { ?>
                            <?php if ($item->id == $this->defcontact) { ?>
                                <option value="<?php echo htmlspecialchars($item->id . ',' . $item->extra . ',' . $item->defsubject); ?>" selected="selected"><?php echo htmlspecialchars($item->name); ?></option>
                            <?php } else { ?>
                                <option value="<?php echo htmlspecialchars($item->id . ',' . $item->extra . ',' . $item->defsubject); ?>" ><?php echo htmlspecialchars($item->name); ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <?php if (count($this->items) == 0) { ?>
                        <span><?php echo htmlspecialchars($app->getCfg('fromname')); ?></span>
                        <input type="hidden" name="emailid" id="emailid" value="99,," />
                    <?php } else { ?>
                        <span><?php echo htmlspecialchars($this->items[0]->name); ?></span>
                        <input type="hidden" id="emailid" name="emailid" value="<?php if($menu->route =='write-for-us'){$this->items[0]->defsubject = $this->subject;} echo htmlspecialchars($this->items[0]->id . ',' . $this->items[0]->extra . ',' . $this->items[0]->defsubject); ?>" />
                    <?php } ?>
                <?php } ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<label for="subject" class="hasTip required"><?php echo htmlspecialchars(JText::_('COM_ALFCONTACT_FORM_SUBJECT')); ?></label>
			</div>
			<div class="controls">
				<input class="required" name="subject" id="subject" type="text" value="<?php echo htmlspecialchars(isset($this->subject) ? $this->subject : ''); ?>"/>
			</div>
		</div>	
		<div class="control-group startfields">
			<div class="control-label">
				<label for="contact-form-message" class="hasTip required"><?php echo htmlspecialchars(JText::_('COM_ALFCONTACT_FORM_MESSAGE')); ?></label>
			</div>
			<div class="controls">
				<textarea class="required" name="message" id="contact-form-message"><?php echo htmlspecialchars(isset($this->message) ? $this->message : ''); ?></textarea>
			</div>
		</div>	
		
		<?php if ($copyme == 1) { ?>
			<div class="control-group">
				<div class="control-label">
					<label for="copy"><span><?php echo JText::_('COM_ALFCONTACT_FORM_COPYTOME') ?></span></label>
				</div>
				<div class="controls">
					<input type="checkbox" name="copy" id="copy"<?php echo (isset($this->copy) && $this->copy) ? ' checked=""' : '' ?> />
				</div>
			</div>
		<?php } 	
            if (($captcha == 1) OR (($captcha == 2) AND (!$user->name))) { 
                if ($captchatype == 0)  {
                    require_once(JPATH_COMPONENT_SITE . '/recaptchalib.php');
					$use_ssl = false;
					if ((isset($_SERVER['HTTPS']) &&	($_SERVER['HTTPS'] == 'on')) ||	getenv('SSL_PROTOCOL_VERSION')){
						$use_ssl = true;
					}?>
                <div class="control-group">
					<div class="control-label"></div>
					<div class="controls">
						<?php echo recaptcha_get_html($publickey, null, $use_ssl); ?>
					</div>
				</div>
				<?php } else { ?>
                <div class="control-group">
					<div class="control-label">
						<input type="hidden" name="captchas_random" id="captchas_random" value="<?php echo $captchas->random(); ?>" />
					</div>
					<div class="controls">
						<?php
                        echo $captchas->image(); 
                        if ($captchas_audiolink == 1) { ?> 
                            <br />
                            <a href="<?php echo $audiolink; ?>"><?php echo JText::_('COM_ALFCONTACT_CAPTCHAS_SPELLING')?></a>
                        <?php } ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="captchas_entry" class="hasTip required"><?php echo JText::_('COM_ALFCONTACT_CAPTCHAS_VERIFICATION')?></label>
					</div>
					<div class="controls">
						<input type="text" name="captchas_entry" class="required" id="captchas_entry" />
					</div>
				</div>
				<?php
                }
            } ?>
		<div class="form-actions">
			<button type="submit" id="submit_btn" class="btn btn-primary validate"><?php echo JText::_('COM_ALFCONTACT_FORM_SEND'); ?></button>
			<?php if ($resetbtn == 1) { ?>
				<button type="button" class="btn" onclick="resetFormValues()"><?php echo JText::_('COM_ALFCONTACT_FORM_RESET'); ?></button>
			<?php } ?>
			<input type="hidden" name="extravalues" id="extravalues" value="<?php echo $extravalues; ?>" />
			<input type="hidden" name="option" value="com_alfcontact" />
			<input type="hidden" name="task" value="sendemail" />
			<input type="hidden" name="emailto_id" id="emailto_id" value= "<?php echo htmlspecialchars($emailto_id); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		</form>
	</div>	
	<div><p><?php echo $footer; ?></p></div>
</div>  
