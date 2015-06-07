<?php
/*------------------------------------------------------------------------
# com_yoorecipe - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/forum
-------------------------------------------------------------------------*/
// ------------------ standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;
$shLangName = '';;
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
// ------------------ standard plugin initialize function - don't change ---------------------------

jimport('joomla.filter.filteroutput');
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');

// Remove itemId
if (!empty($Itemid)) { shRemoveFromGETVarsList('Itemid'); }

// Remove optional pagination
if (!empty($limit))	{ shRemoveFromGETVarsList('limit'); }
if (isset($limitstart)) { shRemoveFromGETVarsList('limitstart'); }

// Remove optional controller 
if (!empty($controller)) { shRemoveFromGETVarsList('controller'); }
		
$params 			= JComponentHelper::getParams('com_yoorecipe');
$show_author_name 	= $params->get('show_author_name', 'username');

$lang = JFactory::getLanguage();
$lang->load('plg_sh404sefextplugincom_yoorecipe', $base_dir = JPATH_ADMINISTRATOR);

if(isset($task))
{
	// index.php?option=com_yoorecipe&controller=yoorecipe&task=viewRecipe&id=4:brochette-de-saint-jacques-et-chorizo&lang=en
	if ($task == 'viewRecipe') {

		$title[] = $params->get('seo_recipe', 'recipe');
		shRemoveFromGETVarsList('task');
		
		if(isset($id))
		{
			$q = 'SELECT alias FROM #__yoorecipe WHERE id = '.$database->Quote($id);
			$database->setQuery($q);
			$title[] = $database->loadResult();
			shRemoveFromGETVarsList('id');
		}
	}
	
	// index.php?option=com_yoorecipe&controller=yoorecipe&task=viewCategory&id=79:plats&lang=en
	else if ($task == 'viewCategory') {
	
		$title[] = $params->get('seo_categories', 'category');
		shRemoveFromGETVarsList('task');
		
		if(isset($id))
		{
			$q = 'SELECT alias FROM #__categories WHERE id = '.$database->Quote($id).' AND extension = '.$database->Quote('com_yoorecipe');
			$database->setQuery($q);
			$title[] = $database->loadResult();
			shRemoveFromGETVarsList('id');
		}
	}
	
	// index.php?option=com_yoorecipe&controller=yoorecipe&task=viewByUser&id=692&lang=en
	else if ($task == 'viewByUser') {
		
		$name = '';
		if (isset($id)) {
			
			$q = 'SELECT ' . $show_author_name . ' FROM #__users WHERE id = '.$database->Quote($id);
			$database->setQuery($q);
			$name = $database->loadResult();
			shRemoveFromGETVarsList('id');
		}
		
		// $title[] = $params->get('seo_user', 'user');
		$title[] = JFilterOutput::stringURLSafe(JText::sprintf('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_BY_USER', $name));
		shRemoveFromGETVarsList('task');
		
	}
	
	// index.php?option=com_yoorecipe&task=getRecipesByLetter&l=A&lang=en
	else if ($task == 'getRecipesByLetter') {
		
		shRemoveFromGETVarsList('task');
		shRemoveFromGETVarsList('l');
		
		$title[] = $params->get('seo_letter', 'letter');
		$title[] = $l;
	}
	
	// index.php?option=com_yoorecipe&task=tags&value=Agneau&lang=en
	else if ($task == 'tags') {
		
		shRemoveFromGETVarsList('task');
		shRemoveFromGETVarsList('value');
		
		$title[] = JText::_('COM_YOORECIPE_TAGS');
		$title[] = $value;
	}
}

if (isset($view)) {

	switch ($view) {
		
		// index.php?option=com_yoorecipe&view=form&layout=edit
		case 'form':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_EDIT_RECIPE', 'edit-recipe'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=landingpage&layout=landingpage
		case 'landingpage':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_LANDING_PAGE', 'landing-page'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=favourites&layout=favourites
		case 'favourites':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_FAVOURITES', 'favourites-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=mostpopular&layout=mostpopular
		case 'mostpopular':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_BEST_RATED', 'best-rated-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=mostread&layout=mostread
		case 'mostread':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_MOST_READ', 'most-read-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=mostrecents&layout=mostrecents
		case 'mostrecents':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_MOST_RECENTS', 'latest-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=user&layout=user
		case 'user':
		
			$name = '';
			if (isset($id)) {
				
				$q = 'SELECT ' . $show_author_name . ' FROM #__users WHERE id = '.$database->Quote($id);
				$database->setQuery($q);
				$name = $database->loadResult();
				shRemoveFromGETVarsList('id');
			}
			
			// $title[] = $params->get('seo_user', 'user');
			$title[] = JFilterOutput::stringURLSafe(JText::sprintf('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_BY_USER', $name));
			
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=yoorecipe
		case 'yoorecipe':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_ALL_RECIPES', 'all-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=myrecipes&layout=myrecipes
		case 'myrecipes':
		
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_MYRECIPES', 'my-cookbook'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=featured&layout=featured
		case 'featured':
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_FEATURED', 'featured'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=search&layout=search
		case 'search':
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_SEARCH', 'search-recipes'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=archive
		case 'archive':
			$title[] = JFilterOutput::stringURLSafe(JText::_('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_ARCHIVE', 'recipes-list'));
			shRemoveFromGETVarsList('view');
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
		
		// index.php?option=com_yoorecipe&view=seasons
		case 'seasons':
			
			$title[] = JFilterOutput::stringURLSafe(JText::sprintf('PLG_SH404SEFEXTPLUGINCOM_YOORECIPE_VIEW_SEASONS', JText::_('COM_YOORECIPE_'.$month_id)));
			shRemoveFromGETVarsList('view');
			if (isset($month_id)) {
				shRemoveFromGETVarsList('month_id');
			}
			if (isset($layout)) {
				shRemoveFromGETVarsList('layout');
			}
		break;
	}
}

// ------------------ standard plugin finalize function - don't change ---------------------------
if ($dosef){
	$string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
	(isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
	(isset($shLangName) ? @$shLangName : null));
}
// ------------------ standard plugin finalize function - don't change ---------------------------