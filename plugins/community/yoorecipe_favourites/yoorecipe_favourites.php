<?php
/*------------------------------------------------------------------------
# plg_community_yoorecipe_favourites - YooRecipe! Joomla 1.6 recipe component
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2011 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_community/libraries/core.php');
require_once(JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipeutils.php');
require_once(JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipejsutils.php');
require_once(JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipehelperroute.php');
require_once(JPATH_SITE.'/components/com_yoorecipe/helpers/html/yoorecipeicon.php');

class plgCommunityYoorecipe_Favourites extends CApplications{

	var $name 		= 'YooRecipe Profile Application';
	var $_name		= 'yoorecipe_favourites';
	var $_path		= '';
	var $_user		= '';
	var $_my		= '';
	var $code		= null;
	
	/** Constructor */
	function plgCommunityYoorecipe_Favourites (&$subject, $config) {
	
		$this->_user	= CFactory::getActiveProfile();
		$this->_my		= CFactory::getUser();
		$this->db 		= JFactory::getDBO();
		
  		parent::__construct($subject, $config);
	}
   
	function onProfileDisplay(){
	
		JPlugin::loadLanguage( 'plg_community_yoorecipe_favourites', JPATH_SITE );
		
		// Load yoorecipe language files
		$lang 		= JFactory::getLanguage();
		$lang->load('com_yoorecipe', JPATH_SITE, $lang->getTag(), $reload=true);
		
		// Load config objects
		$config			= CFactory::getConfig();
		$myJconfig 		= JFactory::getConfig();
		$juri 			= JURI::base();
		$document		= JFactory::getDocument();
		$this->loadUserParams();
		
		// Load css
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::base().'plugins/community/yoorecipe_favourites/yoorecipe_favourites/style.css');
	
		ob_start();
		
		$appfiles_path 	= 'plugins/community/yoorecipe_favourites/yoorecipe_favourites/';
		$my_id 			= $this->_my->id;
		
		$yooRecipeparams = JComponentHelper::getParams('com_yoorecipe');
		if ($yooRecipeparams->get('use_favourites', 1)) {
			
			$document 	= JFactory::getDocument();
			$document->addScriptDeclaration($this->getAddToFavouritesScript());
			$document->addScriptDeclaration($this->removeFromFavouritesScript());
		}
		
		$recipes = $this->_getRecipes($my_id);
		
		echo $this->_displayRecipes($recipes, $my_id);
		$contents	= ob_get_contents();
		ob_end_clean();		
		return $contents;	    		
	}
	
	function onAppDisplay()
	{
		ob_start();
		echo $this->onProfileDisplay($limit);;
		$content = ob_get_contents();
		ob_end_clean(); 
	
		return $content;
	}
	
	function _getRecipesQuery($user_id) {
	
		// Create a new query object.		
		$user	= JFactory::getUser($user_id);
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Select some fields
		$query->select( 'SQL_CALC_FOUND_ROWS r.id, r.access, r.title, r.alias, r.description, r.created_by, r.preparation, r.servings_type' .
				', r.nb_persons, r.difficulty, r.cost, r.carbs, r.fat, r.saturated_fat, r.proteins, r.fibers, r.salt' .
				', r.kcal, r.kjoule, r.diet, r.veggie, r.gluten_free, r.lactose_free, r.creation_date, r.publish_up' .
				', r.publish_down, r.preparation_time, r.cook_time, r.wait_time, r.picture, r.video, r.published' .
				', r.validated, r.featured, r.nb_views, r.note, r.metadata, r.metakey' .
				', r.price');
		$query->select('CASE WHEN CHARACTER_LENGTH(r.alias) THEN CONCAT_WS(\':\', r.id, r.alias) ELSE r.id END as slug');
		$query->select('CASE WHEN CHARACTER_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug');
		$query->select('CASE WHEN fr.recipe_id = r.id THEN 1 ELSE 0 END as favourite');
		
		// From the recipe table
		$query->from('#__yoorecipe as r');
		
		// Join over Cross Categories
		$query->join('LEFT', '#__yoorecipe_categories cc on cc.recipe_id = r.id');

		// Join over Categories
		$query->join('LEFT', '#__categories c on c.id = cc.cat_id');
		
		// Join over favourites
		$query->join('LEFT', '#__yoorecipe_favourites AS fr ON fr.recipe_id = r.id AND fr.user_id = ' . $db->quote($user->id));
		
		// Join over the users for the author.
		$yooRecipeparams 	= JComponentHelper::getParams( 'com_yoorecipe' );
		$showAuthorName 	= $yooRecipeparams->get('show_author_name', 'username');
		
		if ($showAuthorName == 'username') {
			$query->select('ua.username AS author_name');
		} else if ($showAuthorName == 'name') {
			$query->select('ua.name AS author_name');
		}
		$query->join('LEFT', '#__users AS ua ON ua.id = r.created_by');
	
		// Prepare where clause
		$query->where('r.published = 1');
		$query->where('r.validated = 1');
		$query->where('fr.user_id = ' . $db->quote($user->id));
		
		// Filter by access level.
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$query->where('r.access IN ('.$groups.')');
		
		// Filter by language
		$query->where('r.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		
		// Filter by start and end dates.
		$nullDate 	= $db->quote($db->getNullDate());
		$nowDate 	= $db->quote(JFactory::getDate()->toMySQL());

		$query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
		$query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
		
		// Prepare order by clause
		$query->order('r.title asc');
		$query->group('r.id');
		
		return $query;
	}
		
	function _getRecipes($my_id){
		
		$db = JFactory::getDBO();
		$query = $this->_getRecipesQuery($my_id);
		$db->setQuery((string) $query);
		return $db->loadObjectList();
	}
	
	function _displayRecipes($recipes, $user_id) {

		$document		 = JFactory::getDocument();
		$yooRecipeparams = JComponentHelper::getParams('com_yoorecipe');
		$user	= JFactory::getUser($user_id);
		$html 	= array();
		
		// Params
		$isPictureClickable = $this->params->get('is_picture_clickable', 1);
		$ratingStyle 	= 'stars';
		
		// Loop over recipes
		$cnt 	= 1;
		foreach($recipes as $recipe) {
		
			$recipe->canEdit		= $user->guest != 1 && ($user->authorise('core.edit', 'com_yoorecipe') || ($user->authorise('core.edit.own', 'com_yoorecipe') && $recipe->created_by == $user->id)) ;
			$recipe->canDelete	 	= $user->guest != 1 && ($user->authorise('core.delete', 'com_yoorecipe') || ($user->authorise('core.delete.own', 'com_yoorecipe') && $recipe->created_by == $user->id));
			
			if ($recipe->canDelete) {
				$document->addScriptDeclaration($this->_getDeleteAJAXScript($recipe->id));
			}
				
			$url = JRoute::_(JHtml::_('YooRecipeHelperRoute.getreciperoute', $recipe->slug, $recipe->catslug) , false);
	
			$html[] = '<div id="div-fav-recipe-' . $recipe->id .'" class="yoorecipe-row row-' . $cnt++ . '">';
			$html[] = '<div class="yoorecipe-row-item">';
			
			$html[] = '<div class="recipe-picture">';
			$html[] = JHtml::_('yoorecipeutils.generateRecipePicture', $recipe->picture, $recipe->title, $isPictureClickable, $url, 100);
			
			$html[] = '<div class="recipe-rating"><center>';
			if ($recipe->note != null)
			{
				if ($ratingStyle == 'grade') {
					$html[] = '<strong>' . JText::_('COM_YOORECIPE_RECIPE_NOTE') . ': </strong><span> ' . $recipe->note . '/5</span>'; 
				}
				else if ($ratingStyle == 'stars') {
					$rating = round($recipe->note);
					for ($j = 1 ; $j <= 5 ; $j++) {
						if ($rating >= $j) {
							$html[] = '<img src="media/com_yoorecipe/images/star-icon.png" alt=""/>';
						}
						else {
							$html[] = '<img src="media/com_yoorecipe/images/star-icon-empty.png" alt=""/>';
						}
					}
				}
			} else { $html[] ='&nbsp;';}
			$html[] = '</center></div>';
			$html[] = '</div>';
			
			$html[] = '<div class="recipe-container">';
			$html[] = '<div class="recipe-title">';
			$html[] = '<a href="' . $url . '" title="'. htmlspecialchars($recipe->title) . '" target="_self">' . htmlspecialchars($recipe->title) . '</a>';
			$html[] = '</div>';
		
			$html[] = '<div class="fav-icon" id="plg_yoo_fav_' . $recipe->id . '">';
			if ($recipe->favourite) {
				$html[] = '<a onclick="yrfav_removeFromFavourites('.$recipe->id.')"><img src="media/com_yoorecipe/images/favourites.png" alt="'.JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES').'" title="'.JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES').'"/></a>';
			} else {
				$html[] = '<a onclick="yrfav_addToFavourites('.$recipe->id.')"><img src="media/com_yoorecipe/images/add-to-favourites.png" alt="'.JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES').'" title="'.JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES').'"/></a>';
			}
			$html[] = '</div>';
			
			$html[] = '<div class="recipe-desc">'.$recipe->description.'</div>';
			
			$html[] = '<div class="recipe-btns">';
			$html[] = '<span id="span-fav-recipe-' . $recipe->id . '" class="yoorecipe-loading"></span>';
			if ($recipe->canEdit) {
				$editUrl = JRoute::_('index.php?option=com_yoorecipe&view=form&layout=edit&id=' . $recipe->slug);
				$html[] = '<button type="button" class="btn" onclick="window.location=\'' . $editUrl . '\'">' . JText::_('COM_YOORECIPE_EDIT') . '</button>&nbsp;';
			}
			if ($recipe->canDelete) {
				$html[] = '<button type="button" class="btn" id="btn_fav_delete_' . $recipe->id . '">' . JText::_('COM_YOORECIPE_DELETE') . '</button>';
			}
			$html[] = '</div>';
			$html[] = '</div>';
			$html[] = '</div>';
			$html[] = '</div>';
		}
		
		return implode("\n", $html);
	}
		
	public function _getDeleteAJAXScript($recipe_id) {
	
		return "window.addEvent('domready', function () {
			
	$('btn_fav_delete_".$recipe_id."').addEvent('click', function () {

	if (confirm('".addslashes(JText::_('PLG_COMMUNITY_YOORECIPE_CONFIRM'))."')) {
	
		$('span-fav-recipe-".$recipe_id."').setStyle('display', 'block');
		var url = '".JURI::root()."index.php?option=com_yoorecipe&task=deleteRecipe';
		var a = new Request.HTML({
			url: url,
			method:'post',
			data:'format=raw&id='+" . $recipe_id . ",
			onSuccess:function(response){
				
				if (response.match('^'+'NOK')) {
					alert(response.substr(4,response.length-1));
					$('span-fav-recipe-".$recipe_id."').setStyle('display', 'none');
				} else {
					$('div-fav-recipe-".$recipe_id."').set('tween', {
						duration: 600
					}).fade('out').get('tween').chain(function() {
						$('div-fav-recipe-".$recipe_id."').tween('height',0);
					}).chain(function () {
						$('div-fav-recipe-".$recipe_id."').dispose();
					});
				}
			},
			onFailure: function(){
				alert('" . JText::_('PLG_COMMUNITY_YOORECIPE_ERROR_OCCURED') . "');
			} 
		}).send();
	}
		});	});";
	}
	
	/**
	 * Add to favourites ajax call
	 */
	public function getAddToFavouritesScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=addToFavourites&format=raw&recipeId=@@recipe@@';
		return "//<![CDATA[
		function yrfav_addToFavourites(recipeId) {
			
			var raw_url = '" . $url . "';
			var url = raw_url.replace('@@recipe@@',recipeId);
			$('plg_yoo_fav_'+recipeId).empty().addClass('ajax-loading');
			
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(result){
				
					$('plg_yoo_fav_'+recipeId).removeClass('ajax-loading');
					var a = new Element('a');
					a.addEvent('click', function() { yrfav_removeFromFavourites(recipeId); }); 
					var img = new Element('img', {'title' : '".addslashes(JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES'))."', 'alt': '".addslashes(JText::_('COM_YOORECIPE_REMOVE_FROM_FAVOURITES'))."', 'src': 'media/com_yoorecipe/images/favourites.png'});
					img.inject(a);
					a.inject($('plg_yoo_fav_'+recipeId));
				},
				onFailure: function(){
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
	public function removeFromFavouritesScript()
	{
		$url = JURI::root().'index.php?option=com_yoorecipe&task=removeFromFavourites&format=raw&recipeId=@@recipe@@';
		return "//<![CDATA[
		function yrfav_removeFromFavourites(recipeId) {
			
			var raw_url = '" . $url . "';
			var url = raw_url.replace('@@recipe@@',recipeId);
			
			$('plg_yoo_fav_'+recipeId).empty().addClass('ajax-loading');
			var x = new Request({
				url: url, 
				method: 'post', 
				onRequest: function(){ },
				onSuccess: function(result){
					
					$('plg_yoo_fav_'+recipeId).removeClass('ajax-loading');
					var a = new Element('a');
					a.addEvent('click', function() {yrfav_addToFavourites(recipeId); }); 
					var img = new Element('img', {'title' : '".addslashes(JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES'))."', 'alt': '".addslashes(JText::_('COM_YOORECIPE_ADD_TO_FAVOURITES'))."', 'src': 'media/com_yoorecipe/images/add-to-favourites.png'});
					img.inject(a);
					a.inject($('plg_yoo_fav_'+recipeId));
				},
				onFailure: function(){
					alert('" . addslashes(JText::_('COM_YOORECIPE_ERROR_FORBIDDEN_DELETE_OPERATION')) ." ');
				}                
			}).send();
		}
		/* ]]> */
		";
	}
}