<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// JLayout for standard handling of the details sidebar in administrator edit screens.
$title = $displayData->get('form')->getValue('title');
$published = $displayData->get('form')->getField('published');
?>
<div class="span2">
<h4><?php echo JText::_('JDETAILS');?></h4>
			<hr />
			<fieldset class="form-vertical">
				<?php if (empty($title)) : ?>
					<div class="control-group">
						<div class="controls">
							<?php echo $displayData->get('form')->getValue('name'); ?>
						</div>
					</div>
				<?php else : ?>
				<div class="control-group">
					<div class="controls">
						<?php echo $displayData->get('form')->getValue('title'); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($published) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $displayData->get('form')->getLabel('published'); ?>
						</div>
						<div class="controls">
							<?php echo $displayData->get('form')->getInput('published'); ?>
						</div>
					</div>
				<?php else : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $displayData->get('form')->getLabel('state'); ?>
						</div>
						<div class="controls">
							<?php echo $displayData->get('form')->getInput('state'); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('access'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('access'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('featured'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('featured'); ?>
					</div>
				</div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $displayData->get('form')->getLabel('homepagefeatured'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $displayData->get('form')->getInput('homepagefeatured'); ?>
                    </div>
                </div>
				<div class="control-group">
                    <div class="control-label">
                        <?php echo $displayData->get('form')->getLabel('whatsnewfeatured'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $displayData->get('form')->getInput('whatsnewfeatured'); ?>
                    </div>
                </div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('publish_up'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('publish_up'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('publish_down'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('publish_down'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('language'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('language'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $displayData->get('form')->getLabel('tags'); ?>
					</div>
					<div class="controls">
						<?php echo $displayData->get('form')->getInput('tags'); ?>
					</div>
				</div>
			</fieldset>
		</div>

