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

// no direct access
defined('_JEXEC') or die;

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

$params 				= JComponentHelper::getParams('com_yoorecipe');
$canSearchCategories	= $params->get('search_categories', 1);
$canSearchIngredients	= $params->get('search_ingredients', 1);
$canExcludeIngredients	= $params->get('exclude_ingredients', 1);

$show_search_prep_time	= $params->get('show_search_prep_time', 1);
$show_search_cook_time	= $params->get('show_search_cook_time', 1);
$show_search_author 	= $params->get('show_search_author', 1);
$show_search_rated		= $params->get('show_search_rated', 1);
$show_search_cost		= $params->get('show_search_cost', 1);
$show_search_price		= $params->get('show_search_price', 0);
$use_nutrition_facts	= $params->get('use_nutrition_facts', 1);

$searchword_mandatory	= $params->get('searchword_mandatory', 1);
$category_mandatory		= $params->get('category_mandatory', 1);
$ingredients_mandatory	= $params->get('ingredients_mandatory', 0);

$currency				= $params->get('currency', '&euro;');

// Build validation script from parameters
$scriptChunk = "";
if ($searchword_mandatory) : 
	
	$scriptChunk .= 
		"// Perform at least one field filled in
		searchWordElt = $('search-searchword');
		if (searchWordElt.value != '') {
			formOK = true;
		} else {
			$('searchword-validation-message').className = 'shareerr';
			return false;
		}";
endif;

if ($show_search_price) :
	$scriptChunk .= 
		"// Perform at least one field filled in
		searchPriceElt = $('search_operator_price');
		var doubleValue = parseFloat(searchPriceElt.value.replace(',','.'));
		
		// Reset error fields
		$('price-validation').className = 'hide shareerr';
		$('price-validation2').className = 'hide shareerr';
		
		// Perform tests
		if ($('price_selector').value =='999') {
			formOK = true;
		}
		else if(searchPriceElt.value != '' && !isNaN(doubleValue)) {
			formOK = true;
		}
		else {
			formOK = false;
			
			if (searchPriceElt.value == '') {
				$('price-validation').className = 'shareerr';
			} else if (isNaN(doubleValue)) {
				$('price-validation2').className = 'shareerr';
			}
		}
	";
endif;

if ($category_mandatory) :
	
	$scriptChunk .= 
		"// Among category fields
		var inputElts = document.getElementsByTagName('input');
		var catNOK = false;
		for (i = 0 ; i < inputElts.length ; i++) {
			if (inputElts[i].name == 'searchCategories[]' && inputElts[i].checked) {
				formOK = true;
				catNOK = true;
				break;
			}
		}
		
		if (catNOK) {
			$('search-validation-message').className = 'shareerr';
		}
		";
endif;

if ($ingredients_mandatory) :
	
	$scriptChunk .= 
		"// Among ingredient fields
		for (i = 1 ; i < 5; i++) {
			var elt = $('search-ingredient'+i);
			if (elt != undefined && elt.value != '') {
				formOK = true;
				break;
			}
		}
		";
endif;

if (!$searchword_mandatory && !$category_mandatory && !$ingredients_mandatory) :
	$scriptChunk .= "formOK = true;";
endif;

$validateFormScript = "
 //<![CDATA[
	function validateForm() {
	
		var formOK = false;
		
		" . $scriptChunk ."
		
		if (formOK) {
			submitForm();
		}
		
		return false;
	}
	
	function submitForm() {
		
		// Reset error message
		$('search-validation-message').className = 'hide shareerr';
";

if ($show_search_price) {
	$validateFormScript .= "	//Price check";
	$validateFormScript .= "	$('price-validation').className = 'hide shareerr';";
	$validateFormScript .= "	$('price-validation2').className = 'hide shareerr';";
}

$validateFormScript .=
		"$('searchword-validation-message').className = 'hide shareerr';
		$('searchForm').submit();
	}
	
	/* ]]> */
	";

$document = JFactory::getDocument();
$document->addScriptDeclaration($validateFormScript);

// Submit form on enter key press
$document->addScriptDeclaration("window.addEvent('domready', function () {
	window.addEvent('keydown', function(event){
		if (event.key == 'enter') { validateForm(); event.stopPropagation();}
	});
});");

if ($params->get('search_categories_display', 'dropdown') == 'flat') {

	// This algorithm is based on the number of spans (indentations) to determine which inputs are children of the one clicked on
	$document->addScriptDeclaration("
		//<![CDATA[
			function com_yoorecipe_search_checkChildren(elt) {
			
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
}

?>

<?php $formUrl = JRoute::_('index.php?option=com_yoorecipe&task=search'); ?>
<form id="searchForm" action="<?php echo $formUrl; ?>" method="post">
	<a id="yoorecipe-search" name="yoorecipe-search"></a>
	<input type="hidden" name="searchPerformed" value="1"/>
	<fieldset class="yoorecipe-fieldset">
		<legend><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_RECIPE'); ?></legend>
		<ul class="yoorecipe-results">
			<li>
				<label for="search-searchword"><?php echo JText::_('COM_YOORECIPE_SEARCH_KEYWORD'); ?></label>
				<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->searchword); ?>" class="inputbox" />
				<div id="searchword-validation-message" class="hide shareerr"><?php echo JText::_('COM_YOORECIPE_SEARCH_SEARCHWORD_MANDATORY'); ?></div>
			</li>
		</ul>
	</fieldset>

<?php if ($canSearchCategories) : ?>	
	<fieldset class="yoorecipe-fieldset">
		<legend><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_CATEGORY'); ?></legend>
	<?php 
	
	if ($use_nutrition_facts) {
		echo '<ul>';
		echo '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_DIET_LABEL') . '&nbsp;<input type="checkbox" name="search_type_diet"/></li>';
		echo '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_VEGGIE_LABEL') . '&nbsp;<input type="checkbox" name="search_type_veggie"/></li>';
		echo '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_GLUTEN_FREE_LABEL') . '&nbsp;<input type="checkbox" name="search_type_glutenfree"/></li>';
		echo '<li>' . JText::_('COM_YOORECIPE_YOORECIPE_LACTOSE_FREE_LABEL') . '&nbsp;<input type="checkbox" name="search_type_lactosefree"/></li>';
		echo '</ul>';
		echo '<br/>';
	}
	
	if ($params->get('search_categories_display', 'dropdown') == 'flat')
	{
		echo '<ul class="yoorecipe-results">';
		foreach ($this->categories as $category) : 
			echo '<li>';
			$chked = '';
			if (in_array($category->id, $this->searchCategories)) {
				$chked = 'checked="checked"';
			}
	?>
			<input
				type="checkbox" name="searchCategories[]" <?php echo $chked; ?> 
				value="<?php echo $category->id; ?>" 
				id="com_yoorecipe_catid_<?php echo $category->id ?>" 
				onclick="com_yoorecipe_search_checkChildren(this);" 
			/>
	<?php
			echo str_repeat('<span>&nbsp;&nbsp;&nbsp;</span>', $category->level-1) . htmlspecialchars($category->title);
			echo '</li>';
		endforeach;
		echo '</ul>';
	}
	
	else if ($params->get('search_categories_display', 'dropdown') == 'dropdown')
	{
		echo '<select name="searchCategories[]">';
		echo '<option value="">' . JText::_('COM_YOORECIPE_ALL') . '</option>';
		foreach ($this->categories as $category) : 
			echo '<option';
			
			if (in_array($category->id, $this->searchCategories)) {
				echo ' selected="selected"';
			}
			
			echo ' value="' . $category->id . '">' . str_repeat('&nbsp;&nbsp;&nbsp;', $category->level-1) . htmlspecialchars($category->title);
			echo '</option>';
		endforeach;
		echo '</select>';
	}
	?>	
	</fieldset>
<?php
endif; 

if ($show_search_author == 1) { ?>

	<fieldset class="yoorecipe-fieldset">
		<legend><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_AUTHOR'); ?></legend>
		<select name="search_author">
			<option value=""><?php echo JText::_('COM_YOORECIPE_ANY'); ?></option>
<?php   foreach ($this->authors as $author) : 
			echo '<option value="' . $author->id .'">' . $author->author_name . '</option>';
		endforeach; ?>
		</select>
	</fieldset>
<?php 
}

if ($show_search_prep_time || $show_search_cook_time || $show_search_rated)
{
?>
	<fieldset class="yoorecipe-fieldset">
		<legend><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_TIME'); ?></legend>		
<?php 
	if ($show_search_prep_time) 
	{ ?>
		<div class="formelm">
			<label for="search_max_prep_hours"><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_PREP_TIME'); ?></label>
			<select name="search_max_prep_hours">
				<?php for ($i = 0 ; $i < 24 ; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i==1) : echo ' selected="selected"'; endif; ?>><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('COM_YOORECIPE_HOUR'); ?>
			<select name="search_max_prep_minutes">
				<?php for ($i = 0 ; $i < 60 ; $i++) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('COM_YOORECIPE_MIN'); ?>
		</div>
<?php } 
	if ($show_search_cook_time)
	{ ?>
		<div class="formelm">
			<label for="search_max_cook_hours"><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_COOK_TIME'); ?></label>
			<select name="search_max_cook_hours">
				<?php for ($i = 0 ; $i < 24 ; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i==1) : echo ' selected="selected"'; endif; ?>><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('COM_YOORECIPE_HOUR'); ?>
			<select name="search_max_cook_minutes">
				<?php for ($i = 0 ; $i < 60 ; $i = $i+5) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select><?php echo JText::_('COM_YOORECIPE_MIN'); ?>
		</div>
<?php } 
	if ($show_search_rated)
	{ ?>		
		<div class="formelm">
			<label for="search_min_rate"><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_RATING'); ?></label>
			<select name="search_min_rate">
				<option value="0" selected="selected"><?php echo JText::_('COM_YOORECIPE_ANY'); ?></option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
<?php }

	if ($show_search_cost)
	{  ?>
		<div class="formelm">
			<label for="search_max_cost"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_MAX_COST'); ?></label>
			<select name="search_max_cost">
				<option value="999" selected="selected"><?php echo JText::_('COM_YOORECIPE_ANY'); ?></option>
				<option value="1"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_CHEAP_LABEL'); ?></option>
				<option value="2"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_INTERMEDIATE_LABEL'); ?></option>
				<option value="3"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_EXPENSIVE_LABEL'); ?></option>
			</select>
		</div>
<?php }

	if ($show_search_price)
	{  ?>
		<div class="formelm">
			<label for="search_operator_price"><?php echo JText::_('COM_YOORECIPE_YOORECIPE_MAX_PRICE'); ?></label>
			<select name="search_operator_price" id="price_selector">
				<option value="999" selected="selected"><?php echo JText::_('COM_YOORECIPE_ANY'); ?></option>
				<option value="gt"><?php echo JText::_('COM_YOORECIPE_GREATER_THAN'); ?></option>
				<option value="lt"><?php echo JText::_('COM_YOORECIPE_LESS_THAN'); ?></option>
			</select>
			<input type="text" id="search_operator_price" name="search_price" size="6" maxlength="<?php echo $upper_limit; ?>" class="inputbox"/>&nbsp;<?php echo $currency; ?>
		</div>
		<div id="price-validation" class="hide shareerr"><?php echo JText::_('COM_YOORECIPE_SEARCH_SEARCHWORD_MANDATORY'); ?></div>
		<div id="price-validation2" class="hide shareerr"><?php echo JText::_('COM_YOORECIPE_SEARCH_SEARCHWORD_MANDATORY2'); ?></div>
<?php } ?>
	</fieldset>	
<?php
}

if ($canSearchIngredients || $canExcludeIngredients) : ?>	
	<fieldset class="yoorecipe-fieldset right">
		<legend><?php echo JText::_('COM_YOORECIPE_SEARCH_BY_INGREDIENTS'); ?></legend>
	
	<?php if ($canSearchIngredients) : ?>
		<ul class="yoorecipe-results">
			<li>
				<span><?php echo JText::_('COM_YOORECIPE_SEARCH_WITH_INGREDIENTS'); ?></span>
			</li>
			<li>
				<div class="formelm">
				<label for="search-ingredient1"><?php echo JText::_('COM_YOORECIPE_SEARCH_INGREDIENT1'); ?></label>
				<input type="text" name="withIngredients[]" id="search-ingredient1" 
					size="30" maxlength="<?php echo $upper_limit; ?>" 
					value="<?php if (isset($this->withIngredients[0])) { echo $this->escape($this->withIngredients[0]); }?>" 
					class="inputbox"
				/>
				</div>
			</li>
			<li>
				<div class="formelm">
				<label for="search-ingredient2"><?php echo JText::_('COM_YOORECIPE_SEARCH_INGREDIENT2'); ?></label>
				<input type="text" name="withIngredients[]" id="search-ingredient2"
					size="30" maxlength="<?php echo $upper_limit; ?>" 
					value="<?php if (isset($this->withIngredients[1])) { echo $this->escape($this->withIngredients[1]); }?>" 
					class="inputbox" 
				/>
				</div>
			</li>
			<li>
				<div class="formelm">
				<label for="search-ingredient3"><?php echo JText::_('COM_YOORECIPE_SEARCH_INGREDIENT3'); ?></label>
				<input type="text" name="withIngredients[]" id="search-ingredient3"
					size="30" maxlength="<?php echo $upper_limit; ?>" 
					value="<?php if (isset($this->withIngredients[2])) { echo $this->escape($this->withIngredients[2]); }?>"
					class="inputbox"
				/>
				</div>
			</li>
		</ul>
	<?php endif; ?>
	<?php if ($canExcludeIngredients) : ?>
		<ul class="yoorecipe-results">
			<li>
				<div class="formelm">
				<label for="withoutIngredient"><?php echo JText::_('COM_YOORECIPE_SEARCH_WITHOUT_INGREDIENTS'); ?></label>
				<input type="text" name="withoutIngredient" id="search-ingredient4"
					size="30" maxlength="<?php echo $upper_limit; ?>" 
					value="<?php if (isset($this->withoutIngredient)) { echo $this->escape($this->withoutIngredient); }?>"
					class="inputbox"
				/>
				</div>
			</li>
		</ul>
	<?php endif; ?>
	</fieldset>
<?php endif; ?>

	<input type="button" name="Search" onclick="validateForm()" class="btn" value="<?php echo JText::_('COM_YOORECIPE_SEARCH');?>" />
	<input type="hidden" name="task" value="search" />

	<div id="search-validation-message" class="hide shareerr">
		<?php 
			if ($category_mandatory) :
				echo JText::_('COM_YOORECIPE_SEARCH_CATEGORY_IS_MANDATORY') . '. '; 
			endif;
			if ($ingredients_mandatory) :
				echo JText::_('COM_YOORECIPE_SEARCH_INGREDIENT_IS_MANDATORY');
			endif;		
		?>
	</div>
</form>

<br/>