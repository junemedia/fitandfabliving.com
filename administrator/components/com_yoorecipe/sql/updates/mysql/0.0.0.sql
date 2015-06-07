
 ALTER TABLE `#__yoorecipe_rating` ADD `user_id` INT( 11 ) NULL AFTER `author` ;
 UPDATE `#__yoorecipe_rating` set `user_id` = 0 where `user_id` is NULL ;
 ALTER TABLE `#__yoorecipe` ADD `description` VARCHAR( 5120 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `alias` ;