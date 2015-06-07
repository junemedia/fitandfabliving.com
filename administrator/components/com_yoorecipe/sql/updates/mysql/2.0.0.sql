
 ALTER TABLE `#__yoorecipe` CHANGE `note` `note` DECIMAL( 10, 2 ) NULL DEFAULT NULL ;
 ALTER TABLE `#__yoorecipe` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `title` ;