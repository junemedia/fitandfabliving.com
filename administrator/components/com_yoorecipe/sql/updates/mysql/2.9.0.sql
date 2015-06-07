ALTER TABLE `#__yoorecipe` ADD `language` CHAR(7) NOT NULL DEFAULT '*';
ALTER TABLE `#__yoorecipe` ADD `price` DOUBLE NULL;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_tags` (

	`tag_id` int(11) NOT NULL AUTO_INCREMENT,
	`recipe_id` int(11) NOT NULL,
	`tag_value` VARCHAR(50) NOT NULL,

	PRIMARY KEY (`tag_id`)

)  ENGINE=MyISAM DEFAULT CHARSET=utf8;