
 ALTER TABLE `#__yoorecipe` ADD `created_by` INT( 10 ) NOT NULL AFTER `category_id` ;
 ALTER TABLE `#__yoorecipe` ADD `created_by_alias` varchar(255) NOT NULL AFTER `created_by` ;
 ALTER TABLE `#__yoorecipe` ADD `validated` BOOL NOT NULL AFTER `published` ;
 UPDATE `#__yoorecipe` SET `validated` = 1;
 UPDATE `#__yoorecipe` SET `created_by` = 42;