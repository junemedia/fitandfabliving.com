CREATE TABLE IF NOT EXISTS `#__yoorecipe` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`asset_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.' ,
`access` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '1' ,
`category_id` INT( 11 ) NOT NULL,
`created_by` INT( 10 ) NOT NULL,
`user_id` INT( 11 ) NOT NULL,
`title` VARCHAR( 255 ) NOT NULL ,
`alias` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 5120 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`preparation` LONGTEXT NOT NULL ,
`servings_type` VARCHAR( 1 ) NOT NULL ,
`nb_persons` TINYINT NOT NULL ,
`difficulty` TINYINT NOT NULL ,
`cost` TINYINT NOT NULL ,
`carbs` DOUBLE NULL DEFAULT NULL,
`fat` DOUBLE NULL DEFAULT NULL,
`saturated_fat` DOUBLE NULL DEFAULT NULL,
`proteins` DOUBLE NULL DEFAULT NULL,
`fibers` DOUBLE NULL DEFAULT NULL,
`salt` DOUBLE NULL DEFAULT NULL,
`kcal` int(11) DEFAULT NULL,
`kjoule` int(11) DEFAULT NULL,
`diet` tinyint(1) DEFAULT NULL,
`veggie` tinyint(1) DEFAULT NULL,
`gluten_free` tinyint(1) DEFAULT NULL,
`lactose_free` tinyint(1) DEFAULT NULL,
`seasons` VARCHAR(50) DEFAULT NULL,
`creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`preparation_time` INT(4) NOT NULL ,
`cook_time` INT( 4 ) NOT NULL,
`wait_time` INT( 4 ) NOT NULL,
`featured` TINYINT( 1 ) NOT NULL,
`picture` VARCHAR( 255 ) NOT NULL ,
`video` VARCHAR( 255 ) NOT NULL ,
`published` BOOL NOT NULL ,
`validated` BOOL NOT NULL ,
`nb_views` INT NOT NULL,
`note` DECIMAL (10,2) NULL,
`metakey` TEXT NULL,
`metadata` TEXT NULL,
`price` DOUBLE NULL,
`language` CHAR(7) NOT NULL DEFAULT '*'
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_rating` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`recipe_id` INT( 11 ) NOT NULL ,
`note` TINYINT NOT NULL ,
`author` VARCHAR( 255 ) NOT NULL ,
`user_id` INT( 11 ) NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`comment` LONGTEXT NOT NULL ,
`published` BOOL NOT NULL ,
`abuse` BOOL NOT NULL , 
`creation_date` TIMESTAMP NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_ingredients` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`recipe_id` INT( 11 ) NOT NULL ,
`group_id` INT( 11 ) NOT NULL ,
`ordering` INT( 11 ) ,
`quantity` DECIMAL (10,2) NOT NULL ,
`unit` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL ,
`price` VARCHAR( 10 ) NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_categories` (
`recipe_id` INT( 11 ) NOT NULL ,
`cat_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_favourites` (
`recipe_id` INT( 11 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lang` VARCHAR(5) NOT NULL,
  `code` VARCHAR(50) NOT NULL,
  `label` VARCHAR(50) NOT NULL,
  `ordering` INT(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_tags` (

	`tag_id` int(11) NOT NULL AUTO_INCREMENT,
	`recipe_id` int(11) NOT NULL,
	`tag_value` VARCHAR(50) NOT NULL,

	PRIMARY KEY (`tag_id`)

)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_ingredients_groups` (

	`id` int(11) NOT NULL AUTO_INCREMENT,
	`text` VARCHAR(50) NOT NULL,

	PRIMARY KEY (`id`)

)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_seasons` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `month_id` enum('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'),
  PRIMARY KEY (`recipe_id`,`month_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__yoorecipe` ADD INDEX ( `published`,`validated`) ;
ALTER TABLE `#__yoorecipe` ADD INDEX ( `id`) ;
ALTER TABLE `#__yoorecipe_categories` ADD INDEX ( `recipe_id`, `cat_id` ) ;
ALTER TABLE `#__yoorecipe_favourites` ADD INDEX ( `recipe_id`, `user_id` ) ;

insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_RECIPE');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_SAUCE');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_SIDE_DISH');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_CREAM');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_TART');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_PASTRY');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_EMULSION');
insert into `#__yoorecipe_ingredients_groups` (`text`) values ('COM_YOORECIPE_INGR_GROUP_STUFFING');

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_GR', 'gram', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_KILO', 'kilo', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_LITER', 'liter', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_TEASPOON', 'teske', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_TABLESPOON', 'spiseske', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_POUND', 'pund', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_GLASS', 'glas', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_OUNCE', 'ounce', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_CUPS', 'tasse(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_GALLON', 'gallon', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_DROP', 'drop(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_HANDFUL', 'Håndfuld', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_PART', 'Del(e)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_PINCH', 'Knvispids(er)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', 'COM_YOORECIPE_UNITS_DASH', 'Stænk', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_GR', 'Gramm', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_KILO', 'Kilo', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_LITER', 'Liter', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_CENTILITER', 'cl', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_DECILITER', 'dl', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_MILLILITER', 'ml', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_TEASPOON', 'Teeloeffel', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_TABLESPOON', 'Essloeffel', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_POUND', 'Messerspitze', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_GLASS', 'Glas', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_OUNCE', 'Tube', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_FLOZ', 'Dose', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_CUPS', 'Prise', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_GALLON', 'Paeckchen', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_DROP', 'Tropfen', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_HANDFUL', 'handvoll', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_PART', 'Stueck', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_PINCH', 'Bund', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', 'COM_YOORECIPE_UNITS_DASH', 'Schuss', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_GR', 'γραμμάρια', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_KILO', 'κιλό(κιλά)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_LITER', 'λίτρο(λίτρα)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_TEASPOON', 'κουταλάκι γλυκού / κουταλάκια γλυκού', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_TABLESPOON', 'κουτάλι σούπας / κουτάλια σούπας', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_POUND', 'pound(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_GLASS', 'ποτήρι(α)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_OUNCE', 'ounce(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_CUPS', 'φλιτζάνι(α)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_GALLON', 'gallon(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_DROP', 'σταγόνα(σταγόνες)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_HANDFUL', 'χούφτα(χούφτες)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_PART', 'μέρος(μέρη)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_PINCH', 'πρέζα(πρέζες))', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', 'COM_YOORECIPE_UNITS_DASH', 'λίγο', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_GR', 'grams', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_LITER', 'liter(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_TEASPOON', 'teaspoon(s)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_TABLESPOON', 'tablespoon(s)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_POUND', 'pound(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_GLASS', 'glass(es)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_OUNCE', 'ounce(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_GALLON', 'gallon(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_CUPS', 'cup(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_DROP', 'drop(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_HANDFUL', 'handful(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_PART', 'part(s)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_PINCH', 'pinch(es)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', 'COM_YOORECIPE_UNITS_DASH', 'dash(es)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_GR', 'grams', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_LITER', 'liter(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_TEASPOON', 'teaspoon(s)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_TABLESPOON', 'tablespoon(s)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_POUND', 'pound(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_GLASS', 'glass(es)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_OUNCE', 'ounce(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_GALLON', 'gallon(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_CUPS', 'cup(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_DROP', 'drop(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_HANDFUL', 'handful(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_PART', 'part(s)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_PINCH', 'pinch(es)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', 'COM_YOORECIPE_UNITS_DASH', 'dash(es)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_GR', 'grammes', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_LITER', 'litre(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_TEASPOON', 'cuillère(s) à café', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_TABLESPOON', 'cuillère(s) à soupe', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_POUND', 'livre(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_GLASS', 'verre(s)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_OUNCE', 'once(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_CUPS', 'tasse(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_GALLON', 'gallon(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_DROP', 'goutte(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_HANDFUL', 'poignée(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_PART', 'morceau(x)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_PINCH', 'pincée(s)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', 'COM_YOORECIPE_UNITS_DASH', 'touche(s)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_GR', 'grammes', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_LITER', 'litre(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_TEASPOON', 'cuillère(s) à café', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_TABLESPOON', 'cuillère(s) à soupe', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_POUND', 'livre(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_GLASS', 'verre(s)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_OUNCE', 'once(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_CUPS', 'tasse(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_GALLON', 'gallon(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_DROP', 'goutte(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_HANDFUL', 'poignée(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_PART', 'morceau(x)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_PINCH', 'pincée(s)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', 'COM_YOORECIPE_UNITS_DASH', 'touche(s)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_GR', 'גרמים', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_KILO', 'קילו', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_LITER', 'ליטר', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_CENTILITER', 'ס""מ', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_DECILITER', 'ד""צ', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_MILLILITER', 'מ""ל', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_TEASPOON', 'כפית/יות', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_TABLESPOON', 'כף/ות', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_POUND', 'פאונד', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_GLASS', 'כוס/ות', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_OUNCE', 'קורטוב', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_FLOZ', 'פלוז', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_CUPS', 'כוס/ות', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_GALLON', 'גלון/ים', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_DROP', 'טיפה/ות', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_HANDFUL', 'חופן/ים', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_PART', 'חלק/ים', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_PINCH', 'קומץ', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', 'COM_YOORECIPE_UNITS_DASH', 'קורטוב', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_GR', 'grammi', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_KILO', 'kilo(i)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_LITER', 'litro(i)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_TEASPOON', 'cucchiaino(i)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_TABLESPOON', 'cucchiaio(i)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_POUND', 'libbra(e)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_GLASS', 'bicchiere(i)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_OUNCE', 'ooncia(e)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_FLOZ', 'oncia liquida', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_CUPS', 'tazza(e)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_GALLON', 'gallone(i)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_DROP', 'goccia(e)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_HANDFUL', 'manciata(e)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_PART', 'fetta(e)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_PINCH', 'pizzico(chi)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', 'COM_YOORECIPE_UNITS_DASH', 'linea(e)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_GR', 'gram', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_LITER', 'liter(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_TEASPOON', 'theelepel (s)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_TABLESPOON', 'eetlepel (s)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_POUND', 'potje(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_GLASS', 'glas (glazen)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_OUNCE', 'fles(sen)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_FLOZ', 'zakje(s)', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_CUPS', 'Kopje(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_GALLON', 'stuk(s)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_DROP', 'drop (s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_HANDFUL', 'handvol (s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_PART', 'deel (s)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_PINCH', 'pinch (es)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', 'COM_YOORECIPE_UNITS_DASH', 'dash (es)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_GR', 'gram', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_KILO', 'kilogram', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_LITER', 'liter', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_TEASPOON', 'łyżeczka', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_TABLESPOON', 'łyżka', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_POUND', 'funt', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_GLASS', 'szklanka', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_OUNCE', 'uncja', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_CUPS', 'kubek', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_GALLON', 'galon', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_DROP', 'kropla', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_HANDFUL', 'garść', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_PART', 'część', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_PINCH', 'szczypta', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', 'COM_YOORECIPE_UNITS_DASH', 'dash(es)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_GR', 'gramas', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_KILO', 'quilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_LITER', 'litro(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_TEASPOON', 'colher(es) de chá', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_TABLESPOON', 'colher(es) de sopa', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_POUND', 'libra(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_GLASS', 'copo(s)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_OUNCE', 'onça(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_CUPS', 'xícara(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_GALLON', 'galão(oes)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_DROP', 'gota(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_HANDFUL', 'punhado(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_PART', 'pedaço(s)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_PINCH', 'pitada(s)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', 'COM_YOORECIPE_UNITS_DASH', 'tecla(s)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_GR', 'gramas', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_KILO', 'quilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_LITER', 'litro(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_TEASPOON', 'colher(es) de chá', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_TABLESPOON', 'colher(es) de sopa', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_POUND', 'libra(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_GLASS', 'copo(s)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_OUNCE', 'onça(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_CUPS', 'xícara(s)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_GALLON', 'galão(oes)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_DROP', 'gota(s)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_HANDFUL', 'punhado(s)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_PART', 'pedaço(s)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_PINCH', 'pitada(s)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', 'COM_YOORECIPE_UNITS_DASH', 'tecla(s)', 19, 1);
	
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_GR', 'grame', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_KILO', 'kilogram(e)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_LITER', 'litru(i)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_TEASPOON', 'linguriţă(e)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_TABLESPOON', 'lingură(i)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_POUND', 'livră(e)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_GLASS', 'pahar(e)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_OUNCE', 'uncie(i)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_FLOZ', 'uncie lichid', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_CUPS', 'cană(i)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_GALLON', 'galon(i)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_DROP', 'picătură(i)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_HANDFUL', 'câteva(câţiva)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_PART', 'parte(părţi)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_PINCH', 'vârf(uri)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', 'COM_YOORECIPE_UNITS_DASH', 'liniuță(e)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_GR', 'гр', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_KILO', 'кг', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_LITER', 'лт', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_CENTILITER', 'мл', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_DECILITER', 'дл', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_MILLILITER', 'мл', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_TEASPOON', 'чайная ложечка', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_TABLESPOON', 'столовая ложечка', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_POUND', 'фунт', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_GLASS', 'стакан', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_OUNCE', 'унция', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_FLOZ', 'жидкая унция', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_CUPS', 'чашек', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_GALLON', 'галлон', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_DROP', 'капель', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_HANDFUL', 'горстей', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_PART', 'частей', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_PINCH', 'пинчей', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', 'COM_YOORECIPE_UNITS_DASH', 'dash(es)', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_GR', 'gramov', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_KILO', 'kilo(s)', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_LITER', 'liter(s)', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_TEASPOON', 'čajová lyžička(s)', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_TABLESPOON', 'polievková lyžička(s)', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_POUND', 'libra(s)', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_GLASS', 'pohár(ov)', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_OUNCE', 'unca(s)', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_CUPS', 'hrnček(ov)', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_GALLON', 'galón(ov)', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_DROP', 'kvapka(y)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_HANDFUL', 'hrsť(e)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_PART', 'čast(i)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_PINCH', 'štipka(ky)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', 'COM_YOORECIPE_UNITS_DASH', 'pomlčka(ky)', 19, 1);
	
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_GR', 'gram', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_KILO', 'kilogram', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_LITER', 'liter', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_TEASPOON', 'Čajna žlička', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_TABLESPOON', 'žlica', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_POUND', 'funt', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_GLASS', 'kozarec', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_OUNCE', 'unča', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_CUPS', 'skodelic', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_GALLON', 'galon', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_DROP', 'kaplja(i/e)', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_HANDFUL', 'peščic(a/i)', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_PART', 'del(ov)', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_PINCH', 'ščepec(a/ev)', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', 'COM_YOORECIPE_UNITS_DASH', 'vezaj', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_GR', 'gram', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_KILO', 'kilon', 2, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_LITER', 'liter', 3, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_CENTILITER', 'cL', 4, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_DECILITER', 'dL', 5, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_MILLILITER', 'mL', 6, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_TEASPOON', 'tesked', 7, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_TABLESPOON', 'matsked', 8, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_POUND', 'pound', 9, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_GLASS', 'glass', 10, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_OUNCE', 'ounce', 11, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_FLOZ', 'floz', 12, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_CUPS', 'koppar', 13, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_GALLON', 'gallon', 14, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_DROP', 'droppar', 15, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_HANDFUL', 'handfull', 16, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_PART', 'delar', 17, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_PINCH', 'nypor', 18, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', 'COM_YOORECIPE_UNITS_DASH', 'bindestreck', 19, 1);

insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('da-DK', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('de-DE', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('el-GR', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-GB', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('en-US', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-CA', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('fr-FR', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('he-IL', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('it-IT', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('nl-NL', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pl-PL', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-BR', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('pt-PT', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ro-RO', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('ru-RU', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sk-SK', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sl-SI', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('sv-SE', '', '', 1, 1);
insert into `#__yoorecipe_units` (`lang`, `code`, `label`, `ordering`, `published`) values ('tk-TK', '', '', 1, 1);

