<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class JHtmlYooRecipeJSUtils
{
	/**
	 * Smooth scroll
	 */
	static function getSmoothScrollScript()
	{
		return "new Fx.SmoothScroll({duration: 200},window);";
	}
	
	/**
	 * Delete comment ajax call
	 */
	static function getDeleteCommentScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=deleteComment&format=raw&recipeId=@@recipe@@&commentId=@@comment@@';
		
		return "//<![CDATA[
	function com_yoorecipe_deleteComment(recipeId, commentId) {
		
		var raw_url = '" . $url . "';
		var url = raw_url.replace('@@recipe@@', recipeId);
		url = url.replace('@@comment@@', commentId);
		
		$('yoorecipe_comment_'+commentId).empty().addClass('ajax-loading');
		$('yoorecipe_comment_'+commentId).empty().addClass('ajax-centered');
		$('yoorecipe_comment_'+commentId).setStyle('border','none');
		var x = new Request({
			url: url, 
			method: 'post', 
			onRequest: function(){ },
			onSuccess: function(result){
			   if (result.match('^'+'NOK')) {
			      alert(result.substr(4,result.length-1));
				} else {
				   
					nbComments = $('com_yoorecipe_nb_comments2').get('html');
					if ($('com_yoorecipe_nb_comments1') != undefined) {
						$('com_yoorecipe_nb_comments1').set('html', nbComments - 1);
					}
					$('com_yoorecipe_nb_comments2').set('html', nbComments - 1);
					$('yoorecipe_comment_'+commentId).dispose();
				   
					$('div-recipe-rating').set('html', result);
				}
			},
			onFailure: function(response){
				alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_FORBIDDEN_DELETE_OPERATION')) ." ');
			}                
		}).send();
	}
	/* ]]> */";
	}
	
	/**
	 * Report abusive comments ajax call
	 */
	static function getReportAbusiveCommentScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=reportComment&format=raw&recipeId=@@recipe@@&commentId=@@comment@@';
		
		return "//<![CDATA[
		function com_yoorecipe_reportComment(recipeId, commentId) {
			
			var raw_url = '" . $url . "';
			var url = raw_url.replace('@@recipe@@', recipeId);
			url = url.replace('@@comment@@', commentId);
			$('yoorecipe_comment_'+commentId).empty().addClass('ajax-loading');
			$('yoorecipe_comment_'+commentId).empty().addClass('ajax-centered');
			$('yoorecipe_comment_'+commentId).setStyle('border','none');
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(result){
				   if (result == 'OK') {
				   
						nbComments = $('com_yoorecipe_nb_comments2').get('html');
						if ($('com_yoorecipe_nb_comments1') != undefined) {
							$('com_yoorecipe_nb_comments1').set('html', nbComments - 1);
						}
					   $('com_yoorecipe_nb_comments2').set('html', nbComments - 1);
					   $('yoorecipe_comment_'+commentId).dispose();
					} else {
						alert(result);
					}
				},
				onFailure: function(response){
					alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_OCCURED')) ." ');
				}                
			}).send();
		}
		/* ]]> */";
	}

	/**
	 * Add to favourites ajax call
	 */
	static function getAddToFavouritesScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=addToFavourites&format=raw&recipeId=@@recipe@@';
		return "//<![CDATA[
		function addToFavourites(recipeId) {
			
			var raw_url = '" . $url . "';
			var url = raw_url.replace('@@recipe@@',recipeId);
			$('fav_'+recipeId).empty().addClass('ajax-loading');
			
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(result){
					$('fav_'+recipeId).removeClass('ajax-loading');
					$('fav_'+recipeId).set('html', result);
				},
				onFailure: function(response){
					alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_FORBIDDEN_DELETE_OPERATION')) ." ');
				}                
			}).send();
		}
		/* ]]> */
		";
	}
	
	/**
	 * Remove from favourites ajax call
	 */
	static function removeFromFavouritesScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=removeFromFavourites&format=raw&recipeId=@@recipe@@';
		return "//<![CDATA[
		function removeFromFavourites(recipeId) {
			
			var raw_url = '" . $url . "';
			var url = raw_url.replace('@@recipe@@',recipeId);
			$('fav_'+recipeId).empty().addClass('ajax-loading');
			
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(result){
					$('fav_'+recipeId).removeClass('ajax-loading');
					$('fav_'+recipeId).set('html', result);
				},
				onFailure: function(response){
					alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_FORBIDDEN_DELETE_OPERATION')) ." ');
				}                
			}).send();
		}
		/* ]]> */
		";
	}
	
	static function getAddRecipeRatingScripts($canShowEmail, $canShowRecaptch, $int1, $int2 )
	{
		$script = "//<![CDATA[
		function validateCommentForm() {
	
		var resultOK = true;
		
		// Check fields are filled in
		var authorElt = $('yoorecipe-rating-form-author');
		var commentElt = $('yoorecipe-rating-form-comment');
		
		resultOK &= checkFieldEmptyness(authorElt);
		resultOK &= checkFieldEmptyness(commentElt);
		";
		if ($canShowEmail) :
			
			$script .= "// Check email address is valid
		var emailElt = $('yoorecipe-rating-form-email');
		resultOK &= checkFieldEmptyness(emailElt);
		
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test(emailElt.value)) {
			resultOK = false;
			invalidateField(emailElt);
		} else {
			validateField(emailElt);
		}
		";
		endif;
		
		if ($canShowRecaptch == 'std') {
		
			$solution = $int1 + $int2;
			$script .= 'var enigmaElt = $("yoorecipe-rating-form-enigma");
				if (enigmaElt.value != ' . $solution . ') {
					resultOK = false;
					invalidateField(enigmaElt);
				} else {
					validateField(enigmaElt);
				}';
		}
		
		$url = JURI::root().'index.php?option=com_yoorecipe&task=addRecipeRating&format=raw';
		$script .= "if (resultOK) {
		
			var url = '".$url."';
			var recipeId = $('yoorecipe-rating-form-recipe-id').value;
			var userId = $('yoorecipe-rating-form-user-id').value;
			var rating = $('rating').value;
			var comment = encodeURI($('yoorecipe-rating-form-comment').value);
			var author = $('yoorecipe-rating-form-author').value;
			var email;
			if ($('yoorecipe-rating-form-email') != undefined) {
				email = $('yoorecipe-rating-form-email').value;
			}
			var recaptcha_challenge_field;
			if ($('recaptcha_challenge_field') != undefined) {
				recaptcha_challenge_field = $('recaptcha_challenge_field').value;
			}
			var recaptcha_response_field;
			if ($('recaptcha_response_field') != undefined) {
				recaptcha_response_field = $('recaptcha_response_field').value;
			}
			
			var x = new Request({
				url: url, 
				method: 'post',
				data: 'recipeId='+recipeId+'&userId='+userId+'&rating='+rating+'&comment='+comment+'&author='+author+'&email='+email+'&recaptcha_challenge_field='+recaptcha_challenge_field+'&recaptcha_response_field='+recaptcha_response_field,
				append : $('yoorecipe-ajax-container'),
				onRequest: function() { 
					$('ajax-loading').addClass('ajax-loading');
				},
				onSuccess: function(responseJson){
					json = JSON.decode(responseJson);
					
					$('ajax-loading').removeClass('ajax-loading');
					
					if (json.error == false) {
						newVal = parseFloat($('com_yoorecipe_nb_comments2').get('html'));
						if ($('com_yoorecipe_nb_comments1') != undefined) {
							$('com_yoorecipe_nb_comments1').set('html', newVal+1);
						}
						$('yoorecipe-ajax-container').set('html', $('yoorecipe-ajax-container').get('html') + json.html);
						$('com_yoorecipe_nb_comments2').set('html', newVal+1);
						$('yoorecipe-rating-form-comment').value = '';
					}
					else {
						alert('".addslashes(JText::_('COM_YOORECIPE_COMMENT_NOT_ADDED'))."');
					}
				},
				onFailure: function(response){
					alert('".addslashes(JText::_('COM_YOORECIPE_ERROR_OCCURED'))."');
					$('ajax-loading').removeClass('ajax-loading');
				}                
			}).send();
		}
	}
	
	function checkFieldEmptyness(elt) {
		
		if (elt.value == '') {
			invalidateField(elt)
			return false;
		} else {
			validateField(elt)
			return true;
		}
	}
	
	function invalidateField(elt) {
		elt.style.border = '1px solid red';
		$(elt.id + '-err').set('html', $(elt.id + '-msg').value);
	}
	
	function validateField(elt) {
		elt.style.border = 'none';
		$(elt.id + '-err').set('html','');
	}
	
	function setRatingValue(ratingValue) {
		for (i = 1 ; i <= 5; i++) {
			if (i <= ratingValue) {
				$('star-icon-' + i).src = '" . JURI::base( true ) . "/media/com_yoorecipe/images/star-icon.png';
			}
			else {
				$('star-icon-' + i).src = '" . JURI::base( true ) . "/media/com_yoorecipe/images/star-icon-empty.png';
			}
		}
		$('span-rating').set('html', ratingValue);
		$('rating').value = ratingValue;
	}
		/* ]]> */
		";
		
		return $script;	
	}
	
	static function getMoreCommentsScript($recipeId)
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=getMoreComments&format=raw&recipeId=' . $recipeId;
		return "/* <![CDATA[ */
	   function getMoreComments()
	   {
			var url = '" . $url . "';
			
			var div = new Element('div', {'id':'tmpAjaxLoading'}).addClass('ajax-loading');
			div.addClass('ajax-centered');
			div.inject($('yoorecipe-ajax-container'));
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(response){
							
					containerElt = $('yoorecipe-ajax-container');
					$('tmpAjaxLoading').dispose();
					containerElt.set('html', containerElt.get('html') + response);
					$('yoorecipeGetMoreCommentsBtn').destroy();
				},
				onFailure: function(response){
					$('tmpAjaxLoading').dispose();
				}                
			}).send();
		}
		/* ]]> */
		" ;
	}
	
	static function getQuantityConverterScripts($useFractions, $canShowPeopleIcons, $imgIcon, $nbPersons)
	{
		$script = "//<![CDATA[
 
		function turnIntoFraction(value) {
			
			if (value >= 1) {				
				var elts = new String(value).split('.');
				if (elts.length > 1) {
					return elts[0] + ' ' + getFraction('0.' + elts[1]);
				}
				return value;
			}
			else {
				return getFraction(value);
			}
		}
		
		function getFraction(value) {
			if (value == '0.5') return '1/2';
			if (value == '0.33') return '1/3';
			if (value == '0.67') return '2/3';
			if (value == '0.25') return '1/4';
			if (value == '0.75') return '3/4';
			if (value == '0.2') return '1/5';
			if (value == '0.4') return '2/5';
			if (value == '0.6') return '3/5';
			if (value == '0.8') return '4/5';
			if (value == '0.17') return '1/6';
			if (value == '0.83') return '5/6';
			if (value == '0.14') return '1/7';
			if (value == '0.29') return '2/7';
			if (value == '0.43') return '3/7';
			if (value == '0.57') return '4/7';
			if (value == '0.71') return '5/7';
			if (value == '0.86') return '6/7';
			if (value == '0.13') return '1/8';
			if (value == '0.38') return '3/8';
			if (value == '0.63') return '5/8';
			if (value == '0.88') return '7/8';
			if (value == '0.11') return '1/9';
			if (value == '0.22') return '2/9';
			if (value == '0.44') return '4/9';
			if (value == '0.56') return '5/9';
			if (value == '0.78') return '7/9';
			if (value == '0.89') return '8/9';
			if (value == '0.1') return '1/10';
			if (value == '0.3') return '3/10';
			if (value == '0.7') return '7/10';
			if (value == '0.9') return '9/10';
			if (value == '0.08') return '1/12';
			if (value == '0') return '';
			return value;
		}
/* ]]> */";

		return $script;
	}
	
	static function getUpdateLimitBox() {
	
		return "//<![CDATA[
		function updateLimitBox(elt){
		var els=document.getElementsByName('limit'),i,l=els.length;
		for(i=0;i<l;i++){
			els[i].selectedIndex = elt.selectedIndex;
			els[i].options[elt.selectedIndex].value = elt.options[elt.selectedIndex].value;
			els[i].options[elt.selectedIndex].text = elt.options[elt.selectedIndex].text;
		}}
		/* ]]> */";
		
		return $script;
	}
	
	/**
	* Returns ajax function for SWFUpload
	*/
	static function getSwfUploadScript() {
	
		// When we send the files for upload, we have to tell Joomla our session, or we will get logged out 
		$session = JFactory::getSession();
		
		$params	= JComponentHelper::getParams('com_yoorecipe');
		$max_upload_size	= $params->get('max_upload_size', 2000); // kb
		$validFileExts 		= explode(',', $params->get('authorized_extensions', 'jpg,png,gif'));
		$file_types 		= '*.' . implode(';*.', $validFileExts);
		
		return 'var swfu;

 //<![CDATA[
window.onload = function()
{
var settings = 
{
	flash_url : "'.JURI::root().'media/com_yoorecipe/js/swfupload/swfupload.swf",
	upload_url: "index.php",
	file_post_name: "resume_file",
	post_params: 
	{
		"option" : "com_yoorecipe",
		"controller" : "yoorecipe",
		"task" : "uploadRecipePicture",
		"'.$session->getName().'" : "'.$session->getId().'",
		"format" : "raw"
	}, 
	file_size_limit : "'.$max_upload_size.'KB",
	file_types : "'.$file_types.'",
	file_types_description : "All Files",
	file_upload_limit : 0,
	file_queue_limit : 0,
	
	custom_settings : 
	{
		progressTarget : "fsUploadProgress",
		cancelButtonId : "btnCancel"
	},
	debug: false,
 
	// Button Settings
	button_image_url : "'.JURI::root().'media/com_yoorecipe/images/XPButtonUploadText_61x22.png",
	button_placeholder_id : "spanButtonPlaceholder",
	button_width: 70,
	button_height: 22,
	button_disabled : false,
 
	// Event handler settings
	swfupload_preload_handler : preLoad,
	swfupload_loaded_handler : swfUploadLoaded,
	file_dialog_start_handler: fileDialogStart,
	file_queued_handler : fileQueued,
	file_queue_error_handler : fileQueueError,
	file_dialog_complete_handler : fileDialogComplete,
	
	upload_progress_handler : uploadProgress,
	upload_error_handler : uploadError,
	upload_success_handler : uploadSuccess,
	upload_complete_handler : uploadComplete,
	
	custom_settings : {
		progress_target : "fsUploadProgress",
		upload_successful : false
	},
	
	// Debug settings
	debug: false
};

	swfu = new SWFUpload(settings);
	};
	
	/* ]]> */';
	
	}
	
	/**
	* Return script for drag and drop picture upload
	*/
	static function getDragNDropUploadScript() {
	
return "var iffr;
window.addEvent('domready', function(){
  
  // Create the file uploader
  var upload = new Form.Upload('file', {
    dropMsg: '".addslashes(JText::_('COM_YOORECIPE_DROP_FILES'))."',
	onFailure: function(response){
		alert(response);
    },
	onComplete: function(response){
		
		json = JSON.decode(response, true);
		if (json == null) {
			return false;
		} else if (json.errors.length > 0) {
			var params = '';
			Object.each(json.errors, function(item, index) {
				params += 'errcode[]='+item+'&';
			});
			SqueezeBox.open('index.php?option=com_yoorecipe&view=form&tmpl=component&layout=squeezebox&'+params, {handler: 'iframe', size: {x: 400, y: 200}});
		} else {
			$('jform_picture').set('value', json.picture_path);
			$('yoorecipe_picture').set('src', json.picture_path);
		}
	}
  })

  // Use iFrameFormRequest, which posts to iFrame 
  if (!upload.isModern()) {
    iffr = new iFrameFormRequest('adminForm', {
		onFailure: function(response){
			alert(response);
		},
		onComplete: function(response){
			json = JSON.decode(response, true);
			if (json == null) {
				return false;
			} else if (json.errors.length > 0) {
				var params = '';
				Object.each(json.errors, function(item, index) {
					params += 'errcode[]='+item+'&';
				});
				SqueezeBox.open('index.php?option=com_yoorecipe&view=form&tmpl=component&layout=squeezebox&'+params, {handler: 'iframe', size: {x: 400, y: 200}});
			} else {
				$('jform_picture').set('value', json.picture_path);
				$('yoorecipe_picture').set('src', json.picture_path);
			}
		}
    });
  }
});";
	}
	
	/**
	* Script to manage tags
	*/
	static function getTagManagementScript() {
	
		return $checkTagScript = "
	 /* <![CDATA[ */
		function addTag() {
		
			var tagElt = $('currentTag');
			if (tagElt.value != '') {
				var divIncludedtags = $('includedTags');
				var html = '<span class=\'withTg\'>' + tagElt.value + ' <span class=\'TgRemove\' onclick=\'disposeElt(this)\'>x</span><input type=\'hidden\' name=\'withTags[]\'value=\''+tagElt.value+'\' /></span>';
				divIncludedtags.set('html', divIncludedtags.get('html') + html);
				tagElt.value = '';
			} else {
				alert('" . addslashes(JText::_('COM_YOORECIPE_TAG_CANNOT_BE_EMPTY')) . "');
			} 
		}
		
		function disposeElt(el) {
			spanParent = el.parentNode;
			spanParent.parentNode.removeChild(spanParent); 
		}
		
		window.addEvent('domready', function () {
			$('currentTag').addEvent('keydown', function (event) {
				if (event.key == 'enter' || event.key == ',') { event.stopPropagation(); addTag(); $('currentTag').set('value', ''); return false; }
			});
		});
		
	/* ]]> */
	";
	}
	
	static function validateRecipeScript() {
		return "
	 /* <![CDATA[ */
		function com_yoorecipe_checkTitle() {
			
			var result = false;
			if ($('jform_title').value != '') {
				result = true;
			}
			return result;
		}
	/* ]]> */
	
	 /* <![CDATA[ */
	function com_yoorecipe_checkIngredients() {
		
		var atLeastOneQty 	= false;
		var atLeastOneDesc 	= false;
		qtyInputs = $$('input[name^=quantity]');
		for (i = 0 ; i < qtyInputs.length ; i++) {
		
			if (qtyInputs[i].value == '' || isNaN(parseFloat(qtyInputs[i].value))) {
				break;
			} else {
				atLeastOneQty = true;
			}
		}
		descInputs = $$('input[name^=ingr_description]');	
		for (i = 0 ; i < descInputs.length ; i++) {
			if (descInputs[i].value == '') {
				break;
			} else {
				atLeastOneDesc = true;
			}
		}
		return atLeastOneQty && atLeastOneDesc;
	}
	/* ]]> */
	";
	}
	
	/**
	* Generate JS to manage ingredients
	* @units: array of ingredient units
	* @groups: array of ingredient groups
	*/
	static function getManageIngredientsScript($units, $groups) {
		
		$addRemoveScript = "
    /* <![CDATA[ */
   function com_yoorecipe_addRecipeIngredient()
   {
		var tBodyElt = $('tBodyIngredients');
		var addedQuantity = $('add_quantity').value;
		var addedUnit = $('add_unit').value;
		var addedDescription = $('add_description').value;
		var addedGroup = $('add_group').value;
		
		if (addedDescription == '') {
			alert('". addslashes(JText::_('COM_YOORECIPE_DESCRIPTION_IS_MANDATORY')) ."');
			return false;
		}

		var trElt = new Element('tr');
		var tdGroup = new Element('td');
		var selectElt = new Element('select', {'name':'group[]'});
		var optionValues =  new Array('";
			foreach ($groups as $group)
			{
				$addRemoveScript .= "', '" . $group->id;
			}		
		$addRemoveScript .= "');";

		$addRemoveScript .= "		
		var optionLabels =  new Array('";
			foreach ($groups as $group)
			{
				$addRemoveScript .= "', '" . addslashes(JText::_($group->text));
			}
		$addRemoveScript .= "');
				
		for (i=0; i < optionValues.length ; i++) {
			var optionElt = new Element('option', {'value': optionValues[i], 'html': optionLabels[i]});
			if (addedGroup == optionValues[i]) {
				optionElt.set('selected', 'selected');
			}
			optionElt.inject(selectElt);
		}
		
		selectElt.inject(tdGroup);
		
		var tdQty = new Element('td');
		var inputIngrId = new Element('input', {'type':'hidden', 'name': 'ingrId[]'});
		
		var inputQuantity = new Element('input', {'type':'text', 'name':'quantity[]', 'value':addedQuantity});
		inputIngrId.inject(tdQty);
		inputQuantity.inject(tdQty);
		
		var tdUnit = new Element('td');
		var selectElt = new Element('select', {'name':'unit[]'});
							
		var optionValues =  new Array('";
		foreach ($units as $unit)
		{
			$addRemoveScript .= "', '" . $unit->code;
		}		
		$addRemoveScript .= "');";

		$addRemoveScript .= "		
		var optionLabels =  new Array('";
		foreach ($units as $unit)
		{
			$addRemoveScript .= "', '" . $unit->label;
		}
		$addRemoveScript .= "');
		
		for (i=0; i < optionValues.length ; i++) {
			var optionElt = new Element('option', {'value':optionValues[i], 'html':optionLabels[i]});
			if (addedUnit == optionValues[i]) {
				optionElt.set('selected', 'selected');
			}
			optionElt.inject(selectElt);
		}
		selectElt.inject(tdUnit);
		
		var tdDesc = new Element('td');
		var inputDescriptionElt = new Element('input', {'type':'text', 'name':'ingr_description[]', 'value':addedDescription});
		inputDescriptionElt.inject(tdDesc);
		
		var tdBtn = new Element('td');
		var input = new Element('input', {'type': 'button', 'class': 'button', 'value': '". JText::_('COM_YOORECIPE_INGREDIENTS_DELETE') ."'});
		input.addEvent('click', function () {com_yoorecipe_deleteIngredient(this.parentNode);});
		input.inject(tdBtn);		
		tdGroup.inject(trElt);
		tdQty.inject(trElt);
		tdUnit.inject(trElt);
		tdDesc.inject(trElt);
		tdBtn.inject(trElt);
		
		trElt.inject(tBodyElt);
		
		// Reset add fields
		$('add_quantity').value = '';
		$('add_unit').value = '';
		$('add_description').value = '';
    }
	
	function com_yoorecipe_deleteIngredient(el)
	{
		var rowElt = el.parentNode;
		rowElt.parentNode.removeChild(rowElt);
	}

    /* ]]> */
    " ;
	
		return $addRemoveScript;
	}
}