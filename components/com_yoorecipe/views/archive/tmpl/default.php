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

$document 	= JFactory::getDocument();
$user 		= JFactory::getUser();
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_single.css');

// Component Parameters
$yooRecipeparams 		= JComponentHelper::getParams('com_yoorecipe');
?>
<h1 class="yoorecipe-h1"><?php echo JText::_('COM_YOORECIPE_ARCHIVES_LIST'); ?></h1>
<?php
if (count($this->items) > 0) { 

	$cnt = 1;
	$crtLetter = $this->items[0]->title[0];

	$html = array();
	$html[] = '<div class="yoorecipe-cont-results">';
	$html[] = '<div class="dropcap">'.$crtLetter.'</div>';
	$html[] = '<ul>';
	
	foreach($this->items as $recipe) {
	
		if ($crtLetter != $recipe->title[0]) {
			$crtLetter = $recipe->title[0];
			$html[] = '</ul>';
			$html[] = '<div class="dropcap">'.$crtLetter.'</div>';
			$html[] = '<ul>';
		}
		
		$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false);
		$html[] = '<li>';
		$html[] = '<a href="'.$url.'" title="'.htmlspecialchars($recipe->title).'" target="_self">';
		$html[] = htmlspecialchars($recipe->title);
		$html[] = '</a>';
		$html[] = '</li>';
	}
	
	$html[] = '</ul>';
	$html[] = '</div>';
	
	echo implode("\n", $html);
	
} // End if (count($this->items) > 0) {