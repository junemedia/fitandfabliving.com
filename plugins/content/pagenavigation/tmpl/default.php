<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//echo "<pre>";
//print_r($row);
//echo "</pre>";

?>
<ul class="pager pagenav" style="margin-bottom: 5px;">
<?php if ($row->prev) : ?>
	<li class="previous">
		<a href="<?php echo $row->prev; ?>" rel="prev" title="<?php echo $row->prev_title;?>">
        <?php 
            echo JText::_('JGLOBAL_LT') . $pnSpace . JText::_('JPREV');
            //echo $row->prev_title;
        ?>
        </a>  
	</li>
<?php endif; ?>
<?php if ($row->next) : ?>
	<li class="next">
		<a href="<?php echo $row->next; ?>" rel="next" title="<?php echo $row->next_title;?>"><?php echo JText::_('JNEXT') . $pnSpace . JText::_('JGLOBAL_GT'); ?></a>
	</li>
<?php endif; ?>
</ul>

<ul style="list-style: none; margin: 0px;">
<?php if ($row->prev) : ?>
    <li style="width: 300px; float: left; ">
        <h5 align="left" style=" font-family: Arial; font-size: 9px; margin-top: 0px;">
        <a href="<?php echo $row->prev; ?>" rel="prev" title="<?php echo $row->prev_title;?>">
        <?php 
            //echo JText::_('JGLOBAL_LT') . $pnSpace . JText::_('JPREV');
            echo $row->prev_title;
        ?>
        </a>
        </h5>  
    </li>
<?php endif; ?>
<?php if ($row->next) : ?>
    <li style="width: 300px; float: right;">
        <h5 align="right" style=" font-family: Arial; font-size: 9px; margin-top: 0px;">
        <a href="<?php echo $row->next; ?>" rel="prev" title="<?php echo $row->next_title;?>">
        <?php 
            //echo JText::_('JNEXT') . $pnSpace . JText::_('JGLOBAL_GT');
            echo $row->next_title; 
        ?>
        </a>
        </h5>
    </li>
<?php endif; ?>
</ul>
