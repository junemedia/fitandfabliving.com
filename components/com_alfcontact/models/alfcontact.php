<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * AlfContact Model
 */
class AlfcontactModelAlfcontact extends JModelList
{
    protected function getListQuery()
    {
        $user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		
		// Create a new query object.         
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('id,name,email,prefix,extra,defsubject,published,access');
        // From the alfcontact table
        $query->from('#__alfcontact');
        // Disable unpublished items
        $query->where('published > 0');
		// Accesslevels
		$query->where('access IN ('.$groups.')');
		// Language
		$query->where('language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		// Ordering
		$query->order('`ordering` ASC');
		
        return $query;
    }
	
	protected function populateState($ordering=null, $direction=null)
	{
		$this->setState('list.limit', 150);
	}
	
	function CheckCaptcha()
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		$params = JComponentHelper::getParams('com_alfcontact');
		$captchatype = $params->get('captchatype', 0);
		$privatekey  = $params->get('privatekey');
		$captcha 	 = $params->get('captcha');
		$captchas_user = $params->get('captchas_user', 'demo');
		$captchas_key = $params->get('captchas_key', 'secret');
		$captchas_alphabet = $params->get('captchas_alphabet', 'abcdefghijklmnopqrstuvwxyz');
		$captchas_chars = $params->get('captchas_chars', '6');
		$captchas_width = $params->get('captchas_width', '240');
		$captchas_height = $params->get('captchas_height', '80');
		$captchas_color = $params->get('captchas_color', '000000');
		$captchas_random_path = JPATH_SITE . '/tmp/captchasnet-random-strings';
		$response_field  = JRequest::getVar('recaptcha_response_field', '', 'post', 'string');				
		$challenge_field = JRequest::getVar('recaptcha_challenge_field', '', 'post', 'string');
		$captchas_entry  = JRequest::getVar('captchas_entry', '', 'post');
		$captchas_random = JRequest::getVar('captchas_random', '', 'post');
		
		// not using captcha!
		if (($captcha == 0) OR (($captcha == 2) AND ($user->name))) {
			return true;
		}
		
		$return = false;
		if ($captchatype == 0) 
		{
			require_once(JPATH_COMPONENT_SITE . '/recaptchalib.php');
			$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"], $challenge_field, $response_field);
			if ($resp->is_valid) 
			{
				$return = true;
			}
        }
		else
		{
			require_once(JPATH_COMPONENT_SITE . '/captchasdotnet.php');
			$captchas = new CaptchasDotNet($captchas_user, $captchas_key, $captchas_random_path, '3600',
											$captchas_alphabet, $captchas_chars, $captchas_width, $captchas_height, $captchas_color);
			
			// Check the random string to be valid and verify with user entry.
			$validate = $captchas->validate($captchas_random);
			$verify = $captchas->verify($captchas_entry);
			
			if (($validate) AND ($verify))
			{
				$return = true;
			}
		}
				
		if ($return) {
			return true;
		} else { 
			return false; 
		}
	}
}