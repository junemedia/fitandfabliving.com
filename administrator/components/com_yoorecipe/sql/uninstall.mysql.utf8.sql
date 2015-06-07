 
 DROP TABLE IF EXISTS `#__yoorecipe`;
 DROP TABLE IF EXISTS `#__yoorecipe_rating`;
 DROP TABLE IF EXISTS `#__yoorecipe_ingredients`;
 DROP TABLE IF EXISTS `#__yoorecipe_tags`;
 DROP TABLE IF EXISTS `#__yoorecipe_categories`;
 DROP TABLE IF EXISTS `#__yoorecipe_favourites`;
 DROP TABLE IF EXISTS `#__yoorecipe_units`;
 DROP TABLE IF EXISTS `#__yoorecipe_ingredients_groups`;
 DROP TABLE IF EXISTS `#__yoorecipe_seasons`;

 DELETE FROM `#__categories` WHERE extension = 'com_yoorecipe';