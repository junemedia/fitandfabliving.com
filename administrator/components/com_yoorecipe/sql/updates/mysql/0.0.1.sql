
 DROP TABLE IF EXISTS `#__yoorecipe`;
DROP TABLE IF EXISTS `#__yoorecipe_rating`;
DROP TABLE IF EXISTS `#__yoorecipe_ingredients`;
 
CREATE TABLE IF NOT EXISTS `#__yoorecipe` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`category_id` INT( 11 ) NOT NULL,
`title` VARCHAR( 50 ) NOT NULL ,
`preparation` LONGTEXT NOT NULL ,
`nb_persons` TINYINT NOT NULL ,
`difficulty` TINYINT NOT NULL ,
`cost` TINYINT NOT NULL ,
`creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`preparation_time` INT(4) NOT NULL ,
`picture` VARCHAR( 255 ) NOT NULL ,
`published` BOOL NOT NULL ,
`nb_views` INT NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_rating` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`recipe_id` INT( 11 ) NOT NULL ,
`note` TINYINT NOT NULL ,
`author` VARCHAR( 255 ) NOT NULL ,
`comment` LONGTEXT NOT NULL ,
`creation_date` TIMESTAMP NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__yoorecipe_ingredients` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`recipe_id` INT( 11 ) NOT NULL ,
`quantity` DECIMAL NOT NULL ,
`unit` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
