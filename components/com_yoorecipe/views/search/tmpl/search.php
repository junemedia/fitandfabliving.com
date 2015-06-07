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

// Component Parameters
$yooRecipeparams 			= JComponentHelper::getParams('com_yoorecipe');
$yoorecipe_layout			= $yooRecipeparams->get('yoorecipe_layout', 'twocols');

$document = JFactory::getDocument();
$document->addStyleSheet('media/com_yoorecipe/styles/yoorecipe_'.$yoorecipe_layout.'.css');
?>

<div>
<h1 class="yoorecipe-h1"><?php echo JText::_('COM_YOORECIPE_SEARCH_RECIPE'); ?></h1>

<?php 
	if ($this->error==null && count($this->items) > 0) :
		echo $this->loadTemplate('results');
	else :
		echo $this->loadTemplate('form');
		echo $this->loadTemplate('error');
	endif;
?>
</div>