
 CREATE TABLE IF NOT EXISTS`#__yoorecipe_categories` (
`recipe_id` INT( 11 ) NOT NULL ,
`cat_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__yoorecipe_categories` (recipe_id, cat_id) select id, category_id from `#__yoorecipe`;