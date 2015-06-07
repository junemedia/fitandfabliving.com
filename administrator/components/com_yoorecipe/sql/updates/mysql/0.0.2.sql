
 ALTER TABLE `#__yoorecipe` ADD `cook_time` TINYINT( 4 ) NOT NULL AFTER `preparation_time` ;
 ALTER TABLE `#__yoorecipe` ADD `wait_time` TINYINT( 4 ) NOT NULL AFTER `cook_time` ;
 ALTER TABLE `#__yoorecipe` ADD `featured` TINYINT( 1 ) NOT NULL AFTER `published` ;
 ALTER TABLE `#__yoorecipe` ADD `note` DECIMAL NULL AFTER `nb_views` ;
 ALTER TABLE `#__yoorecipe_rating` ADD `email` VARCHAR( 255 ) NOT NULL AFTER `author`;