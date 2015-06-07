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

abstract class JHtmlYooRecipeCommonJsUtils
{
	/**
	* Get ingredients management script
	* @units: ingredient units
	* @groups: ingredient groups
	*/
	static function getIngredientsManagementScript($units, $groups) {
	
		$ajax = "
    /* <![CDATA[ */
   function com_yoorecipe_addIngredient()
   {
             var myElement = $('ajax_container');
			 var order = $('order').value;
			 var recipe_id = $('recipe_id').value;
			 var quantity = $('quantity').value;
			 var unit = $('unit').value;
			 var description = $('ingr_description').value;
			 var price = $('price').value;
			 var group = $('group').value;
			 
			 // Check order and quantity are numeric fields
			 var exp=new RegExp('[0-9]+','g');
			 if (order != '' && !order.match(exp) ) {
				alert('". addslashes(JText::_('COM_YOORECIPE_ORDER_MUST_BE_NUMERIC')) ."');
				return false;
			 }
			 
			 if (!quantity.match(exp) ) {
				alert('". addslashes(JText::_('COM_YOORECIPE_QUANTITY_MUST_BE_NUMERIC')) ."');
				return false;
			 }
			 
			 // Check description is not empty
			 if (description == '') {
				alert('". addslashes(JText::_('COM_YOORECIPE_DESCRIPTION_IS_MANDATORY')) ."');
				return false;
			 }
                
			var tBodyElt = $('tBodyIngredients');
			
			var addedOrder = $('order').value;
			var addedQuantity = $('quantity').value;
			var addedUnit = $('unit').value;
			var addedDescription = $('ingr_description').value;
			var addedPrice = $('price').value;
			var addedGroup = $('group').value;
			
			var trElt = document.createElement('tr');
			
			var td0Elt = document.createElement('td');
			var inputOrder = new Element('input', {'type':'text', 'size':'3', 'name':'ordering[]', 'value':addedOrder});
			td0Elt.appendChild(inputOrder);
			
			var td0BisElt = document.createElement('td');
			var selectElt = document.createElement('select');
			selectElt.setAttribute('name','group[]');
			var optionValues =  new Array('";
				foreach ($groups as $group)
				{
					$ajax .= "', '" . $group->id;
				}		
			$ajax .= "');";

			$ajax .= "		
			var optionLabels =  new Array('";
				foreach ($groups as $group)
				{
					$ajax .= "', '" . addslashes(JText::_($group->text));
				}
			$ajax .= "');
					
			for (i=0; i < optionValues.length ; i++) {
			
				var optionElt = document.createElement('option');
				optionElt.setAttribute('value', optionValues[i]);
				optionElt.innerHTML = optionLabels[i];
				if (addedGroup == optionValues[i]) {
					optionElt.setAttribute('selected', 'selected');
				}
				selectElt.appendChild(optionElt);
			}
			
			td0BisElt.appendChild(selectElt);
			
			var td1Elt = document.createElement('td');
			var inputIngredientId = new Element('input', {'id': 'ingredientId', 'type':'hidden', 'size':'3', 'name':'ingredientId[]'});

			var inputQuantity = new Element('input', {'type':'text', 'name':'quantity[]', 'value':addedQuantity});
			td1Elt.appendChild(inputIngredientId);
			td1Elt.appendChild(inputQuantity);
			
			var td2Elt = document.createElement('td');
			var selectElt = document.createElement('select');
			selectElt.setAttribute('name','unit[]');
								
			var optionValues =  new Array('";
				foreach ($units as $crtUnit)
				{
					$ajax .= "', '" . $crtUnit->code;
				}		
			$ajax .= "');";

			$ajax .= "		
			var optionLabels =  new Array('";
				foreach ($units as $crtUnit)
				{
					$ajax .= "', '" . $crtUnit->label;
				}
			$ajax .= "');
					
			for (i=0; i < optionValues.length ; i++) {
			
				var optionElt = document.createElement('option');
				optionElt.setAttribute('value', optionValues[i]);
				optionElt.innerHTML = optionLabels[i];
				if (addedUnit == optionValues[i]) {
					optionElt.setAttribute('selected', 'selected');
				}
				selectElt.appendChild(optionElt);
			}
			
			td2Elt.appendChild(selectElt);
			
			var td3Elt = document.createElement('td');
			var inputDescriptionElt = new Element('input', {'type':'text', 'name':'ingr_description[]', 'value':addedDescription});
			td3Elt.appendChild(inputDescriptionElt);
			
			var td3BisElt = document.createElement('td');
			var inputPriceElt = new Element('input', {'type':'text', 'name':'price[]', 'value':addedPrice});
			td3BisElt.appendChild(inputPriceElt);
			
			var td5Elt = document.createElement('td');
			var inputDeleteElt = new Element('input', {'onclick': 'deleteIngredient(this)', 'type':'button', 'class':'btn', 'value':'". addslashes(JText::_('COM_YOORECIPE_INGREDIENTS_DELETE')) ."'});
			td5Elt.appendChild(inputDeleteElt);
			
			var td6Elt = document.createElement('td');
			
			trElt.appendChild(td0Elt);
			trElt.appendChild(td0BisElt);
			trElt.appendChild(td1Elt);
			trElt.appendChild(td2Elt);
			trElt.appendChild(td3Elt);
			trElt.appendChild(td3BisElt);
			trElt.appendChild(td5Elt);
			trElt.appendChild(td6Elt);
			
			tBodyElt.appendChild(trElt);
			
			// Reset add fields
			var order = $('order').value = '';
			var quantity = $('quantity').value = '';
			var unit = $('unit').value = '';
			var description = $('ingr_description').value = '';
			var price = $('price').value = '';
    }
	
	function deleteIngredient(elt) {
		elt.parentNode.parentNode.dispose();
	}

    /* ]]> */
	" ;
	
		return $ajax;
	}
	
	
	/**
	* Get ingredients management script
	* @units: ingredient units
	*/
	static function getJ3_IngredientsManagementScript($units, $currency) {
	
		$unitsLabels = array();
		foreach($units as $unit) {
			$unitsLabels[$unit->code] = $unit->label;
		}
		
		$ajax = "
    /* <![CDATA[ */
   function com_yoorecipe_addIngredient()
   {
		var ingr_container = $('ingr_container');
		var unitslabels = ".json_encode($unitsLabels).";
		var addedOrder = $('order').value;
		var addedQuantity = $('quantity').value;
		var addedUnit = $('unit').value;
		var addedDescription = $('ingr_description').value;
		var addedPrice;
		if ($('price') != undefined) {
			addedPrice = $('price').value;
		} else {
			addedPrice = '';
		}
		var addedGroup = $('group').value;
		var recipe_id = $('recipe_id').value;

		// Check order and quantity are numeric fields
		var exp=new RegExp('[0-9]+','g');
		if (addedOrder != '' && !addedOrder.match(exp) ) {
			alert('". addslashes(JText::_('COM_YOORECIPE_ORDER_MUST_BE_NUMERIC')) ."');
			return false;
		}

		if (!addedQuantity.match(exp) ) {
			alert('". addslashes(JText::_('COM_YOORECIPE_QUANTITY_MUST_BE_NUMERIC')) ."');
			return false;
		}

		// Check description is not empty
		if (addedDescription == '') {
			alert('". addslashes(JText::_('COM_YOORECIPE_DESCRIPTION_IS_MANDATORY')) ."');
			return false;
		}

		var ingrContainer = new Element('li');
		
		var inputOrder = new Element('input', {'type':'hidden', 'size':'3', 'name':'ordering[]', 'value':addedOrder});
		var inputGroup = new Element('input', {'type':'hidden', 'size':'3', 'name':'group[]', 'value':addedGroup});
		var inputIngredientId = new Element('input', {'id': 'ingredientId', 'type':'hidden', 'size':'3', 'name':'ingredientId[]'});
		var inputQuantity = new Element('input', {'type':'hidden', 'size':'3', 'name':'quantity[]', 'value':addedQuantity});
		var inputUnit = new Element('input', {'type':'hidden', 'size':'3', 'name':'unit[]', 'value':addedUnit});
		var inputDescriptionElt = new Element('input', {'type':'hidden', 'size':'3', 'name':'ingr_description[]', 'value':addedDescription});
		var inputPriceElt = new Element('input', {'type':'hidden', 'size':'3', 'name':'price[]', 'value':addedPrice});
		
		var content = addedQuantity + ' ' + unitslabels[addedUnit] + ' ' + addedDescription;
		if (addedPrice != '') {
			content += ' ('+addedPrice+' ".$currency.")';
		} 
		var spanText = new Element('span', {'html':content,'class':'withTg'});
		
		var deleteElt = new Element('span', {'onclick': 'j3_deleteIngredient(this)', 'html': ' x'});
		deleteElt.setStyle('cursor','pointer');
		var brElt = new Element('br');
		
		// inject
		spanText.grab(deleteElt);
		ingrContainer.grab(spanText);
		
		ingrContainer.grab(inputOrder);
		ingrContainer.grab(inputGroup);
		ingrContainer.grab(inputIngredientId);
		ingrContainer.grab(inputQuantity);
		ingrContainer.grab(inputUnit);
		ingrContainer.grab(inputDescriptionElt);
		ingrContainer.grab(inputPriceElt);
		ingrContainer.grab(brElt);
		
		$('group_'+addedGroup).grab(ingrContainer);
		$('cont_group_'+addedGroup).setStyle('display', 'block');
		
		// Reset add fields
		var order = $('order').value = '';
		var quantity = $('quantity').value = '';
		var unit = $('unit').value = '';
		var description = $('ingr_description').value = '';
		var price = $('price').value = '';
    }
	
	function deleteIngredient(elt) {
		elt.parentNode.parentNode.dispose();
	}
	
	function j3_deleteIngredient(elt) {
		elt.parentNode.parentNode.dispose();
	}

    /* ]]> */
	" ;
	
		return $ajax;
	}
	
	/**
	* Script to manage fractions
	*/
	static function getFractionsScript() {
	
		return "function turnIntoFraction(v) {
		
		if (v >= 1) {				
			var elts = new String(v).split('.');
			if (elts.length > 1) {
				return elts[0] + ' ' + getFraction('0.' + elts[1]);
			}
			return v;
		}
		else {
			return getFraction(v);
		}
	}
	
	function getFraction(v) {
		if (v=='0.5') return '1/2';
		if (v=='0.33') return '1/3';
		if (v=='0.67') return '2/3';
		if (v=='0.25') return '1/4';
		if (v=='0.75') return '3/4';
		if (v=='0.2') return '1/5';
		if (v=='0.4') return '2/5';
		if (v=='0.6') return '3/5';
		if (v=='0.8') return '4/5';
		if (v=='0.17') return '1/6';
		if (v=='0.83') return '5/6';
		if (v=='0.14') return '1/7';
		if (v=='0.29') return '2/7';
		if (v=='0.43') return '3/7';
		if (v=='0.57') return '4/7';
		if (v=='0.71') return '5/7';
		if (v=='0.86') return '6/7';
		if (v=='0.13') return '1/8';
		if (v=='0.38') return '3/8';
		if (v=='0.63') return '5/8';
		if (v=='0.88') return '7/8';
		if (v=='0.11') return '1/9';
		if (v=='0.22') return '2/9';
		if (v=='0.44') return '4/9';
		if (v=='0.56') return '5/9';
		if (v=='0.78') return '7/9';
		if (v=='0.89') return '8/9';
		if (v=='0.1') return '1/10';
		if (v=='0.3') return '3/10';
		if (v=='0.7') return '7/10';
		if (v=='0.9') return '9/10';
		if (v=='0.08') return '1/12';
		return v;
	}";
	}
	
	
	/**
	* Get tag management script
	*/
	static function getTagManagementScript() {
	
		return 
			"/* <![CDATA[ */
	function addTag() {
	
		var tagElt = $('currentTag');
		if (tagElt.value != '') {
			var divIncludedtags = $('includedTags');
			var html = '<span class=\'withTg\'>' + tagElt.value + ' <span class=\'TgRemove\' onclick=\'disposeElt(this)\'>x</span><input type=\'hidden\' name=\'withTags[]\'value=\''+tagElt.value+'\' /></span>';
			divIncludedtags.innerHTML = divIncludedtags.innerHTML + html;
			tagElt.value = '';
		} else {
			alert('".addslashes(JText::_('COM_YOORECIPE_TAG_MUST_NOT_BE_EMPTY'))."');
		} 
	}
	
	function disposeElt(el) {
		spanParent = el.parentNode;
		spanParent.parentNode.removeChild(spanParent); 
	}
	
	window.addEvent('domready', function () {
		$('currentTag').addEvent('keydown', function (event) {
			if (event.key == 'enter' || event.key == ',') { event.stopPropagation(); addTag(); $('currentTag').set('value', ''); return false;}
		});
	});
	
/* ]]> */";
	}
	
}