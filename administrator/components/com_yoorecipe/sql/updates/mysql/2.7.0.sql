
ALTER TABLE `#__yoorecipe_rating` ADD `published` BOOL NOT NULL AFTER `comment`;
UPDATE `#__yoorecipe_rating` SET `published` = 1;
ALTER TABLE `#__yoorecipe_rating` ADD `abuse` BOOL NOT NULL AFTER `published` ;
UPDATE `#__yoorecipe_rating` SET `abuse` = 0;
ALTER TABLE `#__yoorecipe` ADD `video` VARCHAR( 255 ) NOT NULL AFTER `picture` ;
ALTER TABLE `#__yoorecipe` ADD `metakey` TEXT NULL AFTER `note` ;
ALTER TABLE `#__yoorecipe` ADD `metadata` TEXT NULL AFTER `metakey` ;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_favourites` (
`recipe_id` INT( 11 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

DELETE FROM `#__yoorecipe_rating` where recipe_id not in (select r.id from `#__yoorecipe` r);
DELETE FROM `#__yoorecipe_categories` where recipe_id not in (select r.id from `#__yoorecipe` r);
DELETE FROM `#__yoorecipe_ingredients` where recipe_id not in (select r.id from `#__yoorecipe` r);

ALTER TABLE `#__yoorecipe` ADD INDEX ( `published`,`validated`) ;
ALTER TABLE `#__yoorecipe` ADD INDEX ( `id`) ;
ALTER TABLE `#__yoorecipe_categories` ADD INDEX ( `recipe_id`, `cat_id` ) ;
ALTER TABLE `#__yoorecipe_favourites` ADD INDEX ( `recipe_id`, `user_id` ) ;

update `#__yoorecipe` set picture = REPLACE(picture , '//image', '/image') where picture like '%//image%';