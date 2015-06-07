<?php
/*------------------------------------------------------------------------
# com_yoorecipe
# ------------------------------------------------------------------------
# author    YooRock!
# copyright Copyright (C) 2012 yoorock.fr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://extensions.yoorock.fr
# Technical Support:  Forum - http://extensions.yoorock.fr/
-------------------------------------------------------------------------*/
$input 	= JFactory::getApplication()->input;
$msg = $input->get('msg', '', 'STRING');
echo $this->escape(JText::_($msg));