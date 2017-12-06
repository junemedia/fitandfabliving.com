<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('mosets.profilepicture.profilepicture');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$menuItem = JSite::getMenu()->getActive();

$isPrivacy = false;
if (!empty($menuItem) &&
    ($menuItem->route == 'privacy-policy' ||
     $menuItem->route == 'terms-of-use' ||
     $menuItem->route == 'aboutus')) {
  $isPrivacy = true;
}

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$canEdit = '';  // we don't want to allow users to edit recipes from front-end. Samir 9/5/13
$user    = JFactory::getUser();
$picture = new ProfilePicture($this->item->created_by);
$headimage = $picture->getURL('200');
if (!$headimage) {
  $headimage="images/headimg_reserve.png";
}
$info    = $params->get('info_block_position', 0);
$getapps= & JFactory::getApplication();
$template = $getapps->getTemplate();
$baseurl = substr(JURI::base(),0,-1);
JHtml::_('behavior.caption');

function cutWord($str,$length) {
  $str = preg_replace("/<img.*?>/si", "", $str);
  if ($str[0] == '"') {
    $str = substr($str, 1, strlen($str));
  }
  if (strlen($str) <= $length) {
    return $str;
  }
  else {
    $pos = strrpos(substr($str, 0, $length), ' ', -1);
    return substr($str, 0, $pos) . " ... ";
  }
}
$authorLink = JRoute::_('index.php?option=com_users&view=author&user_name='.$this->item->authorAccount);

$isSlide = false;
if (isset($this->item->toc)) {
  $isSlide = true;
}
?>

<div class="item-page<?php echo $this->pageclass_sfx?>">

<?php
  // connatix on non-slideshow pages only
  if (!$isSlide) {
    include 'templates/protostar/partials/ads/connatix_infeed.html';
  }
?>

<?php if ($params->get('show_category')) { ?>
  <div class="breadcrumb">

  <?php
  $title = $this->escape($this->item->category_title);
  $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';

  if ($params->get('link_category') && $this->item->catslug) { ?>
    <a href="<?php echo JURI::base();?>" class="bread_home">HOME</a> /
    <span><?php echo $url;?></span>
  <?php
  } else {
    echo JText::sprintf('COM_CONTENT_CATEGORY', $title);
  }
  ?>
  </div><!-- /.breadcrumb -->
<?php } ?>



  <div class="article">
    <div id="article_details" class="<?php echo $isSlide ? 'article_slideshow' : ''; ?>">

    <?php
    if (!empty($this->item->pagination) &&
        $this->item->pagination &&
        !$this->item->paginationposition &&
        $this->item->paginationrelative) {
      echo $this->item->pagination;
    }

    if ($params->get('show_title') || $params->get('show_author')) {
      if ($this->item->state == 0) { ?>

      <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>

      <?php
      }
      if ($params->get('show_title')) {
        if ($params->get('link_titles') && !empty($this->item->readmore_link)) { ?>

      <a href="<?php echo $this->item->readmore_link; ?>">
        <h1 class="article_title"><?php echo $this->escape($this->item->title); ?></h1>
      </a>
      <h3 class="article_subtitle"><?php echo $this->escape($this->item->subtitle);?></h3>

          <?php if ($this->item->sponsored == 1 && !$isSlide) { ?>

      <div><font class="sponsored" size='1'>Sponsored</font></div>

          <?php }
        } else {
          echo $this->escape($this->item->title);
        }
      }
    }

    if (!$this->print) {
      if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) { ?>

      <div class="btn-group pull-right">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>

        <?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
        <ul class="dropdown-menu actions">
          <?php if ($params->get('show_print_icon')) { ?>
          <li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
          <?php } ?>
          <?php if ($params->get('show_email_icon')) { ?>
          <li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
          <?php } ?>
          <?php if ($canEdit) { ?>
          <li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
          <?php } ?>
        </ul>
      </div>

      <?php }
    }
    else { ?>

      <div class="pull-right">
        <?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
      </div>

    <?php } ?>

    <?php $useDefList = ($params->get('show_modify_date') ||
                         $params->get('show_publish_date') ||
                         $params->get('show_create_date') ||
                         $params->get('show_hits') ||
                         $params->get('show_category') ||
                         $params->get('show_parent_category') ||
                         $params->get('show_author'));
    if ($useDefList && ($info == 0 || $info == 2) && !$isPrivacy) {
      if ($isSlide && $this->item->sponsored == 1) {
        echo '';
      }
      else { ?>

      <div class="author_info">

        <?php if (!empty($this->item->author)) { ?>

        <img style="width:66px;height:66px;" src="<?php echo $headimage; ?>"/>

          <?php
          $author = $this->item->author;
          if (!empty($this->item->contactid) && $params->get('link_author') == true) {
            $needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
            $menu = JFactory::getApplication()->getMenu();
            $item = $menu->getItems('link', $needle, true);
            $cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
            ?>

        <span>by </span> <a href="<?php echo $authorLink;?>"><span class="name"><?php echo $author; ?></span></a>

          <?php } else { ?>

        <span>by </span> <a href="<?php echo $authorLink;?>"><span class="name"><?php echo $author; ?></span></a>

          <?php }
        } ?>

        <?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) { ?>
        <dd class="parent-category-name">
          <?php $title = $this->escape($this->item->parent_title);
          $url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
          <?php
          if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) {
            echo JText::sprintf('COM_CONTENT_PARENT', $url);
          } else {
            echo JText::sprintf('COM_CONTENT_PARENT', $title);
          }
          ?>
        </dd>
        <?php } ?>

        <?php if ($params->get('show_publish_date')) { ?>
        <dd class="published">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>
        <?php } ?>

        <?php if ($info == 0) {
          if ($params->get('show_modify_date')) { ?>

        <dd class="modified">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>

          <?php
          }

          if ($params->get('show_create_date')) { ?>

        <dd class="create">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>

          <?php
          }

          if ($params->get('show_hits')) { ?>

        <dd class="hits">
          <span class="icon-eye-open"></span>
          <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
        </dd>

          <?php
          }
        } ?>
      </div><!-- /.author_info -->

      <?php }
    } ?>

    <div class="share_recipe">
    <?php if ($this->item->sponsored == 1 && $isSlide) { ?>
      <div class="sponsored"><font class="sponsored" size='1'>Sponsored</font></div>
    <?php }?>

      <div class="share_icon">
        <ul>
          <li>
            <a href="http://www.facebook.com/sharer.php?u=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank">
              <img src="<?php echo $baseurl."/templates/".$template."/images/FFL-facebook-box.png";?>"/></a></li>
          <li>
            <a href="https://twitter.com/share?original_referer=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank">
              <img src="<?php echo $baseurl."/templates/".$template."/images/FFL-twitter-box.png";?>"/></a></li>
          <li>
            <a href="http://pinterest.com/pin/create/button/?url=<?php echo $baseurl.$this->item->readmore_link;?>&media=<?php echo $baseurl.'/'.htmlspecialchars($images->image_fulltext);?>" target="_blank">
              <img src="<?php echo $baseurl."/templates/".$template."/images/FFL-pinterest-box.png";?>"/></a></li>
          <li>
            <a href="https://plus.google.com/share?url=<?php echo $baseurl.$this->item->readmore_link;?>" target="_blank">
              <img src="<?php echo $baseurl."/templates/".$template."/images/FFL-googleplus-box.png";?>"/></a></li>
          <li>
            <a href="<?php echo $baseurl.$this->item->readmore_link;?>?tmpl=component&amp;print=1&amp;layout=default&amp;page=" title="Print" onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;" rel="nofollow">
              <img src="<?php echo $baseurl."/templates/".$template."/images/FFL-print-box.png";?>"/></a></li>
        </ul>
        <script type="text/javascript">var switchTo5x = false;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher:'0541fe9f-2a3f-4c01-ac74-8f02c84e7fde'});</script>
      </div><!-- /.share_icon -->
    </div><!-- /.share_recipe -->

    <div <?php echo $isSlide?'class="s_line"':''?> style="clear:both;"></div>

    <?php
    if ($params->get('show_tags', 1) && !empty($this->item->tags)) {
      $this->item->tagLayout = new JLayoutFile('joomla.content.tags');
      echo $this->item->tagLayout->render($this->item->tags->itemTags);
    }

    if (!$params->get('show_intro')) {
      echo $this->item->event->afterDisplayTitle;
    }

    echo $this->item->event->beforeDisplayContent;

    if (isset($urls) &&
        ((!empty($urls->urls_position) && ($urls->urls_position == '0')) ||
         ($params->get('urls_position') == '0' && empty($urls->urls_position))) ||
        (empty($urls->urls_position) && (!$params->get('urls_position')))) {
      echo $this->loadTemplate('links');
    } ?>

    <div class="article_content">
    <?php
    if ($params->get('access-view')) {
      if (isset($images->image_fulltext) && !empty($images->image_fulltext)) {
        $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>

      <div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image">
        <img
        <?php
        if ($images->image_fulltext_caption) {
          echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) . '"';
        } ?>
            src="<?php echo htmlspecialchars($images->image_fulltext); ?>"
            alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
      </div>
      <?php }

      if (!empty($this->item->pagination) &&
          $this->item->pagination &&
          !$this->item->paginationposition &&
          !$this->item->paginationrelative) {
        echo $this->item->pagination;
      };

      if (isset ($this->item->toc)) {
        echo $this->item->toc;
      }

      $slideImages = $this->item->pageImage->images;
      $page = $this->item->pageImage->page;
      ?>

      <div class="article_content_detail">
      <?php
      if ($isSlide) {
        $itemIds = array(8513);
        if (in_array($this->item->id,$itemIds)) { ?>

        <div style="text-align:center;">
          <img style="<?php if($this->item->id==8513 && $page !=6){echo 'width:500px;';}?>" src="<?php echo $slideImages[$page];?>" />
        </div>

        <?php }
        else { ?>

        <div style="text-align:center;"><img src="<?php echo $slideImages[$page];?>" /></div>

        <?php }

        echo $this->removeSlideImage($this->item->text);
      }
      else {
        echo $this->item->text;
      } ?>
      </div><!-- /.article_content_detail -->
    </div><!-- /.article_content -->

      <?php if ($useDefList && ($info == 1 || $info == 2)) { ?>

    <div class="article-info muted">
      <dl class="article-info">
        <dt class="article-info-term">
          <?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
        </dt>

        <?php if ($info == 1) {
          if ($params->get('show_author') && !empty($this->item->author )) { ?>

        <dd class="createdby">
            <?php
            $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
            if (!empty($this->item->contactid) && $params->get('link_author') == true) {
              $needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
              $menu = JFactory::getApplication()->getMenu();
              $item = $menu->getItems('link', $needle, true);
              $cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
              echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author));
            }
            else {
              echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author);
            } ?>
        </dd>

          <?php }

          if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) { ?>
        <dd class="parent-category-name">
            <?php
            $title = $this->escape($this->item->parent_title);
            $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';
            if ($params->get('link_parent_category') && $this->item->parent_slug) :
              echo JText::sprintf('COM_CONTENT_PARENT', $url);
            else :
              echo JText::sprintf('COM_CONTENT_PARENT', $title);
            endif; ?>
        </dd>
          <?php }

          if ($params->get('show_category')) { ?>
        <dd class="category-name">
            <?php
            $title = $this->escape($this->item->category_title);
            $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';
            if ($params->get('link_category') && $this->item->catslug) {
              echo JText::sprintf('COM_CONTENT_CATEGORY', $url);
            }
            else {
              echo JText::sprintf('COM_CONTENT_CATEGORY', $title);
            } ?>
        </dd>
          <?php }

          if ($params->get('show_publish_date')) { ?>
        <dd class="published">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>
          <?php }
        }

        if ($params->get('show_create_date')) { ?>
        <dd class="create">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>
        <?php }

        if ($params->get('show_modify_date')) { ?>
        <dd class="modified">
          <span class="icon-calendar"></span>
          <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
        </dd>
        <?php }

        if ($params->get('show_hits')) { ?>
        <dd class="hits">
          <span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
        </dd>
        <?php } ?>
      </dl>
    </div><!-- ./article-info muted -->
      <?php } ?>

      <?php
      if (!empty($this->item->pagination) &&
          !$isPrivacy &&
          $this->item->pagination &&
          $this->item->paginationposition &&
          !$this->item->paginationrelative &&
          !$isSlide) {
        echo $this->item->pagination;
      }
      if (isset($urls) &&
          ((!empty($urls->urls_position) &&
          ($urls->urls_position == '1')) ||
          ($params->get('urls_position') == '1'))) {
        echo $this->loadTemplate('links');
      }
    }
    elseif ($params->get('show_noauth') == true && $user->get('guest')) {
      // Optional teaser intro text for guests
      echo $this->item->introtext;
      //Optional link to let them register to see the whole article.
      if ($params->get('show_readmore') && $this->item->fulltext != null) {
        $link1 = JRoute::_('index.php?option=com_users&view=login');
        $link = new JUri($link1);?>

    <p class="readmore">
      <a href="<?php echo $link; ?>">

        <?php
        $attribs = json_decode($this->item->attribs);

        if ($attribs->alternative_readmore == null) {
          echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
        }
        elseif ($readmore = $this->item->alternative_readmore) {
          echo $readmore;
          if ($params->get('show_readmore_title', 0) != 0) {
            echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
          }
        }
        elseif ($params->get('show_readmore_title', 0) == 0) {
          echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
        }
        else {
          echo JText::_('COM_CONTENT_READ_MORE');
          echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
        } ?>

      </a>
    </p>
      <?php }
    }

    if (!empty($this->item->pagination) &&
        !$isPrivacy &&
        $this->item->pagination &&
        $this->item->paginationposition &&
        $this->item->paginationrelative &&
        !$isSlide) {
      echo $this->item->pagination;
    }
    ?>
    </div><!-- /.article_details -->

    <div style="margin:30px auto">
      <?php include 'templates/protostar/partials/ads/lockerdome.html'; ?>
    </div>

    <?php if (!$isPrivacy) { ?>
    <div id="more_articles">
      <h2 class="content_h2">MORE FROM <?php echo $title;?></h2>
      <div class="s_line"></div>
      <?php
      $i = 0;
      foreach ($this->moreList as $moreItem) { ?>
      <div class="article_box <?php echo $i!=0?"box_top_space":""; $i++;?>">
        <img src="<?php if(!isset($moreItem->images['image_intro']) || empty($moreItem->images['image_intro'])) {echo JURI::base()."templates/".$template."/images/image_reserve.png";} else {echo $moreItem->images['image_intro'];}?>" class="article_box_img"/>
        <div class="article_box_content">
          <a href="<?php echo $moreItem->readmore_link;?>">
            <h2 class="article_box_title"><?php echo $moreItem->title;?></h2>
          </a>
          <div class="article_box_brief">
            <?php echo getplaintextintrofromhtml($moreItem->text,120);?> <a href="<?php echo $moreItem->readmore_link;?>" class="read_more">read more</a>
          </div>
        </div>
      </div>
      <?php }?>
    </div><!-- /#more_articles -->
    <?php }?>
  </div><!-- /.article -->
  <?php echo $this->item->event->afterDisplayContent; ?>
</div><!-- /.item-page -->
