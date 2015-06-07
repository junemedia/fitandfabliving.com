<?php
/*----------------------------------------------------------------------
# YooRock! YooRecipe Search Module
# ----------------------------------------------------------------------
# Copyright (C) 2011 YooRock. All Rights Reserved.
# Coded by: YooRock!
# License: GNU GPL v2
# Website: http://extensions.yoorock.fr
------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access'); // no direct access
JHtmlBehavior::framework();

$document = JFactory::getDocument();
$document->addStyleSheet('media/mod_yoorecipe_search/styles/mod_yoorecipe_search'.$params->get('moduleclass_sfx').'.css');

$scriptValidateFormSearchTitle = '';
if ($params->get('show_search_title') && $params->get('searchword_mandatory')) {

	$scriptValidateFormSearchTitle = "
	//<![CDATA[
	function validateFormSearchTitle() { 
		var searchwordElt = $('mod_yoorecipe_search_searchword');
		if (searchwordElt.value == 0 || searchwordElt.value.length < 3) {
			alert('" . JText::_('MOD_YOORECIPE_SEARCH_SEARCHWORD_NOK') . "');
			return false;
		}
		return true;
	}
	/* ]]> */";
} else {
	$scriptValidateFormSearchTitle = "
	//<![CDATA[
	function validateFormSearchTitle() { 
		return true;
	}
	/* ]]> */
	";
}

$scriptValidateFormSearchCategories = '';
if ($params->get('show_search_categories') && $params->get('categories_direction') == 'horizontal' && $params->get('category_mandatory')) {

	$scriptValidateFormSearchCategories = "
	//<![CDATA[
	function validateFormSearchCategories() {
	
		var inputElts = document.getElementsByTagName('input');
		var categoriesChecked = false;
		for (i = 0 ; i < inputElts.length ; i++) {
			if (inputElts[i].name == 'searchCategories[]') {
				if (inputElts[i].checked) {
					categoriesChecked = true;
					break;
				}
			}
		}
		
		if (!categoriesChecked) {
			alert('" . JText::_('MOD_YOORECIPE_SEARCH_CATEGORY_NOK') . "');
		}
		return categoriesChecked;
	}
	/* ]]> */
	";
}
else {
	$scriptValidateFormSearchCategories = "
	//<![CDATA[
	function validateFormSearchCategories() {
		return true;
	}
	/* ]]> */
	";
}

$scriptValidateFormSearchIngredients = '';
if ($params->get('show_search_ingredients') && $params->get('ingredients_mandatory')) {
	
	$scriptValidateFormSearchIngredients = 
	"//<![CDATA[
	function validateFormSearchIngredients() {
		
		var validationResult = false;
		var inputsElt = document.getElementsByTagName('input');
		for (i=0; i < inputsElt.length; i++) {
			if (inputsElt[i].name == 'withIngredients[]' || inputsElt[i].name == 'withoutIngredient') {
				validationResult = true;
				break;
			}
		}
		
		if (!validationResult) {
			alert('" . JText::_('MOD_YOORECIPE_SEARCH_INGREDIENTS_NOK') . "');
		}
		return validationResult;
	}
	/* ]]> */
	";
}
else {
	$scriptValidateFormSearchIngredients = 
	"//<![CDATA[
	function validateFormSearchIngredients() {
		return true;
	}
	/* ]]> */
	";
}

if ($params->get('show_search_ingredients')) {
	$scriptValidateFormSearchIngredients .=
	"//<![CDATA[
	function withIngredient() {
		var ingredientElt = $('currentIngredient');
		if (ingredientElt.value != '') {
			
			var divIncludedIngredients = $('includedIngredients');
			var html = '<span class=\'withIngr\'>" . JText::_('MOD_YOORECIPE_SEARCH_WITH') . " ' + ingredientElt.value + ' <span class=\'ingrRemove\' onclick=\'disposeElt(this)\'>x</span><input type=\'hidden\' name=\'withIngredients[]\'value=\''+ingredientElt.value+'\' /></span>';
			divIncludedIngredients.innerHTML = divIncludedIngredients.innerHTML + html;
		
			ingredientElt.value = '';
		} else {
			alert('" . JText::_('MOD_YOORECIPE_SEARCH_INGREDIENT_MANDATORY') . "');
		}
	}
	
	function withoutIngredient() {
		var ingredientElt = $('currentIngredient');
		if (ingredientElt.value != '') {
			
			var divIncludedIngredients = $('includedIngredients');
			var html = '<span class=\'withoutIngr\'>" . JText::_('MOD_YOORECIPE_SEARCH_WITHOUT') . " ' + ingredientElt.value + ' <span class=\'ingrRemove\' onclick=\'removeIngredient(this)\'>x</span><input type=\'hidden\' name=\'withoutIngredient\'value=\''+ingredientElt.value+'\' /></span>';
			divIncludedIngredients.innerHTML = divIncludedIngredients.innerHTML + html;
			$('withoutButton').style.display = 'none';
			ingredientElt.value = '';
		} else {
			alert('" . JText::_('MOD_YOORECIPE_SEARCH_INGREDIENT_MANDATORY') . "');
		}
	}
	
	function removeIngredient(el) {
		$('withoutButton').style.display = 'inline';
		$$('span.withoutIngr').dispose();
	}
	
	function disposeElt(el) {
		spanParent = el.parentNode;
		spanParent.parentNode.removeChild(spanParent); 
	}
	
	function addIngredient(ingredient, cssclass) {
		var divIncludedIngredients = $('includedIngredients');
		var html = '<span class=\'withIngr\'>" . JText::_('MOD_YOORECIPE_SEARCH_WITH') . " ' + ingredient + ' <span class=\'ingrRemove\' onclick=\'this.parentNode.dispose()\'>x</span><input type=\'hidden\' name=\'withoutIngredient\' value=\''+ingredient+'\' /></span>';
		divIncludedIngredients.innerHTML = divIncludedIngredients.innerHTML + html;
	}
	/* ]]> */";
}

$scriptValidateFormSearchPrice = '';
if ($params->get('show_price', 0)) {
	
	$scriptValidateFormSearchPrice = "
	//<![CDATA[
	function validateFormSearchPrice() {
		var validationResult = false;
		searchPriceElt=$('search_price');
		var doubleValue = parseFloat(searchPriceElt.value.replace(',', '.'));
		
		if ($('price_selector').value =='999') {
			validationResult = true;
		}
		else if(searchPriceElt.value != '' && !isNaN(doubleValue)) {
			validationResult = true;
		}
		else {
			validationResult = false;
			
			if (searchPriceElt.value == '') {
				alert('" . JText::_('MOD_YOORECIPE_SEARCH_PRICE_NOK') . "');
			} else if (isNaN(doubleValue)) {
				alert('" . JText::_('MOD_YOORECIPE_SEARCH_PRICE_NOK2') . "');
			}
		}
		
		return validationResult;
	}
	/* ]]> */
	";
} else {
	$scriptValidateFormSearchPrice = "
	//<![CDATA[
	function validateFormSearchPrice() {
		return true;
	}
	/* ]]> */
	";
}

$scriptValidateSearchForm = "
	//<![CDATA[
	function validateSearchForm() {
	
		if (validateFormSearchTitle() && validateFormSearchCategories() && validateFormSearchIngredients() && validateFormSearchPrice()) {
			$('mod_yoorecipe_search_form').submit();
		}
	}
	/* ]]> */
";

$document->addScriptDeclaration($scriptValidateFormSearchTitle);
$document->addScriptDeclaration($scriptValidateFormSearchCategories);
$document->addScriptDeclaration($scriptValidateFormSearchIngredients);
$document->addScriptDeclaration($scriptValidateFormSearchPrice);
$document->addScriptDeclaration($scriptValidateSearchForm);

// This algorithm is based on the number of spans (indentations) to determine which inputs are children of the one clicked on
$document->addScriptDeclaration("
	//<![CDATA[
		function mod_yoorecipe_search_checkChildren(elt) {
		
			liElt = elt.getParent('li');
			nbChildrenOfClickedElt = liElt.getChildren().length;
			
			allNextLiElts = liElt.getAllNext();
			for (i = 0 ; i < allNextLiElts.length; i++) {
				
				childrenElts = allNextLiElts[i].getChildren();
				if (childrenElts.length > nbChildrenOfClickedElt) {
					childrenElts[0].checked = elt.checked;
				} else {
					break;
				}
			}
		}
		
	/* ]]> */
	");
	
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

$isSearchByTitleOnly = $params->get('show_search_title') && !$params->get('show_search_categories') && !$params->get('show_search_ingredients');

// Parametered labels
$titleLbl		= $params->get('title_lbl', JText::_('MOD_YOORECIPE_SEARCH_TITLE'));
$categoryLbl	= $params->get('category_lbl', JText::_('MOD_YOORECIPE_SEARCH_SEARCH_BY_CATEGORY'));
$ingredientsLbl	= $params->get('ingredients_lbl', JText::_('MOD_YOORECIPE_SEARCH_SEARCH_BY_INGREDIENTS'));
$submitLbl		= $params->get('submit_lbl', JText::_('MOD_YOORECIPE_SEARCH_SUBMIT'));
$authorLbl		= $params->get('author_lbl', JText::_('MOD_YOORECIPE_SEARCH_BY_AUTHOR'));
$prepTimeLbl	= $params->get('max_prep_time_lbl', JText::_('MOD_YOORECIPE_SEARCH_BY_PREP_TIME'));
$cookTimeLbl	= $params->get('max_cook_time_lbl', JText::_('MOD_YOORECIPE_SEARCH_BY_COOK_TIME'));
$ratingLbl		= $params->get('rated_lbl', JText::_('MOD_YOORECIPE_SEARCH_BY_RATING'));
$currencyLbl	= $params->get('currency_lbl', JText::_('MOD_YOORECIPE_SEARCH_PRICE_LABEL'));

$yooRecipeparams 	= JComponentHelper::getParams( 'com_yoorecipe' );
$currency			= $yooRecipeparams->get('currency');
?>
<form id="mod_yoorecipe_search_form" action="<?php echo JRoute::_('index.php');?>" method="post">

	<div>
		<input type="hidden" name="searchPerformed" value="1"/>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_yoorecipe" />
		<input type="hidden" name="view" value="search" />
		<input type="hidden" name="layout" value="search" />
	</div>
	
	<?php 
	if (strlen($params->get('intro_text')) > 0) {
		echo '<p>' . $params->get('intro_text') . '</p>';
	} 
	
	if ($params->get('show_search_title')) { ?>
		<fieldset class="mod_yoorecipe_search_fieldset">
			<legend><?php echo $titleLbl; ?></legend>
			<input type="text" class="span" name="searchword" id="mod_yoorecipe_search_searchword" size="20" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $searchword; ?>"/>
			<?php if ($isSearchByTitleOnly) : ?><input type="button" class="btn" onclick="validateSearchForm();" value="<?php echo $submitLbl; ?>"/><?php endif; ?>
		</fieldset>
	<?php 
	}
	
	if ($params->get('show_search_categories', 1)) {
	?>
		<fieldset class="mod_yoorecipe_search_fieldset">
			<legend><?php echo $categoryLbl; ?></legend>
		
		<?php
		if ($params->get('categories_direction') == 'vertical')
		{
			echo '<ul>';
			foreach ($categories as $category) : 
				echo '<li>';
				
				$chked = '';
				if (in_array($category->id, $searchCategories)) {
					$chked = 'checked="checked"';
				}
	?>
			<input
				type="checkbox" name="searchCategories[]" <?php echo $chked; ?> 
				value="<?php echo $category->id; ?>" 
				id="mod_yoorecipe_catid_<?php echo $category->id ?>" 
				onclick="mod_yoorecipe_search_checkChildren(this);" 
			/>
		<?php	
				echo str_repeat('<span>&nbsp;&nbsp;&nbsp;</span>', $category->level-1) . htmlspecialchars($category->title);
				echo '</li>';
			endforeach;
			echo '</ul>';
		}
			
		else if ($params->get('categories_direction') == 'horizontal')
		{
			foreach ($categories as $category) : 
				
				if (in_array($category->id, $searchCategories)) {	?>
					<input type="checkbox" name="searchCategories[]" value="<?php echo $category->id; ?>" checked="checked" />&nbsp;<?php echo $category->title; ?>&nbsp;&nbsp;
		<?php	} else { ?>
					<input type="checkbox" name="searchCategories[]" value="<?php echo $category->id; ?>" />&nbsp;<?php echo $category->title; ?>&nbsp;&nbsp;
		<?php 	}
			endforeach;
		}
		
		else if ($params->get('categories_direction') == 'dropdown')
		{
		
			echo '<select name="searchCategories[]" class="span">';
			echo '<option value="">' . JText::_('MOD_YOORECIPE_SEARCH_ALL') . '</option>';
			foreach ($categories as $category) : 
				echo '<option';
				
				if (in_array($category->id, $searchCategories)) {
					echo ' selected="selected"';
				}
				
				echo ' value="' . $category->id . '">' . str_repeat('&nbsp;&nbsp;&nbsp;', $category->level-1) . htmlspecialchars($category->title);
				echo '</option>';
			endforeach;
			echo '</select>';
			} ?>
			
		</fieldset>
	<?php 
	
	}
	
	if ($params->get('show_nutrition_facts', 1)) {
		
		echo '<div class="formelm">';
		echo '<label for="search_type_diet">' . JText::_('MOD_YOORECIPE_SEARCH_DIET_LABEL') . '</label>';
		echo '<input type="checkbox" name="search_type_diet"/>';
		echo '</div>';
		
		echo '<div class="formelm">';
		echo '<label for="search_type_veggie">' . JText::_('MOD_YOORECIPE_SEARCH_VEGGIE_LABEL') . '</label>';
		echo '<input type="checkbox" name="search_type_veggie"/>';
		echo '</div>';
		
		echo '<div class="formelm">';
		echo '<label for="search_type_glutenfree">' . JText::_('MOD_YOORECIPE_SEARCH_GLUTEN_FREE_LABEL') . '</label>';
		echo '<input type="checkbox" name="search_type_glutenfree"/>';
		echo '</div>';
		
		echo '<div class="formelm">';
		echo '<label for="search_type_lactosefree">' . JText::_('MOD_YOORECIPE_SEARCH_LACTOSE_FREE_LABEL') . '</label>';
		echo '<input type="checkbox" name="search_type_lactosefree"/>';
		echo '</div>';
	}
	
	if ($params->get('show_search_author', 1))
	{ ?>
		<div class="formelm">
			<label for="search_author"><?php echo $authorLbl ?></label>
			<select name="search_author" class="span">
				<option value=""><?php echo JText::_('MOD_YOORECIPE_SEARCH_ANY'); ?></option>
	<?php   foreach ($authors as $author) : 
				echo '<option value="' . $author->id .'">' . $author->author_name . '</option>';
			endforeach; ?>
			</select>
		</div>
	<?php 
	} 
	
	if ($params->get('show_search_prep_time', 1))
	{ ?>
		<div class="formelm">
			<label for="search_max_prep_hours"><?php echo $prepTimeLbl ?></label>
			<select name="search_max_prep_hours" class="span">
				<?php for ($i = 0 ; $i < 24 ; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i==2) : echo ' selected="selected"'; endif; ?>><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('MOD_YOORECIPE_SEARCH_HOURS_SYMBOL'); ?>
			<select name="search_max_prep_minutes" class="span">
				<?php for ($i = 0 ; $i < 60 ; $i++) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('MOD_YOORECIPE_SEARCH_MINUTES_SYMBOL'); ?>
		</div>
	<?php 
	} 
	
	if ($params->get('show_search_cook_time', 1))
	{ ?>
		<div class="formelm">
			<label for="search_max_cook_hours"><?php echo $cookTimeLbl ?></label>
			<select name="search_max_cook_hours" class="span">
				<?php for ($i = 0 ; $i < 24 ; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i==2) : echo ' selected="selected"'; endif; ?>><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('MOD_YOORECIPE_SEARCH_HOURS_SYMBOL'); ?>
			<select name="search_max_cook_minutes" class="span">
				<?php for ($i = 0 ; $i < 60 ; $i = $i+5) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('MOD_YOORECIPE_SEARCH_MINUTES_SYMBOL'); ?>
		</div>
	<?php
	}
	
	if ($params->get('show_search_rated', 1)) { ?>
		<div class="formelm">
			<label for="search_min_rate"><?php echo  $ratingLbl ?></label>
			<select name="search_min_rate" class="span">
				<option value="0" selected="selected"><?php echo JText::_('MOD_YOORECIPE_SEARCH_ANY'); ?></option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
	<?php
	}
	
	if ($params->get('show_search_cost', 1))
	{  ?>
		<div class="formelm">
			<label for="search_max_cost"><?php echo JText::_('MOD_YOORECIPE_SEARCH_YOORECIPE_MAX_COST'); ?></label>
			<select name="search_max_cost" class="span">
				<option value="999" selected="selected"><?php echo JText::_('MOD_YOORECIPE_SEARCH_ANY'); ?></option>
				<option value="1"><?php echo JText::_('MOD_YOORECIPE_SEARCH_YOORECIPE_CHEAP_LABEL'); ?></option>
				<option value="2"><?php echo JText::_('MOD_YOORECIPE_SEARCH_YOORECIPE_INTERMEDIATE_LABEL'); ?></option>
				<option value="3"><?php echo JText::_('MOD_YOORECIPE_SEARCH_YOORECIPE_EXPENSIVE_LABEL'); ?></option>
			</select>
		</div>
<?php }

	if ($params->get('show_price', 0)) { ?>
		<fieldset class="mod_yoorecipe_search_fieldset">
			<?php echo $currencyLbl . ' (' . $currency. ')' .'</br>';?> 
			<select name="search_operator_price" id="price_selector" class="span">
				<option value="999" selected="selected"><?php echo JText::_('MOD_YOORECIPE_SEARCH_ANY'); ?></option>
				<option value="gt"><?php echo JText::_('MOD_YOORECIPE_SEARCH_GREATER_THAN'); ?></option>
				<option value="lt"><?php echo JText::_('MOD_YOORECIPE_SEARCH_LESS_THAN'); ?></option>
			</select>
			<input class="inputbox span" type="text" name="search_price" id="search_price" size="10" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $searchPrice; ?>"/>
		</fieldset>
	<?php 
	}
	
	if ($params->get('show_search_ingredients', 1))
	{ ?>
		<fieldset class="mod_yoorecipe_search_fieldset">
			<legend><?php echo $ingredientsLbl; ?></legend>
			<div class="formelm">
				<input type="text" name="currentIngredient" id="currentIngredient" 
						size="15" maxlength="<?php echo $upper_limit; ?>"
						value="<?php if (isset($withIngredients[0])) { echo $withIngredients[0]; }?>" 
						class="inputbox span"
				/>
				<input type="button" class="btn" value="<?php echo JText::_('MOD_YOORECIPE_SEARCH_WITH'); ?>" onclick="withIngredient()"/>
				<input type="button" class="btn" id="withoutButton" value="<?php echo JText::_('MOD_YOORECIPE_SEARCH_WITHOUT'); ?>" onclick="withoutIngredient()" style="display:inline"/>
				<div id="includedIngredients"></div>
			</div>
		</fieldset>
	<?php
	}
	if (!$isSearchByTitleOnly) : ?>
		<input type="button" class="btn" id="mod_yoorecipe_search_btn" onclick="validateSearchForm();" value="<?php echo $submitLbl; ?>"/>
	<?php endif; ?>
</form>