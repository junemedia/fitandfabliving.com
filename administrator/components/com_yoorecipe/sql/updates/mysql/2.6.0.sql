ALTER TABLE `#__yoorecipe` ADD `asset_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.' AFTER `id` ,
ADD `access` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `asset_id` ;

UPDATE `#__yoorecipe` SET access = 1;

ALTER TABLE `#__yoorecipe` ADD `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `creation_date`;
ALTER TABLE `#__yoorecipe` ADD `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_up`;