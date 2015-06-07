<?php 
/**
 * @package      ITPrism Modules
 * @subpackage   ITPShare
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPShare is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('_JEXEC') or die;?>
<div class="itp-share-mod<?php echo $moduleclass_sfx;?>">
    <?php
    echo ItpShareHelper::getTwitter($params, $url, $title);
    echo ItpShareHelper::getStumbpleUpon($params, $url);
    echo ItpShareHelper::getLinkedIn($params, $url);
    echo ItpShareHelper::getTumblr($params, $url);
    echo ItpShareHelper::getBuffer($params, $url, $title);
    echo ItpShareHelper::getPinterest($params, $url, $title);
    echo ItpShareHelper::getReddit($params, $url, $title);
    echo ItpShareHelper::getFacebookLike($params, $url);
    echo ItpShareHelper::getGooglePlusOne($params, $url);
    echo ItpShareHelper::getGoogleShare($params, $url);
    echo ItpShareHelper::getExtraButtons($params, $url, $title);
    ?>
</div>
<div style="clear:both;"></div>