CREATE TABLE IF NOT EXISTS `#__yoorecipe_ingredients_groups` (

	`id` int(11) NOT NULL AUTO_INCREMENT,
	`text` VARCHAR(50) NOT NULL,

	PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_RECIPE');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_SAUCE');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_SIDE_DISH');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_CREAM');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_TART');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_PASTRY');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_EMULSION');
INSERT INTO `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_STUFFING');

ALTER TABLE `#__yoorecipe_ingredients` ADD COLUMN `group_id` INT( 11 ) NOT NULL AFTER `recipe_id`;

UPDATE `#__yoorecipe_ingredients` set group_id = 1;

ALTER TABLE `#__yoorecipe` CHANGE `carbs` `carbs` DOUBLE NULL DEFAULT NULL ;
ALTER TABLE `#__yoorecipe` CHANGE `fat` `fat` DOUBLE NULL DEFAULT NULL ;
ALTER TABLE `#__yoorecipe` CHANGE `saturated_fat` `saturated_fat` DOUBLE NULL DEFAULT NULL ;
ALTER TABLE `#__yoorecipe` CHANGE `proteins` `proteins` DOUBLE NULL DEFAULT NULL ;
ALTER TABLE `#__yoorecipe` CHANGE `fibers` `fibers` DOUBLE NULL DEFAULT NULL ;
ALTER TABLE `#__yoorecipe` CHANGE `salt` `salt` DOUBLE NULL DEFAULT NULL ;
