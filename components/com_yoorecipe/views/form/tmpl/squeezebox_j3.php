<?php
/*------------------------------------------------------------------------
# com_campingmanager
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
$input 	= JFactory::getApplication()->input;
$labels = $input->get('errcode', '', 'ARRAY');
echo '<ul>';
foreach ($labels as $label) {
	echo '<li>' . JText::_($label) . '</li>';
}
echo '</ul>';