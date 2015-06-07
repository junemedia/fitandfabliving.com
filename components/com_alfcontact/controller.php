<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * ALFContact Component Controller
 */
class AlfcontactController extends JControllerLegacy
{
	function sendemail()
	{
		function in_ip_range($ip_one, $ip_two=false)
		{
			if($ip_two===false){
				if($ip_one==$_SERVER['REMOTE_ADDR']){
					$ip=true;
				}else{
					$ip=false;
				}
			}else{
				if(ip2long($ip_one)<=ip2long($_SERVER['REMOTE_ADDR']) && ip2long($ip_two)>=ip2long($_SERVER['REMOTE_ADDR'])){
					$ip=true;
				}else{
					$ip=false;
				}
			}
			return $ip;
		}
				
		$app = JFactory::getApplication();
		$model = $this->getModel();

		// check if website uses CloudFlare and set IP
		if (in_ip_range('204.93.240.0','204.93.240.255') ||
		    in_ip_range('204.93.177.0','204.93.177.255') ||
		    in_ip_range('199.27.128.0','199.27.135.255') ||
		    in_ip_range('173.245.48.0','173.245.63.255') ||
			in_ip_range('103.22.200.0','103.22.203.255') ||
		    in_ip_range('141.101.64.0','141.101.127.255') ||
		    in_ip_range('108.162.192.0','108.162.255.255') ||
		    in_ip_range('190.93.240.0','190.93.255.255'))
		{
			$site_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} else{
			$site_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		// get the parameters
		$params = JComponentHelper::getParams('com_alfcontact');
		$redirect_option = $params->get('redirect_option', 1);
		$redirect_url = $params->get('custom_header', '');
		$verbose = $params->get('verbose', 1);
		$html = $params->get('mailformat', 1);
		$site = $params->get('fromsite', 0);
		$max = $params->get('maxchars', '');
		$sitename = $app->getCfg('fromname');
		$siteaddress = $app->getCfg('mailfrom');
		
		if ($html)
		{
			$sep  = "<BR>";
			$line = "<HR>";
		} 
		else 
		{
			$sep  = "\n";
			$line = "-------------------------------------------------------------------------------\n";
		}
						
				
		//Variable ophalen die verstuurd zijn via URL
        $name       = JRequest::getString('name','', 'post');
		$email      = JRequest::getString('email','', 'post');		
		$emailto_id = JRequest::getInt('emailto_id', 99);
		$subject    = JRequest::getString('subject','','post');
        $message    = JRequest::getString('message','','post');
		$copy       = JRequest::getVar('copy', 0);
		$extravalues= JRequest::getString('extravalues','','post');
		
		    
        //Store form data in the session
     	$app->setUserState('com_alfcontact.name', $name);
		$app->setUserState('com_alfcontact.email', $email);
		$app->setUserState('com_alfcontact.emailto_id', $emailto_id);
		$app->setUserState('com_alfcontact.subject', $subject);
		$app->setUserState('com_alfcontact.message', $message);
		$app->setUserState('com_alfcontact.copy', $copy);
				
		//check the security measures
		if (!$model->CheckCaptcha()) 
		{
			JError::raiseWarning("0", JText::_('COM_ALFCONTACT_WRONG_CAPTCHA'));
			$this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=alfcontact', false));
			return false;
		}
		
                // field validation - we trim the input to prevent whitespace-only values
                if (!trim($name)) {
                    JError::raiseWarning("0", JText::_('COM_ALFCONTACT_INVALID_NAME'));
                    $this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=alfcontact', false));
                    return false;
                }
                if (!preg_match('/^[a-zA-Z0-9._-]+(\\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\\.)+[a-zA-Z0-9.-]{2,4}$/', $email)) {
                    JError::raiseWarning("0", JText::_('COM_ALFCONTACT_INVALID_EMAIL'));
                    $this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=alfcontact', false));
                    return false;
                }
                if (!trim($subject)) {
                    JError::raiseWarning("0", JText::_('COM_ALFCONTACT_INVALID_SUBJECT'));
                    $this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=alfcontact', false));
                    return false;
                }
                if (!trim($message)) {
                    JError::raiseWarning("0", JText::_('COM_ALFCONTACT_INVALID_MESSAGE'));
                    $this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=alfcontact', false));
                    return false;
                }
                
		//get email address coresponding to ID number
		if ($emailto_id == '99')
		{
			$emailto = $siteaddress; 
		}
		else
		{		
			
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__alfcontact WHERE id =". (int) $emailto_id;
		
			$db->setQuery( $query );
        	$rows      = $db->loadObjectList();
			$emailto   = $rows[0]->email;
			$prefix    = $rows[0]->prefix;
			$optfields = $rows[0]->extra;
					
            //Adding prefix to subject
			$subject = $prefix.' '.$subject;
		}
		
		//Split multiple email addresses into an array
		$recipients = explode("\n", $emailto);
		
		// Add information from the extra fields if applicable
		$fields_array = explode("\n", $optfields);
		$values_array = explode("\n", $extravalues);
		$extra_array = array_combine($fields_array, $values_array);
		
		if ($extra_array > 0){
			$extramsg = '';
			foreach ($extra_array as $key=>$value) {
				$extramsg = $extramsg . $key . ' ' . $value . $line;
			}
			$message = $extramsg . $sep . $message;
		}	
												
		// send copy if requested
		if ($copy)
		{
			$copySubject = JText::_('COM_ALFCONTACT_COPYOFMESSAGE').' '.$sitename ;
			
			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->setSender(array($siteaddress, $sitename));
			$mail->setSubject($copySubject);
			$mail->setBody($message);
			if ($html)
			{
				$mail->IsHTML(True);
				//$mail->setBody(nl2br($message));
		    }
			$sent = $mail->Send();
		}
		
		//Add an infomation banner to the top of the contacts message.
		if ($verbose)
		{
			$header = JText::_('COM_ALFCONTACT_DETAILS_HEADER') . $sep;
			$header = $header . $line;
			$header = $header . JText::_('COM_ALFCONTACT_DETAILS_NAME') . " " . $name . $sep;
			$header = $header . JText::_('COM_ALFCONTACT_DETAILS_EMAIL') . " " . $email . $sep;
			$header = $header . JText::_('COM_ALFCONTACT_DETAILS_IP') . " " . $site_ip . $sep;
			$header = $header . JText::_('COM_ALFCONTACT_DETAILS_BROWSER') . " " .$_SERVER['HTTP_USER_AGENT'] . $sep;
			$header = $header . $line;
			$message = $header . $message;
		}
		
		//send mail
		$mail = JFactory::getMailer();
		
		foreach($recipients as $value)
		{
			$mail->addRecipient($value);	
		}
						
		if ($site)
		{
			$mail->setSender(array($siteaddress, $name));
		}
		else
		{
			$mail->setSender(array($email, $name));
		}
				
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->addReplyTo(array($email, $name));
		
		if ($html) 
		{
			$mail->IsHTML(True);
			//$mail->setBody($message);
			//$mail->setBody(nl2br($message));
		}
				
		$sent = $mail->Send();
		
		//Clear session variables
		$app->setUserState('com_alfcontact.name', null);
		$app->setUserState('com_alfcontact.email', null);
		$app->setUserState('com_alfcontact.emailto_id', null);
		$app->setUserState('com_alfcontact.subject', null);
		$app->setUserState('com_alfcontact.message', null);
		$app->setUserState('com_alfcontact.copy', null);
		
		//redirect
		switch ($redirect_option) {
			case 2: $this->setRedirect(JURI::current());
					break;
			case 3: $this->setRedirect(JRoute::_('index.php?option=com_alfcontact&view=response'));
					break;
			case 4: $this->setRedirect($redirect_url);
					break;
			default:$this->setRedirect(JRoute::_(JURI::root()));
					break;
		}
	}    
}
