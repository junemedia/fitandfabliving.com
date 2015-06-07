<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class JHtmlYooRecipeUtils
{
	/**
	 * Format an amount of minutes into hours and minutes
	 */
	public static function formatTime($duration, $D = null, $H = null, $M = null) {

		if ($D == null) {
			$D = JText::_('COM_YOORECIPE_DAY') . ' ';
		}
		if ($H == null) {
			$H = JText::_('COM_YOORECIPE_HOUR') . ' ';
		}
		if ($M == null) {
			$M = JText::_('COM_YOORECIPE_MIN');
		}
		
		if ($duration == 0) {
			return '0'. JText::_('COM_YOORECIPE_MIN');
		}
		 
		$d = floor ($duration / 1440);
		$h = floor ( ($duration - ($d * 1440)) / 60);
		$m = $duration % 60;
		
		$result = '';
		
		if ($d > 0) {
			$result .= $d . $D ;
		}
		if ($h > 0) {
			$result .= $h . $H;
		}
		if ($m > 0) {
			$result .= $m . $M;
		}
		return $result;
	}

	/**
	 * Returns the number of days containes in $duration minutes
	 */
	public static function getDaysFromDuration($duration) {
		return floor($duration / 1440);
	}
	
	/**
	 * Returns the number of hours contained in $duration minutes
	 */
	public static function getHoursFromDuration($duration) {
		return floor ( ($duration - JHtmlYooRecipeUtils::getDaysFromDuration($duration) * 1440) / 60);
	}
	
	/**
	 * Returns the remainder of minutes contained in $duration minutes
	 */
	public static function getMinutesFromDuration($duration) {
		return $duration % 60;
	}
	
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	public static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png',	'articles.featured',	'COM_CONTENT_UNFEATURED',	'COM_CONTENT_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png',		'articles.unfeatured',	'COM_CONTENT_FEATURED',		'COM_CONTENT_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image','admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html.'</a>';
		}

		return $html;
	}
	
	 /**
	  * Generate Social Bookmarks icons
	  */
	public static function socialSharing($params, $item)
	{
		$socialLink = urlencode(JURI::getInstance());
		$size 		= $params->get('bookmark_size');
		
		$html = array();
		$html[] = '<div class="yooRecipeSocialLinksBlock">';
		
		if ($params->get('show_title')) {
		
			$title = $params->get('title', JText::_("COM_YOORECIPE_SOCIAL_SHARING"));
			$html[] = '<span>' . $title . '</span>';
		}
		
		if ($size == 'small') {
			$html[] = '<ul class="socialLinks-small">';
		} else {
			$html[] = '<ul class="socialLinks-tall">';
		}
		
		if ($params->get('show_googleplus1')) {
			$html[] = '<li>' .  JHtmlYooRecipeUtils::getGooglePlus1($params) . '</li>';
		}

		if ($params->get('show_pinterest')) {
			if (($size == 'small')) {
				$html[] = '<li><a href="http://pinterest.com/pin/create/button/?url='.$socialLink.'&description='.htmlspecialchars($item->description).'" class="pin-it-button" count-layout="horizontal"><img border="0" src="http://passets-lt.pinterest.com/images/about/buttons/small-p-button.png" title="Pin It" /></a></li>';
			} else {
				$html[] = '<li><a href="http://pinterest.com/pin/create/button/?url='.$socialLink.'&description='.htmlspecialchars($item->description).'" class="pin-it-button" count-layout="horizontal"><img border="0" width="50" height="50" src="http://passets-lt.pinterest.com/images/about/buttons/big-p-button.png" title="Pin It" /></a></li>';
			}
		}
		if ($params->get('show_twitter')) {
			$html[] = '<li><a class="twitter-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_TWITTER') . '" href="https://twitter.com/share?original_referer=' . $socialLink . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_TWITTER') . '</span></a></li>';
		}
		if ($params->get('show_facebook')) {
			$html[] = '<li><a class="facebook-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_FACEBOOK') . '" href="http://www.facebook.com/sharer.php?u=' . $socialLink . '&amp;t=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_FACEBOOK') . '</span></a></li>';
		}
		if ($params->get('show_delicious')) {
			$html[] = '<li><a class="delicious-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_DELICIOUS') . '" href="http://del.icio.us/post?url=' . $socialLink . '&amp;title=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_DELICIOUS') . '</span></a></li>';
		}
		if ($params->get('show_diggthis')) {
			$html[] = '<li><a class="digg-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_DIGG') . '" href="http://digg.com/submit?url=' . $socialLink . '&amp;title=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_DIGG') . '</span></a></li>';
		}
		if ($params->get('show_reddit')) {
			$html[] = '<li><a class="reddit-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_REDDIT') . '" href="http://reddit.com/submit?url=' . $socialLink . '&amp;title=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_REDDIT') . '</span></a></li>';
		}
		if ($params->get('show_stumbleupon')) {
			$html[] = '<li><a class="stumble-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_STUMBLEUPON') . '" href="http://www.stumbleupon.com/submit?url=' . $socialLink . '&amp;title=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_STUMBLEUPON') . '</span></a></li>';
		}
		if ($params->get('show_myspace')) {
			$html[] = '<li><a class="myspace-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_MYSPACE') . '" href="http://www.myspace.com/Modules/PostTo/Pages/?l=3&amp;u=' . $socialLink . '&amp;t=' . urlencode($item->title) . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_MYSPACE') . '</span></a></li>';
		}
		if ($params->get('show_technorati')) {
			$html[] = '<li><a class="technorati-'.$size.'" title="' . JText::_('COM_YOORECIPE_ADD_TECHNORATI') . '" href="http://www.technorati.com/faves?add=' . $socialLink . '" target="_blank"><span>' . JText::_('COM_YOORECIPE_ADD_TECHNORATI') . '</span></a></li>';
		}
		
		$html[] = '<li class="clr"></li>';
		$html[] = '</ul>';
		$html[] = '</div><br/>';
		$html[] = '<div class="clear"></div>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate the google +1 code
	 */
	private static function getGooglePlus1($params)
	{
		$html = array();
		
		$doc 	= JFactory::getDocument();
		$lang 	= JFactory::getLanguage();
		$locales = $lang->getTag();
		$doc->addScriptDeclaration("
			window.___gcfg = {
			lang: '" . $lang->getTag() . "',
			parsetags: 'onload'
			};
		");
		
		$doc->addScript("https://apis.google.com/js/plusone.js");
		
		$size = $params->get('bookmark_size');
		$html[] = '<g:plusone size="'. $size . '"></g:plusone>';

		return implode("\n", $html);
	}
	
	/**
	 * Returns the parameter value of a given $paramName.
	 * Priority 1: menu parameters, Fallback: global parameters, fallback: default value
	 */
	public static function getParamValue($menuParams, $globalParams, $paramName, $default)
	{
		if (isset($menuParams)) {
		
			$paramVal = $menuParams->get($paramName);
			if (isset($paramVal)) {
				return ($menuParams->get($paramName) == 'use_global') ? $globalParams->get($paramName) : $menuParams->get($paramName);
			} else {
				return $globalParams->get($paramName);
			}
		}
		else {
			return $globalParams->get($paramName, $default);
		}
	}
	
	/**
	 * Automatically numbers paragraph contained in recipe directions
	 */
	public static function formatParagraphs($recipeDirections)
	{
		$result = '<ol class="numbering">';
		$regex = '#<p(.*)(.*)\>#iU';
		
		$matches = array();
		while (preg_match( $regex, $recipeDirections, $matches )) {
			$tag = $matches[0];
			$replaceText = '<li class="numbering"><div>';
			$recipeDirections = str_replace( $tag, $replaceText, $recipeDirections);
		}
		
		$regex = '/<\/p>/';
		
		$matches = array();
		while (preg_match( $regex, $recipeDirections, $matches )) {
			$tag = $matches[0];
			$replaceText = '</div></li>';
			$recipeDirections = str_replace( $tag, $replaceText, $recipeDirections);
		}
		
		$result .= $recipeDirections . '</ol>';
		return $result;
	}
	
	/**
	 * Notify a user a recipe has been created
	 */
	public static function sendMailToUserOnCreate($recipe, $update = 0)
	{
		jimport('joomla.mail.helper');
		jimport( 'joomla.utilities.utility' );
		
		$app		= JFactory::getApplication();
		$SiteName	= $app->getCfg('sitename');
		$MailFrom	= $app->getCfg('mailfrom');
		$FromName	= $app->getCfg('fromname');

		$email		= $recipe->author_email;
		$sender		= $FromName;
		$from		= $MailFrom;
		$subject	= JText::sprintf('COM_YOORECIPE_RECIPE_CREATION_SUBJECT', $recipe->title);

		// Check for a valid to address
		$error	= false;
		if (! $email  || ! JMailHelper::isEmailAddress($email))
		{
			$error	= JText::sprintf('COM_YOORECIPE_CREATION_EMAIL_NOT_SENT', $recipe->title);
			JError::raiseWarning(0, $error);
		}

		// Check 'From' address is valid
		if (! $from || ! JMailHelper::isEmailAddress($from))
		{
			$error	= JText::sprintf('COM_YOORECIPE_CREATION_EMAIL_NOT_SENT', $recipe->title);
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return;
		}

		// Build the message to send
		$body	= JText::sprintf('COM_YOORECIPE_RECIPE_CREATION_BODY', $recipe->author_name, $recipe->title, $FromName);

		// Clean the email data
		$subject = JMailHelper::cleanSubject($subject);
		$body	 = JMailHelper::cleanBody($body);
		$sender	 = JMailHelper::cleanAddress($sender);

		$mailer 	= JFactory::getMailer();
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		
		// Send the email
		$send = $mailer->Send();
		if ( $send !== true )
		{
			JError::raiseNotice(500, JText::sprintf('COM_YOORECIPE_CREATION_EMAIL_NOT_SENT', $recipe->title));
		}
	}
	
	/**
	 * Notify admin on recipe update
	 */
	public static function sendRecipeUpdateNotificationToAdmin($recipe) {
	
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		$sender = array( 
			$config->get('config.mailfrom'),
			$config->get('config.fromname')
		);
		
		// Build message body
		$body = array();
		$body[] = '<h2>'. JText::sprintf('COM_YOORECIPE_RECIPE_AWAITS_MODERATION', $recipe->title ) . '</h2>';
		$body[] = '| <a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=yoorecipes.validate&cid[]='.$recipe->id.'">' . JText::_('COM_YOORECIPE_VALIDATE') . '</a> | ';
		$body[] = '<a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=yoorecipes.publish&cid[]='.$recipe->id.'">' . JText::_('COM_YOORECIPE_PUBLISH') . '</a> |<br/>';
		$body[] = '<div><ul>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_TITLE') . ':</strong>'.$recipe->title.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_DESCRIPTION_LABEL') . ':</strong>'.$recipe->description.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_PREPARATION_LABEL') . ':</strong>'.$recipe->preparation.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_NB_PERSONS_LABEL') . ':</strong>'.$recipe->nb_persons . '</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_DIFFICULTY_LABEL'). ':</strong>'.$recipe->difficulty.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_RECIPES_COST') . ':</strong>'.$recipe->cost.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_PREPARATION_TIME_LABEL') .':</strong>'.$recipe->preparation_time . '</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_COOK_TIME_LABEL') . ':</strong>'.$recipe->cook_time.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_YOORECIPE_WAIT_TIME_LABEL') . ':</strong>'.$recipe->wait_time.'</li>';
		$body[] = '<li><strong>Ingredients:</strong>';
	
		foreach ($recipe->ingredients as $ingredient) {
			$body[] = round($ingredient->quantity, 2) . ' ' . JText::_($ingredient->unit) . ' ' . htmlspecialchars($ingredient->description) . ', ';
		}
		
		$body[] = '</li><li><strong>Picture:</strong><br/><img src="' . $recipe->picture . '" alt="'.$recipe->alias.'"/></ul></div><br/>';
		$body[] = '| <a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=yoorecipes.validate&cid[]='.$recipe->id.'">' . JText::_('COM_YOORECIPE_VALIDATE') . '</a> | ';
		$body[] = '<a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=yoorecipes.publish&cid[]='.$recipe->id.'">' . JText::_('COM_YOORECIPE_PUBLISH'). '</a> |';
		
		// Prepare mailer
		$mailer->setSender($sender);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject(JText::_('COM_YOORECIPE_NEW_RECIPE_TO_VALIDATE') . ': ' . $recipe->title);
		$mailer->setBody(implode("\n", $body));
		
		// Add recipient
		$recipient = $config->get('config.mailfrom');
		$mailer->addRecipient($recipient);
		
		// Send
		$send = $mailer->Send();
		if ( $send !== true ) {
			JError::raiseNotice(500, JText::sprintf('COM_YOORECIPE_CREATION_EMAIL_NOT_SENT', $recipe->title));
		}
	}
	
	/**
	 * Send an email to the admin when a user submit a comment
	 */
	public static function sendMailToAdminOnSubmitComment($comment, $recipe)
	{
		jimport('joomla.mail.helper');
		jimport('joomla.utilities.utility');
		
		$app				= JFactory::getApplication();
		$yooRecipeparams	= JComponentHelper::getParams('com_yoorecipe');
		$SiteName			= $app->getCfg('sitename');
		$MailFrom			= $app->getCfg('mailfrom');
		$FromName			= $app->getCfg('fromname');

		$mailer 	= JFactory::getMailer();
		$email		= $MailFrom;
		$sender		= $FromName;
		$from		= $MailFrom;

		// Check for a valid to address
		$error	= false;
		if (! $email  || ! JMailHelper::isEmailAddress($email))
		{
			$error	= JText::_('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT');
			JError::raiseWarning(0, $error);
		}

		// Check for a valid from address
		if (! $from || ! JMailHelper::isEmailAddress($from))
		{
			$error	= JText::_('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT');
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return;
		}
		
		// Build message body
		$body = array();
		$body[] = JText::_('COM_YOORECIPE_NEW_COMMENT') . " ";
		if ($yooRecipeparams->get('auto_publish_comment', 1 )){
			$body[] = JText::_('COM_YOORECIPE_NEW_COMMENT_PUBLISHED') . ' ';
		}
		else {
			$body[] = JText::_('COM_YOORECIPE_NEW_COMMENT_TO_MODERATE') . ' ';
		}
		
		$url = Juri::root() . JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->id . ':' . $recipe->alias) , false) . '#comments';
		$body[] = '<a href="'. $url .'">' . JText::sprintf($recipe->title). '</a>';
		
		if (!($yooRecipeparams->get('auto_publish_comment', 1 ))){
			$body[] = '<br/><a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=comments.publish&cid[]='.$comment->id.'">' . JText::_('COM_YOORECIPE_PUBLISH'). '</a>';
		}
		$body[] = '<div><ul>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_COMMENT_AUTHOR') . ':</strong>&nbsp;'.$comment->author.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_COMMENT_NOTE') . ':</strong>&nbsp;'.$comment->note.'/5</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_COMMENT_EMAIL') . ':</strong>&nbsp;'.$comment->email.'</li>';
		$body[] = '<li><strong>' . JText::_('COM_YOORECIPE_COMMENT_COMMENT') . ':</strong>&nbsp;'.$comment->comment.'</li>';
		$body[] = '</ul></div>';
		if (!($yooRecipeparams->get('auto_publish_comment', 1 ))){
			$body[] = '<a href="'. JUri::root() . 'administrator/index.php?option=com_yoorecipe&task=comments.publish&cid[]='.$comment->id.'">' . JText::_('COM_YOORECIPE_PUBLISH'). '</a>';
		}
		
		// Clean the email data
		$subject = JMailHelper::cleanSubject(JText::_('COM_YOORECIPE_RECIPE_VALIDATION_COMMENT_SUBJECT'));
		$body	 = JMailHelper::cleanBody(implode("\n", $body));
		$sender	 = JMailHelper::cleanAddress($sender);
		
		// Prepare mailer
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject(JText::_('COM_YOORECIPE_RECIPE_VALIDATION_COMMENT_SUBJECT'));
		$mailer->setBody($body);
		
		$send =& $mailer->Send();
		if ( $send !== true ) // Send the email
		{
			JError::raiseNotice(500, JText:: _ ('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT'));
		}
		return;
	}
	
	/**
	 * Send an email to the author of the recipe when a user comment it.
	 */
	public static function sendMailToAuthorOnSubmitComment($comment, $recipe)
	{
		jimport('joomla.mail.helper');
		jimport('joomla.utilities.utility');
		
		$app				= JFactory::getApplication();
		$yooRecipeparams	= JComponentHelper::getParams('com_yoorecipe');
		$SiteName			= $app->getCfg('sitename');
		$MailFrom			= $app->getCfg('mailfrom');
		$FromName			= $app->getCfg('fromname');

		$recipeAuthor = new JUser($recipe->created_by);
		$mailer 	= JFactory::getMailer();
		$email		= $recipeAuthor->email;
		$sender		= $FromName;
		$from		= $MailFrom;

		// Check for a valid to address
		$error	= false;
		if (! $email  || ! JMailHelper::isEmailAddress($email))
		{
			$error	= JText::_('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT');
			JError::raiseWarning(0, $error);
		}

		// Check for a valid from address
		if (! $from || ! JMailHelper::isEmailAddress($from))
		{
			$error	= JText::_('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT');
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return;
		}
		
		// Build message body
		$url = Juri::root() . JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->id . ':' . $recipe->alias) , false) . '#comments';
		$body = array();
		$body[] = JText::_('COM_YOORECIPE_NEW_COMMENT_TO_AUTHOR') . ' <a href="'. $url .'">' . JText::sprintf($recipe->title). '</a><br/>';
		if (!$yooRecipeparams->get('auto_publish_comment', 1)){
			$body[] = JText::_('COM_YOORECIPE_NEW_COMMENT_UNDER_MODERATION');
		}
		
		// Clean the email data
		$subject = JMailHelper::cleanSubject(JText::_('COM_YOORECIPE_NEW_COMMENT_UNDER_MODERATION'));
		$body	 = JMailHelper::cleanBody(implode("\n", $body));
		$sender	 = JMailHelper::cleanAddress($sender);
		
		// Prepare mailer
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject(JText::_('COM_YOORECIPE_RECIPE_NOTIFICATION_COMMENT_TO_AUTHOR'));
		$mailer->setBody($body);
		
		// Send the email
		$send =& $mailer->Send();
		if ( $send !== true ) // Send the email
		{
			JError::raiseNotice(500, JText:: _ ('COM_YOORECIPE_MODERATOR_EMAIL_NOT_SENT'));
		}
		return;
	}	
	
	/**
	 * Build a recipe object from data contained in post
	 */
	public static function buildRecipeFromRequest($params) {
	
		jimport( 'joomla.error.error' );
		
		// Get input
		$input 	= JFactory::getApplication()->input;
		$jform 	= $input->get('jform', '', 'ARRAY');
		
		// FIRST TAB - DETAILS
		$prep_days 		= $input->get('prep_days', '0', 'INT');
		$prep_hours   	= $input->get('prep_hours', '0', 'INT');
		$prep_minutes 	= $input->get('prep_minutes', '0', 'INT');
		
		$cook_days		= $input->get('cook_days', '0', 'INT');
		$cook_hours		= $input->get('cook_hours', '0', 'INT');
		$cook_minutes	= $input->get('cook_minutes', '0', 'INT');
		
		$wait_days		= $input->get('wait_days', '0', 'INT');
		$wait_hours		= $input->get('wait_hours', '0', 'INT');
		$wait_minutes	= $input->get('wait_minutes', '0', 'INT');
		
		$created_by		= $input->get('created_by', '0', 'INT');
		
		// Init variables
		$recipe = new stdClass();
		$user = JFactory::getUser();
		
		$recipe->id					= $jform['id'];
		$recipe->title				= htmlspecialchars($jform['title']);
		$recipe->description		= $jform['description'];
		$recipe->alias 				= JFilterOutput::stringURLSafe($recipe->title);
		$recipe->category_id 		= $jform['category_id'];
		$recipe->season_id 			= $jform['season_id'];
		$recipe->preparation 		= $jform['preparation'];
		$recipe->nb_persons 		= $jform['nb_persons'];
		$recipe->servings_type 		= $jform['servings_type'];
		
		// Recipe fields
		if (isset($jform['price'])) :
			$recipe->price		 	= str_replace(",", ".", $jform['price']);
		endif;
		if (isset($jform['difficulty'])) :
			$recipe->difficulty 		= $jform['difficulty'];
		endif;
		if (isset($jform['cost'])) :
			$recipe->cost				= $jform['cost'];
		endif;
		
		if (isset($prep_days) || isset($prep_hours) || isset($prep_minutes)) :
			$recipe->preparation_time 	= $prep_days * 1440 + $prep_hours * 60 + $prep_minutes; 
		endif;
		if (isset($cook_days) || isset($cook_hours) || isset($cook_minutes)) :
			$recipe->cook_time 	= $cook_days * 1440 + $cook_hours * 60 + $cook_minutes; 
		endif;
		if (isset($wait_days) || isset($wait_hours) || isset($wait_minutes)) :
			$recipe->wait_time 	= $wait_days * 1440 + $wait_hours * 60 + $wait_minutes; 
		endif;
		
		// 3rd TAB: PUBLISHING OPTIONS
		if (!empty($created_by)) {
			$recipe->created_by	= $created_by;
		} else {
			$recipe->created_by	= $user->id;
		}
		
		if (isset($jform['access'])) {
			$recipe->access = $jform['access'];
		}
		if (isset($jform['publish_up'])) {
			$recipe->publish_up = $jform['publish_up'];
		}
		if (isset($jform['publish_down'])) {
			$recipe->publish_down = $jform['publish_down'];
		}
		if (isset($jform['nb_views'])) {
			$recipe->nb_views = $jform['nb_views'];
		}
		
		// 4TH TAB: NUTRITION FACTS
		if (isset($jform['diet'])) :
			$recipe->diet 			= $jform['diet'];
		endif;
		if (isset($jform['gluten_free'])) :
			$recipe->gluten_free 	= $jform['gluten_free'];
		endif;
		if (isset($jform['veggie'])) :
			$recipe->veggie 		= $jform['veggie'];
		endif;
		if (isset($jform['lactose_free'])) :
			$recipe->lactose_free 	= $jform['lactose_free'];
		endif;
		
		if (isset($jform['kcal'])) :
			$recipe->kcal 			= $jform['kcal'];
		endif;
		if (isset($jform['carbs'])) :
			$recipe->carbs 			=  str_replace(",", ".", $jform['carbs']);
		endif;
		if (isset($jform['fat'])) :
			$recipe->fat 			=  str_replace(",", ".", $jform['fat']);
		endif;
		if (isset($jform['saturated_fat'])) :
			$recipe->saturated_fat 	=  str_replace(",", ".", $jform['saturated_fat']);
		endif;
		if (isset($jform['proteins'])) :
			$recipe->proteins 		=  str_replace(",", ".", $jform['proteins']);
		endif;
		if (isset($jform['fibers'])) :
			$recipe->fibers 		=  str_replace(",", ".", $jform['fibers']);
		endif;
		if (isset($jform['salt'])) :
			$recipe->salt 			=  str_replace(",", ".", $jform['salt']);
		endif;

		// 5th TAB: SEO
		if (isset($jform['metakey'])) :
			$recipe->metakey 			=  $jform['metakey'];
		endif;
		if (isset($jform['metadata'])) :
			$recipe->metadata 			=  $jform['metadata'];
		endif;
		
		// Status parameters
		if ($params->get('auto_publish', 1)) {
			$recipe->published 		= 1;
		} else if (isset($jform['published'])) {
			$recipe->published 		= $jform['published'];
		}
		
		if ($params->get('auto_validate', 0)) {
			$recipe->validated 		= 1;
		} else if (isset($jform['validated'])) {
			$recipe->validated 		= $jform['validated'];
		}
		
		if (isset($jform['featured'])) {
			$recipe->featured 		= $jform['featured'];
		}
		
		if (isset($jform['language'])) {
			$recipe->language = $jform['language'];
		}
		
		
		
		// Retrieve ingredients
		$recipe->ingredients = JHtmlYooRecipeUtils::buildIngredients();
		
		// Retrieve tags
		$recipe->tags = JHtmlYooRecipeUtils::buildTags();
		
		// Check uploaded picture and video
		$picturePath = $jform['picture'];
		
		if (isset($jform['picture']) && !empty($jform['picture'])) {
			$matches = array();
			preg_match('/.*images\/com_yoorecipe\/(.*)/i', $jform['picture'], $matches);
			$picturePath = 'images/com_yoorecipe/' . $matches[1];
		}
		$recipe->picture	= $picturePath;
		$recipe->video 		= $jform['video'];
		
		// TODO improve exception handling
		if ($recipe->ingredients == false) {
			return false;
		}
		
		return $recipe;
	}
	
	/**
	 * Function to build ingredients objects from POST data
	 */
	private static function buildIngredients() {
	
		// Retrieve ingredients
		$ingredients = array();
		
		$input 			= JFactory::getApplication()->input;
		$quantities 	= $input->get('quantity', array(), 'ARRAY');
		$units 			= $input->get('unit', array(), 'ARRAY');
		$ingr_descriptions 	= $input->get('ingr_description', array(), 'ARRAY');
		
		if (isset($quantities) && isset($units) && isset($ingr_descriptions)) {
			
			$ingrIds	= $input->get('ingrId', array(), 'ARRAY');
			$groups	 	= $input->get('group', array(), 'ARRAY');
			
			for ($i = 0 ; $i < count($quantities) ; $i++) {
				
				// Change decimal separator if needed
				$qty = str_replace(',', '.', $quantities[$i]);
				$qtyToNum;
				
				if (strpos($qty, '/') == false) {
					$qtyToNum = $qty;
				} else {
				
					// Turn fractions into decimal value if applicable
					$fraction = array('whole' => 0);
					
					preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $qty, $fraction);
					if ($fraction['denominator'] != 0) {
						$qtyToNum = $fraction['whole'] + $fraction['numerator']/$fraction['denominator'];
					} else {
						$qtyToNum = 0;
					}
				}
				
				if (!empty($ingr_descriptions[$i])) {
				
					$crtIngredient 				= new stdClass();
					// $crtIngredient->id 			= (int) $ingrIds[$i];
					$crtIngredient->quantity 	= (is_numeric($qtyToNum)) ? $qtyToNum : 0; // Allow quantities to be empty
					$crtIngredient->unit 		= $units[$i];
					$crtIngredient->description = htmlspecialchars($ingr_descriptions[$i]);
					$crtIngredient->group_id	= $groups[$i];
					
					$ingredients[] = $crtIngredient;
				}
			}	
		}
		else {
			Jerror::raiseWarning(500, JText::_('COM_YOORECIPE_NO_INGREDIENTS'));
			return false;
		}
		
		return $ingredients;
	}
	
	/**
	 * Function to build tags objects from post data
	 */
	private static function buildTags() {
	
		// Retrieve tags
		$tags = array();
		
		$input 		= JFactory::getApplication()->input;
		$jform		= $input->get('jform', '', 'ARRAY');
		$withTags 	= $input->get('withTags', array(), 'ARRAY');
		
		if (isset($withTags)) {
			
			for ($i = 0 ; $i < count($withTags) ; $i++) {
			
				$crtTag	 			= new stdClass();
				$crtTag->recipe_id 	= $jform['id'];
				$crtTag->tag_value 	= $withTags[$i];
				
				$tags[] = $crtTag;
			}
		}
		return $tags;
	}

	/**
	* Generate a browsable category list
	*/
	public static function generateCategoriesList($categories) {
	
		$html = array();
		$html[] = '<div>' . JText::_('COM_YOORECIPE_NO_RECIPE_FOUND') . '</div>';
		$html[] = '<div>' . JText::_('COM_YOORECIPE_BROWSE_CATEGORIES_LIST') . '</div>';
		$html[] = '<select onchange="window.location.href = this.value">';
	
		foreach ($categories as $category) :
			$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getcategoryroute', $category->slug) , false);
			$html[] = '<option value="' . $url . '">' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->level-1) . htmlspecialchars($category->title) . '</option>';
		endforeach;		
	
		$html[] = '</select>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate add recipe button
	 */
	public static function generateAddRecipeButton($first = false){
		
		$html = array();
		
		$user = JFactory::getUser();
		if ($user->guest != 1 && ($user->authorise('core.edit', 'com_yoorecipe') || ($user->authorise('core.edit.own', 'com_yoorecipe')))) {
		
			if ($first){
				$html[] = '<div>' . JText::_('COM_YOORECIPE_FIRST_ADD_RECIPE') . '</div>';
			}
			$html[] = '<div class="form-add-button">';
			$html[] = '<button type="button" class="btn" onclick="Joomla.submitbutton(\'addRecipe\')">' . JText::_('COM_YOORECIPE_ADD') . '</button>';
			$html[] = '</div>';
		}
		return implode("\n", $html);
	}
	
	/**
	 * Generate a mosaic of all sub-categories for the current category
	 */
	public static function generateSubCategoriesMosaic($subcategories, $show_sub_categories_picture){
		
		$yooRecipeparams		= JComponentHelper::getParams('com_yoorecipe');
		$use_watermark			= $yooRecipeparams->get('use_watermark', 1);
		$sub_categories_width	= $yooRecipeparams->get('sub_categories_width', 130);
		$sub_categories_height	= $yooRecipeparams->get('sub_categories_height', 100);
		
		// Get Joomla version
		$version = new JVersion;
		$joomla = $version->getShortVersion(); 
		
		$is_joomla3 = version_compare($joomla, '3.0', '>=') ? true:false;
		
		if ($is_joomla3)
		{
			$html = array();
			$cnt = 0;
			$nb_cols = 4;

			$html[] = '<div class="items-row cols-'.$nb_cols.' row-0 row-fluid">';
			foreach($subcategories as $category) :
				
				if ($cnt % $nb_cols == 0 && $cnt > 0) {
					$html[] = '</div>';
					$html[] = '<div class="items-row cols-'.$nb_cols.' row-0 row-fluid">';
				}
				
				$catUrl = JRoute::_('index.php?option=com_yoorecipe&task=viewCategory&id='. $category->slug);
				
				$params = new JRegistry();
				$params->loadString($category->params);
				
				$picturePath = $params->get('image', 'media/com_yoorecipe/images/no-image.jpg');
				if ($show_sub_categories_picture && $use_watermark) {
					$picturePath = JHtmlYooRecipeUtils::watermarkImage($picturePath, JText::_('COM_YOORECIPE_COPYRIGHT_LABEL') . ' ' . juri::base());
				}
				
				$html[] = '<div class="item span'.(int)12/$nb_cols.'">';
				if ($show_sub_categories_picture) {
					$html[] = '<a href="'. $catUrl . '"><img class="recipe-picture" width="' . $sub_categories_width. '" height="' . $sub_categories_height . '" src="' . $picturePath . '" alt="' . $category->title . '"/></a>';
				}
				$html[] = '<div class="clear"><a href="'. $catUrl . '">'.$category->title . '</a>&nbsp;(' . $category->nb_recipes . ')</div></li>';
				
				$html[] = '</div>';
				$cnt++;
			endforeach; 
			$html[] = '</div>';
			return implode("\n", $html);
		}
		else {
			$nbItemsPerLine = 4;
			$ul1 			= '<ul class="width25">';
			$ul2 			= '<ul class="width25">';
			$ul3 			= '<ul class="width25">';
			$ul4 			= '<ul class="width25">';
			
			$cnt = 0;
			foreach($subcategories as $category) :
				
				$catUrl = JRoute::_('index.php?option=com_yoorecipe&task=viewCategory&id='. $category->slug);
				
				$params = new JRegistry();
				$params->loadString($category->params);
				
				$picturePath = $params->get('image', 'media/com_yoorecipe/images/no-image.jpg');
				if ($show_sub_categories_picture && $use_watermark) {
					$picturePath = JHtmlYooRecipeUtils::watermarkImage($picturePath, JText::_('COM_YOORECIPE_COPYRIGHT_LABEL') . ' ' . juri::base());
				}
				switch ($cnt % 4) {				
					
					case 0:
					default:
						$ul1 	.= '<li>';
						if ($show_sub_categories_picture) {
							$ul1	.= '<a href="'. $catUrl . '"><img class="recipe-picture" width="' . $sub_categories_width. '" height="' . $sub_categories_height . '" src="' . $picturePath . '" alt="' . $category->title . '"/></a>';
						}
						$ul1 	.= '<div class="clear"><a href="'. $catUrl . '">'.$category->title . '</a>&nbsp;(' . $category->nb_recipes . ')</div></li>';
					break;
					case 1:					
						$ul2 	.= '<li>';
						if ($show_sub_categories_picture) {
							$ul2	.= '<a href="'. $catUrl . '"><img class="recipe-picture" width="' . $sub_categories_width. '" height="' . $sub_categories_height . '" src="' . $picturePath . '" alt="' . $category->title . '"/></a>';
						}
						$ul2 	.= '<div class="clear"><a href="'. $catUrl . '">'.$category->title . '</a>&nbsp;(' . $category->nb_recipes . ')</div></li>';
					break;
					case 2:					
						$ul3 	.= '<li>';
						if ($show_sub_categories_picture) {
							$ul3	.= '<a href="'. $catUrl . '"><a href="'. $catUrl . '"><img class="recipe-picture" width="' . $sub_categories_width. '" height="' . $sub_categories_height . '" src="' . $picturePath . '" alt="' . $category->title . '"/></a>';
						}
						$ul3 	.= '<div class="clear"><a href="'. $catUrl . '">'.$category->title . '</a>&nbsp;(' . $category->nb_recipes . ')</div></li>';
					break;
					case 3:					
						$ul4 	.= '<li>';
						if ($show_sub_categories_picture) {
							$ul4	.= '<a href="'. $catUrl . '"><img class="recipe-picture" width="' . $sub_categories_width. '" height="' . $sub_categories_height . '" src="' . $picturePath . '" alt="' . $category->title . '"/></a>';
						}
						$ul4 	.= '<div class="clear"><a href="'. $catUrl . '">'.$category->title . '</a>&nbsp;(' . $category->nb_recipes . ')</div></li>';
					break;
				}
				$cnt++;
			
			endforeach; 
			
			$ul1 .= '</ul>';
			$ul2 .= '</ul>';
			$ul3 .= '</ul>';
			$ul4 .= '</ul>';
			
			return $ul1.$ul2.$ul3.$ul4;
		}
	}
	
	/**
	 * Function to create pagination
	 */
	public static function generatePagination($pagination) {

		require_once JPATH_COMPONENT.'/helpers/html/yoorecipepagination.php';
		
		$html = array();
	
		$html[] = '<div class="pagination">';
		$html[] = $pagination->getPagesLinks();
		$html[] = '<span id="comyoorecipe-items-per-page">' . JText::_('COM_YOORECIPE_NB_RECIPES_PER_PAGE') . JHtmlYooRecipePagination::getLimitBox($pagination) . '</span>';
		$html[] = '<div class="clear"></div></div>';
	
		return implode("\n", $html);
	}
	
	/**
	 * Function to generate edit and delete buttons
	 */
	public static function generateManagementPanel($recipe) {
	
		$html = array();
		
		if ($recipe->canEdit || $recipe->canDelete) {
		
			$document = JFactory::getDocument();
			$url = JURI::root().'index.php?option=com_yoorecipe&task=deleteRecipe&format=raw&id='.$recipe->id;
			$script = "window.addEvent('domready', function () {
			
				$('btn_yr_del_".$recipe->id."').addEvent('click', function () {
					var result = confirm('".addslashes(JText::_('COM_YOORECIPE_CONFIRM_DELETE'))."');
					if (result) {
						var x = new Request({
							url: '".$url."', 
							method: 'post', 
							onRequest: function() { 
								var div = new Element('div', {'id': 'tmpAjaxLoading'});
								div.addClass('ajax-loading');
								div.addClass('ajax-centered');
								div.inject('yr_btns_".$recipe->id."');
							},
							onSuccess: function(result){
								if (result.match('^'+'NOK')) {
									alert(result.substr(4,result.length-1));
									$('tmpAjaxLoading').dispose();
								} else {
									$('div_recipe_".$recipe->id."').set('tween', {
										duration: 600
									}).fade('out').get('tween').chain(function() {
										$('div_recipe_".$recipe->id."').tween('height',0);
									}).chain(function () {
										$('div_recipe_".$recipe->id."').dispose();
									});
								}
							},
							onFailure: function(){
								alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_FORBIDDEN_DELETE_OPERATION')) ." ');
							}                
						}).send();
					}
				});
			});";
			$document->addScriptDeclaration($script);
			
			$editUrl = JRoute::_('index.php?option=com_yoorecipe&view=form&layout=edit&id=' . $recipe->slug);
			$html[] = '<div id="yr_btns_'.$recipe->id.'">';
			if ($recipe->canEdit) {
				$html[] = '<button type="button" class="btn"  onclick="window.location=\'' . $editUrl . '\'">' . JText::_('COM_YOORECIPE_EDIT') . '</button>';
			}
			if ($recipe->canDelete) {
				$html[] = '<button type="button" class="btn"  id="btn_yr_del_'.$recipe->id.'">' . JText::_('COM_YOORECIPE_DELETE') . '</button>';
			}
			$html[] =  '</div>';
		}
		
		return implode("\n", $html);
	}
	
	/**
	 * Function to generate edit and delete buttons
	 */
	public static function generateCrossCategories($recipe) {
	
		$html = array();
		
		$html[] = '<strong>' . JText::_('COM_YOORECIPE_CATEGORY') . ':&nbsp;</strong>';
		$firstElt = true;
		foreach ($recipe->categories as $category) :
			$catUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getcategoryroute', $category->id . ":" . $category->alias) , false);
			if ($firstElt) {
				$html[] = '<span><a href="' . $catUrl .'" title="' . htmlspecialchars($category->title) . '">' . htmlspecialchars($category->title) . '</a></span>';
			} else {
				$html[] = '<span>,&nbsp;<a href="' . $catUrl .'" title="' . htmlspecialchars($category->title) . '">' . htmlspecialchars($category->title) . '</a></span>';
			}
			$firstElt = false;
		endforeach;
		
		return implode("\n", $html);
	}
	
	/**
	 * Returns HTML recipe tags
	 */
	public static function generateRecipeTags($recipe) {
		
		if (count($recipe->tags)==0) {
			return '';
		}
		
		$html = array();
		$html[] = '<strong>' . JText::_('COM_YOORECIPE_TAGS') . ':&nbsp;</strong>';
		
		$firstElt = true;
		foreach ($recipe->tags as $tag) :
			$tagUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getTagRoute', $tag->tag_value) , false);
			if ($firstElt) {
				$html[] = '<span><a href="' . $tagUrl .'" title="' . htmlspecialchars($tag->tag_value) . '">' . htmlspecialchars($tag->tag_value) . '</a></span>';
			} else {
				$html[] = '<span>,&nbsp;<a href="' . $tagUrl .'" title="' . htmlspecialchars($tag->tag_value) . '">' . htmlspecialchars($tag->tag_value) . '</a></span>';
			}
			$firstElt = false;
		endforeach;
		
		return implode("\n", $html);
	}
	
	/**
	 * Function to generate recipe's picture
	 */
	public static function generateRecipePicture($picturePath, $recipeTitle, $isPictureClickable = 1, $url, $width = null) {
	
		// Component Parameters
		$yooRecipeparams 			= JComponentHelper::getParams('com_yoorecipe');
		$use_watermark				= $yooRecipeparams->get('use_watermark', 1);
		$useDefaultPicture			= $yooRecipeparams->get('use_default_picture', 1);
		$use_watermark				= $yooRecipeparams->get('use_watermark', 1);
		$thumbnail_width			= ($width == null) ? $yooRecipeparams->get('thumbnail_width', 250) : $width;
		
		$html = array();
		
		if ($picturePath == '' && $useDefaultPicture) {
			$picturePath = 'media/com_yoorecipe/images/no-image.jpg';
		}
		if ($use_watermark) {
			$picturePath = self::watermarkImage($picturePath, 'Copyright ' . juri::base());
		}
		
		if ($picturePath != '') {
			
			$html[] = '<div id="div-recipe-result-picture">';
			if ($isPictureClickable) {
				$html[] = '<a href="' . $url  .'" title="' . htmlspecialchars ($recipeTitle) . '">';
				$html[] = '	<img class="recipe-picture blog-picture" src="' . $picturePath . '" title="' . htmlspecialchars ($recipeTitle) . '" alt="' . htmlspecialchars ($recipeTitle) . '" style="width:' . $thumbnail_width. 'px"/>';
				$html[] = '</a>';
			}
			else {
				$html[] = '<img class="recipe-picture blog-picture" src="' . $picturePath . '" title="' . htmlspecialchars($recipeTitle) . '" alt="' . htmlspecialchars ($recipeTitle) . '" style="width:' . $thumbnail_width. 'px"/>';
			}
			$html[] = '</div>';
		}
	
		return implode("\n", $html);
	}
	
	/**
	 * Turn a decimal value into a fraction
	 */
	public static function decimalToFraction($decimal) {
		$elts = explode('.', $decimal);
		if (count($elts) > 1) {
			if ($elts[0] == '0') {
				$value = JHtmlYooRecipeUtils::getFraction('0.' . $elts[1]);
			} else {
				if ($elts[1] == '0') { 
					$value = $elts[0] . JHtmlYooRecipeUtils::getFraction('0.' . $elts[1]);
				}
				else {
					$value = $elts[0] . ' ' .JHtmlYooRecipeUtils::getFraction('0.' . $elts[1]);
				}
			}
			return $value;
		}
		else {
			return $decimal;
		}
	}
	
	/**
	* Turns a decimal value into a fraction
	*/
	private static function getFraction($value) {
		
		if ($value == '0.0') return '';
		if ($value == '0.5') return '1/2';
		if ($value == '0.33') return '1/3';
		if ($value == '0.67') return '2/3';
		if ($value == '0.25') return '1/4';
		if ($value == '0.75') return '3/4';
		if ($value == '0.2') return '1/5';
		if ($value == '0.4') return '2/5';
		if ($value == '0.6') return '3/5';
		if ($value == '0.8') return '4/5';
		if ($value == '0.17') return '1/6';
		if ($value == '0.83') return '5/6';
		if ($value == '0.14') return '1/7';
		if ($value == '0.29') return '2/7';
		if ($value == '0.43') return '3/7';
		if ($value == '0.57') return '4/7';
		if ($value == '0.71') return '5/7';
		if ($value == '0.86') return '6/7';
		if ($value == '0.13') return '1/8';
		if ($value == '0.38') return '3/8';
		if ($value == '0.63') return '5/8';
		if ($value == '0.88') return '7/8';
		if ($value == '0.11') return '1/9';
		if ($value == '0.22') return '2/9';
		if ($value == '0.44') return '4/9';
		if ($value == '0.56') return '5/9';
		if ($value == '0.78') return '7/9';
		if ($value == '0.89') return '8/9';
		if ($value == '0.1') return '1/10';
		if ($value == '0.3') return '3/10';
		if ($value == '0.7') return '7/10';
		if ($value == '0.9') return '9/10';
		if ($value == '0.08') return '1/12';
		return $value;
	}
	
	/**
	 * Generate recipe ratings
	 */
	public static function generateRatings($ratings, $canManageComments, $canReportComments) {
	
		$html = array();
		foreach ($ratings as $rating) :
		
			$cssClass = "";
			if (!$rating->published || $rating->abuse) {
				$cssClass = "greyedout";
			}
			
			$html[] = '<div class="recipe-comment-header '.$cssClass.'" id="yoorecipe_comment_'. $rating->id . '">';
				
			for ($j = 1 ; $j <= 5 ; $j++) {
				if ($rating->note >= $j) {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
				}
				else {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
				}
			}
		
			// Link to author if possible
			$html[] = ' ' . JText::_('COM_YOORECIPE_BY') . ' ' .  '<strong>';
			if ($rating->user_id != null && $rating->user_id != 0) {
				$authorUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getuserroute', $rating->user_id) , false);
				$html[] = '<a href="'. $authorUrl . '"><span>' . $rating->author_name . '</span></a>';
			} else {
				$html[] = htmlspecialchars($rating->author);
			}
			$html[] = '</strong>' . ', ' . JText::_('COM_YOORECIPE_ON') . ' ' . JHtml::_('date', $rating->creation_date); 
			$html[] = '<br/><span class="recipe-comment-text small-text">' . htmlspecialchars($rating->comment) . '</span>';
			
			if ($canManageComments) {
				$html[] = '<input type="button" class="btn" onclick="com_yoorecipe_deleteComment(' . $rating->recipe_id . ',' . $rating->id .');" value="' . JText::_('COM_YOORECIPE_DELETE') . '"/>';
			}
			
			// Show under moderation if needed, show report comment otherwise
			if (!$rating->published || $rating->abuse) {
				$html[] = '<img src="media/com_yoorecipe/images/pending.png" alt="" title="'.JText::_('COM_YOORECIPE_PENDING_APPROVAL').'"/>';
			} else if ($canReportComments) {
				$html[] = '<input type="button" class="btn" onclick="com_yoorecipe_reportComment(' . $rating->recipe_id . ',' . $rating->id .');" value="' . JText::_('COM_YOORECIPE_REPORT_AS_OFFENSIVE') . '"/>';
			}
			
			$html[] = '</div>';
		endforeach;
		
		return implode("\n", $html);
	}
	
	/** 
	 * Returns authorization to manage comments
	 */
	public static function canManageComments($user, $authorId) {
		
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		$canManageComments	= $user->authorise('core.admin', 'com_yoorecipe') || $user->authorise('core.edit', 'com_yoorecipe');
		if ($yooRecipeparams->get('comments_manager', 'admin') == 'admin_and_owner') {
			$canManageComments |= ($user->authorise('core.edit.own', 'com_yoorecipe') && $authorId == $user->id);
			}
		return $canManageComments;
	}
	
	/**
	 * Returns authorization to report comments
	 */
	public static function canReportComments($user) {
		
		$yooRecipeparams 	= JComponentHelper::getParams('com_yoorecipe');
		return $user->authorise('recipe.comments.report', 'com_yoorecipe');
	}
	
	/**
	 * Method that generates a video player for the recipe
	 */
	public static function generateVideoPlayer($videoId)
	{
		$yooRecipeParams 	= JComponentHelper::getParams('com_yoorecipe');
		$video_height		= $yooRecipeParams->get('video_height', 360);
		$video_width		= $yooRecipeParams->get('video_width', 640);
		
		$html = array();
		$html[] = '<object style="height: ' . $video_height . 'px; width: ' . $video_width . 'px">';
		$html[] = '<param name="movie" value="https://www.youtube.com/v/' . $videoId . '?version=3&feature=player_embedded&rel=0">';
		$html[] = '<param name="allowFullScreen" value="true">';
		$html[] = '<param name="allowScriptAccess" value="always">';
		$html[] = '<embed src="https://www.youtube.com/v/' . $videoId . '?version=3&feature=player_embedded&rel=0" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="'. $video_width .'" height="'. $video_height . '"></object>';

		return implode("\n", $html);
	}
	
	/**
	 * Generate cross categories, recipe tags, difficulty, cost, prep time, cook time, wait time
	 */
	public static function generateRecipeActions($recipe, $yooRecipeparams, $canShowCategoryTitle, $canShowDifficulty, $canShowCost, $canShowPreparationTime, $canShowCookTime, $canShowWaitTime)
	{
		$user 		= JFactory::getUser();
		$use_tags 	= $yooRecipeparams->get('use_tags', 1);
		
		$html = array();
		$html[] = '<ul class="yoorecipe-infos">';
		$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false);
		
		if ($canShowCategoryTitle) :
			$html[] = '<li>' . JHtml::_('yoorecipeutils.generateCrossCategories', $recipe) . '</li>';
		endif;
		
		// Generate Recipe tags
		if ($use_tags) {
			$html[] = '<li>' . JHtml::_('yoorecipeutils.generateRecipeTags', $recipe) . '</li>';
		}
		
		if ($canShowDifficulty) :
			
			$html[] = '<li><strong>' . JText::_('COM_YOORECIPE_RECIPES_DIFFICULTY') . ':&nbsp;</strong>';
			for ($j = 1 ; $j <= 4; $j++) {
					
				if ($recipe->difficulty >= $j) {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
				}
				else {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
				}
			}
			$html[] = '</li>';
		endif;
		
		if ($canShowCost) :
			
			$html[] = '<li><strong>' . '  ' . JText::_('COM_YOORECIPE_RECIPES_COST') . ':&nbsp;</strong>';
			for ($j = 1 ; $j <= 3 ; $j++) {
				if ($recipe->cost >= $j) {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
				}
				else {
					$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
				}
			}
			$html[] = '</li>';
		endif;
		
		if ($canShowPreparationTime && $recipe->preparation_time != 0) :
			
			$html[] = '<li><strong>' . JText::_('COM_YOORECIPE_RECIPES_PREPARATION_TIME') . ':&nbsp;</strong>';
			$html[] = '<span>' . JHtml::_('yoorecipeutils.formattime', $recipe->preparation_time) . '</span></li>';
		endif;

		if ($canShowCookTime && $recipe->cook_time != 0) :
		
			$html[] = '<li><strong>' . JText::_('COM_YOORECIPE_RECIPES_COOK_TIME') . ':&nbsp;</strong>';
			$html[] = '<span>' . JHtml::_('yoorecipeutils.formattime', $recipe->cook_time) . '</span></li>';
		endif;
		
		if ($canShowWaitTime && $recipe->wait_time != 0) :
				
			$html[] = '<li><strong>' . JText::_('COM_YOORECIPE_RECIPES_WAIT_TIME') . ':&nbsp;</strong>';
			$html[] = '<span>' . JHtml::_('yoorecipeutils.formattime', $recipe->wait_time) . '</span></li>';
		endif;
		$html[] = '</ul>';
			
		return implode("\n", $html);
	}
	
	/**
	 * Generate recipe average rating
	 */
	public static function generateRecipeRatings($recipe, $useGoogleRecipe, $ratingStyle)
	{
		$html = array();
		if ($recipe->note != null)
		{
			$html[] = JText::_('COM_YOORECIPE_RECIPE_NOTE') . '&nbsp;';
			if ($useGoogleRecipe) {
			
				$html[] = '<span class="rating"><span class="average">' . $recipe->note . '</span>/5</span>';
			} else if ($ratingStyle == 'grade') {
				$html[] = $recipe->note . '/5';
			}
			if ($ratingStyle == 'stars') {
				$rating = round($recipe->note);
				for ($j = 1 ; $j <= 5 ; $j++) {
					if ($rating >= $j) {
						$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" title="' . $recipe->note . '/5" alt=""/>';
					}
					else {
						$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" title="' . $recipe->note . '/5" alt=""/>';
					}
				}
			}
		}
		return implode("\n", $html);
	}
	
	/**
	* Returns recipe nutritional information
	*/
	public static function generateRecipeNutritionalInfo($recipe)
	{
		
		if ($recipe->kcal == 0 && $recipe->carbs == 0 && $recipe->proteins == 0 && $recipe->fat == 0 && $recipe->saturated_fat == 0 && 
				$recipe->fibers == 0 && $recipe->salt == 0 && !$recipe->diet && !$recipe->veggie && !$recipe->gluten_free && !$recipe->lactose_free) {
			return '';
		}
		
		$html = array();
		$html[] = '<div><h3>' . JText::_('COM_YOORECIPE_RECIPES_NUTRITION_FACTS') . '</h3>';
		
		$calories	= $recipe->kcal == 0 ? '' : $recipe->kcal . ' ' . JText::_('COM_YOORECIPE_CALORIES');
		$carbs		= $recipe->carbs == 0 ? '' : $recipe->carbs . JText::_('COM_YOORECIPE_GRAMS_SYMBOL');
		$proteins	= $recipe->proteins == 0 ? '' : $recipe->proteins . JText::_('COM_YOORECIPE_GRAMS_SYMBOL');
		$fat 		= $recipe->fat == 0 ? '' : $recipe->fat . JText::_('COM_YOORECIPE_GRAMS_SYMBOL');
		$sfat		= $recipe->saturated_fat == 0 ? '' : $recipe->saturated_fat . JText::_('COM_YOORECIPE_GRAMS_SYMBOL');
		$fibers		= $recipe->fibers == 0 ? '' : $recipe->fibers . JText::_('COM_YOORECIPE_GRAMS_SYMBOL');
		$salt		= $recipe->salt == 0 ? '' : $recipe->salt . JText::_('COM_YOORECIPE_MILLIGRAMS_SYMBOL');
		
		$recipeTypeArray = array();
		if ($recipe->diet) {
			$recipeTypeArray[] = JText::_('COM_YOORECIPE_TYPE_DIET');
		}
		if ($recipe->veggie) {
			$recipeTypeArray[] = JText::_('COM_YOORECIPE_TYPE_VEGGIE');
		}
		if ($recipe->gluten_free) {
			$recipeTypeArray[] = strtolower(JText::_('COM_YOORECIPE_YOORECIPE_GLUTEN_FREE_LABEL'));
		}
		if ($recipe->lactose_free) {
			$recipeTypeArray[] = strtolower(JText::_('COM_YOORECIPE_YOORECIPE_LACTOSE_FREE_LABEL'));
		}
		
		if (sizeof($recipeTypeArray) > 0) {
			$html[] = ' (' . implode(", ",$recipeTypeArray) . ')';
		}

		$html[] = '<ul>';
		if ($calories != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_KCAL_LABEL').': '. $calories . '</li>';
		}
		if ($carbs != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_CARBS_LABEL').': '. $carbs . '</li>';
		}
		if ($proteins != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_PROTEINS_LABEL').': '. $proteins . '</li>';
		}
		if ($fat != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_FAT_LABEL').': '. $fat . '</li>';
		}
		if ($sfat != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_SATURATED_FAT_LABEL').': '. $sfat . '</li>';
		}
		if ($fibers != '') {
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_FIBERS_LABEL').': '. $fibers . '</li>';
		}
		if ($salt != '') {	
			$html[] = '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_SALT_LABEL').': '. $salt . '</li>';
		}
		$html[] = '</ul></div>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate alphabet according to a locale
	 */
	public static function generateAlphabet($locale)
	{
		$alphabet = array();
		if ($locale == 'da-DK') {
			$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Æ', 'Ø', 'Å');
		} else if ($locale == 'ru-RU') {
			$alphabet = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'); 
		} else if ($locale == 'pl-PL') {
			$alphabet = array('A', 'Ą', 'B', 'C', 'Ć', 'D', 'E', 'Ę', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'Ł', 'M', 'N', 'Ń', 'O', 'Ó', 'P', 'R', 'S', 'Ś', 'T', 'U', 'W', 'Y', 'Z', 'Ź', 'Ż');
		} else if ($locale == 'sv-SE') {
			$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Å', 'Ä', 'Ö');
		} else if ($locale == 'sk-SK') {
			$alphabet = array('A','Á','B','C','Č','D','Ď','E','É','Ě','F','G','H','CH','I','Í','J','K','L','M','N','Ň','O','Ó','P','Q','R','Ř','S','Š','T','Ť','U','Ú','Ů','V','W','X','Y','Z','Ž');
		} else if ($locale == 'ro-RO') {
			$alphabet = array('A','Ă','Â','B','C','D','E','F','G','H','I','Î','J','K','L','M','N','O','P','Q','R','S','Ş','T','Ţ','U','V','W','X','Y','Z');
		} else if ($locale == 'el-GR') {
			$alphabet = array('Α','Β','Γ','Δ','Ε','Ζ','Η','Θ','Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω');
		} else {
			$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		}
		return $alphabet;
	}
	
	/**
	 * Adds dynamicall a copyright label on picture
	 * sourceFile: image path
	 */
	public static function watermarkImage ($sourceFile, $waterMarkText) {
		
		if ((strpos($sourceFile, '.jpg') !== FALSE || strpos($sourceFile, '.jpeg') !== FALSE)) {
		
			$matches = array();
			preg_match('/.*images\/com_yoorecipe\/(.*)\.(jpe?g)/i', $sourceFile, $matches);
			$watermarkedPicturePath = '';
			$inputFilePath = '';
			if (count( $matches) > 0) {
				// Use recipe picture, while making sure no http:// inside picture path
				$watermarkedPicturePath = 'images/com_yoorecipe/' . $matches[1] . '.protected.jpg';
				$inputFilePath = 'images/com_yoorecipe/' . $matches[1] . '.' .  $matches[2];
			} else {
				// Use default no image
				$watermarkedPicturePath = substr($sourceFile, 0, strpos($sourceFile, '.')) . '.protected.jpg';
				$inputFilePath = $sourceFile;
			}
				
			if (!file_exists($watermarkedPicturePath) && file_exists($inputFilePath)) {
			
				list($width, $height) = getimagesize($inputFilePath);
				$image_p = imagecreatetruecolor($width, $height);
				$image = imagecreatefromjpeg($inputFilePath);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
				$color = imagecolorallocate($image_p, 255 , 255, 255);
				$font = JPATH_ROOT .'/media/com_yoorecipe/fonts/arial.ttf';
				$font_size = 10;
				
				imagettftext($image_p, $font_size, 0, 10, $height-10, $color, $font, $waterMarkText);
				
				imagejpeg ($image_p, $watermarkedPicturePath, 100);
				imagedestroy($image);
				imagedestroy($image_p);
			}
			
			return $watermarkedPicturePath;
		} else {
			return $sourceFile;
		}
	}
	
	/**
	* Generate ingredients list
	*/
	public static function generateIngredientsList($ingredients) {
		
		$html = array();
		$html[] = '<p>';
		$html[] = '<span class="span-recipe-label">' . JText::_('COM_YOORECIPE_RECIPES_INGREDIENTS') . ':</span><br/>';
		$html[] = '<span class="span-recipe-ingredients">';
		
		for ($i=0;$i<count($ingredients)-1;$i++) {
		
			$ingredient = $ingredients[$i];
			$qty = ($ingredient->quantity == 0) ? '' : round($ingredient->quantity, 2) . ' ';
			$html[] =  $qty . JText::_($ingredient->unit) . ' ' . htmlspecialchars($ingredient->description) . ', ';
		}
		
		$ingredient = $ingredients[count($ingredients)-1];
		$qty = ($ingredient->quantity == 0) ? '' : round($ingredient->quantity, 2). ' ';
		$html[] = $qty . JText::_($ingredient->unit) . ' ' . htmlspecialchars($ingredient->description);
		
		$html[] = '</span>';
		$html[] = '</p>';
				
		return implode("\n", $html);
	}
	
	/**
	* Return HTML code for SWFUpload
	*/
	public static function generateSWFUpload($picturePath) {
	
		// Add CSS
		$document 	= JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'media/com_yoorecipe/styles/swfupload/default.css');

		// Add the links to the external files into the head of the webpage
		$document->addScript(JURI::root().'media/com_yoorecipe/js/swfupload/swfupload.js');
		$document->addScript(JURI::root().'media/com_yoorecipe/js/swfupload/fileprogress.js');
		$document->addScript(JURI::root().'media/com_yoorecipe/js/swfupload/handlers.js');
		
		$swfUploadHeadJs 	= JHtml::_('yoorecipejsutils.getSwfUploadScript');
		$document->addScriptDeclaration($swfUploadHeadJs);
		
		$params				= JComponentHelper::getParams('com_yoorecipe');
		$thumbnail_width	= $params->get('thumbnail_width', 250);
		$max_upload_size	= $params->get('max_upload_size', 2000); // kb

		// Add HTML
		$html = array();
		
		$html[] = '<input type="hidden" id="swfupload_upload_width" value="'.$thumbnail_width.'"/>';
		$html[] = '<input type="hidden" id="swfupload_upload_height" value="'.$thumbnail_width.'"/>';
	
		$html[] = '<input type="text" id="txtFileName" disabled="true" style="border: solid 1px; background-color: #fff;"/>';
		$html[] = '<span id="spanButtonPlaceholder"></span>('.JText::sprintf('COM_YOORECIPE_MAX_N_MB', $max_upload_size /1000).')';
		$html[] = '<div class="flash" id="fsUploadProgress">';
		$html[] = '</div>';
		
		$html[] = '<input type="hidden" name="jform[picture]" id="jform_picture" value="'.$picturePath.'"/>';
		$html[] = '<span class="clear" id="yoorecipe_uploadResultMsg"></span>';
		
		return implode("\n", $html);
	}
	
	/**
	* HTML code for drag and drop image upload
	*/
	public static function generateDragNDropUpload($picturePath) {
	
		// Add Javascript
		$document 	= JFactory::getDocument();
		$document->addScript(JUri::root().'media/com_yoorecipe/js/mooupload/Form.MultipleFileInput.js');
		$document->addScript(JUri::root().'media/com_yoorecipe/js/mooupload/Form.Upload.js');
		$document->addScript(JUri::root().'media/com_yoorecipe/js/mooupload/Request.File.js');
		$document->addScript(JUri::root().'media/com_yoorecipe/js/mooupload/iFrameFormRequest.js');
		$document->addScriptDeclaration(JHtml::_('yoorecipejsutils.getDragNDropUploadScript'));
		
		// Add HTML
		$html = array();
		
		$html[] = '<p>'.JText::_('COM_YOORECIPE_DRAG_N_DROP_TEXT').'</p>';
		$html[] = '<div>';
		$html[] = '<div class="formRow">';
		$html[] = '<label for="file" class="floated">'.JText::_('COM_YOORECIPE_FILE').'</label>';
		$html[] = '<input type="file" id="file" name="file" multiple><br>';
		$html[] = '</div>';
	  
	    $html[] = '<div class="formRow">';
	    $html[] = '<input type="submit" class="btn" value="'.JText::_('COM_YOORECIPE_UPLOAD').'" name="upload">';
	    $html[] = '</div>';
		
		$html[] = '<input type="hidden" name="jform[picture]" id="jform_picture" value="'.$picturePath.'" />';
		$html[] = '</div>';
	
		return implode("\n", $html);
	}
	
	public static function generateRecipeSeason($seasons_id) {
	
		// Add HTML
		$html = array();
		
		if (isset($seasons_id) && !empty($seasons_id)) {
		
			$html[] = '<strong>' . JText::_('COM_YOORECIPE_SEASONS') . ':&nbsp;</strong>';
		
			$firstElt = true;
			foreach ($seasons_id as $season_id) {
				$seasonUrl = JRoute::_(JHtml::_('YooRecipeHelperRoute.getSeasonRoute', $season_id) , false);
				$seasonLabel = htmlspecialchars(JText::_('COM_YOORECIPE_'.$season_id));
				if ($firstElt) {
					$html[] = '<span><a href="' . $seasonUrl .'" title="' . $seasonLabel . '">' . $seasonLabel . '</a></span>';
				} else {
					$html[] = '<span>, <a href="' . $seasonUrl .'" title="' . $seasonLabel . '">' . $seasonLabel . '</a></span>';
				}
				$firstElt = false;
			}
		}
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate a select list for duration in days
	 */
	static function selectDaysFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getDaysFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_days" id="' . $pfx_name . '_days">';
		for ($i=0 ; $i < 5 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
			
		return implode("\n", $html);
	}
	
	/**
	 * Generate a select list for duration in hours
	 */
	static function selectHoursFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getHoursFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_hours" id="' . $pfx_name . '_hours">';
		for ($i=0 ; $i < 24 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Generate a select list for duration in minutes
	 */
	static function selectMinutesFromDuration($pfx_name, $duration) {
		
		$html = array();
		
		$nbDays = JHtmlYooRecipeAdminUtils::getMinutesFromDuration($duration);
		$html[] = '<select name="' . $pfx_name . '_minutes" id="'. $pfx_name . '_minutes">';
		for ($i=0 ; $i < 60 ; $i++) {
			if (strcmp($nbDays,$i) == 0) {
				$html[] = '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			} else {
				$html[] = '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$html[] = '</select>';
			
		return implode("\n", $html);
	}
}