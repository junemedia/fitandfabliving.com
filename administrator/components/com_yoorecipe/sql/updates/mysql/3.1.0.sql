CREATE TABLE IF NOT EXISTS `#__yoorecipe_seasons` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `month_id` enum('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'),
  PRIMARY KEY (`recipe_id`,`month_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__yoorecipe` ADD COLUMN `seasons` VARCHAR(50) DEFAULT NULL AFTER `lactose_free`;