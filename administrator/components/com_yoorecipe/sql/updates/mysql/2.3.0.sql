

 ALTER TABLE `#__yoorecipe` ADD `servings_type` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `preparation` ;
 update `#__yoorecipe` set servings_type = 'P';
 
 ALTER TABLE `#__yoorecipe` DROP COLUMN `created_by_alias`;
 ALTER TABLE `#__yoorecipe_ingredients` ADD `ordering` INT( 11 ) AFTER `recipe_id` ;
 
 update `#__yoorecipe_ingredients` set unit = 'kilo(s)' where unit = 'kilos';
 update `#__yoorecipe_ingredients` set unit = 'liter(s)' where unit = 'liters';
 update `#__yoorecipe_ingredients` set unit = 'teaspoon(s)' where unit = 'teaspoon';
 update `#__yoorecipe_ingredients` set unit = 'tablespoon(s)' where unit = 'tablespoon';
 update `#__yoorecipe_ingredients` set unit = 'pound(s)' where unit = 'pound';
 update `#__yoorecipe_ingredients` set unit = 'glass(es)' where unit = 'glass';
 update `#__yoorecipe_ingredients` set unit = 'ounce(s)' where unit = 'ounce';
 update `#__yoorecipe_ingredients` set unit = 'gallon(s)' where unit = 'gallon';
 update `#__yoorecipe_ingredients` set unit = 'drop(s)' where unit = 'drops';
 update `#__yoorecipe_ingredients` set unit = 'handful(s)' where unit = 'handfuls';
 update `#__yoorecipe_ingredients` set unit = 'part(s)' where unit = 'parts';
 update `#__yoorecipe_ingredients` set unit = 'pinch(es)' where unit = 'pinches';
 
 update `#__yoorecipe_ingredients` set unit = 'litre(s)' where unit = 'litre';
 update `#__yoorecipe_ingredients` set unit = 'cuillère(s) à café' where unit = 'cuillère à café';
 update `#__yoorecipe_ingredients` set unit = 'cuillère(s) à soupe' where unit = 'cuillère à soupe';
 update `#__yoorecipe_ingredients` set unit = 'livre(s)' where unit = 'livre';
 update `#__yoorecipe_ingredients` set unit = 'verre(s)' where unit = 'verre';
 update `#__yoorecipe_ingredients` set unit = 'once(s)' where unit = 'once';
 update `#__yoorecipe_ingredients` set unit = 'tasse(s)' where unit = 'tasse'; 
 update `#__yoorecipe_ingredients` set unit = 'goutte(s)' where unit = 'gouttes';
 update `#__yoorecipe_ingredients` set unit = 'poignée(s)' where unit = 'poignées';
 update `#__yoorecipe_ingredients` set unit = 'morceau(x)' where unit = 'morceaux';
 update `#__yoorecipe_ingredients` set unit = 'pincée(s)' where unit = 'pincées';

 -- danois
  update `#__yoorecipe_ingredients` set unit = 'grams' where unit = 'gram';
  update `#__yoorecipe_ingredients` set unit = 'kilo(s)' where unit = 'kilo';
  update `#__yoorecipe_ingredients` set unit = 'liter(s)' where unit = 'liter';
  update `#__yoorecipe_ingredients` set unit = 'liter(s)' where unit = 'liter';
  
 -- en
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'grams';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO' where unit = 'kilo(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'liter(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CENTILITER' where unit = 'cL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DECILITER' where unit = 'dL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_MILLILITER' where unit = 'mL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'teaspoon(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'tablespoon(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'pound(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'glass(es)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'ounce(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_FLOZ' where unit = 'floz';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = 'gallon(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'cup(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'drop(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'handful(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'part(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'pinch(es)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DASH' where unit = 'dash(es)';

 -- fr
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'grammes';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'litre(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'cuillère(s) à café';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'cuillère(s) à soupe';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'livre(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'verre(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'once(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'tasse(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'goutte(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'poignée(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'morceau(x)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'pincée(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DASH' where unit = 'touche(s)';

 -- da
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'gram';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO' where unit = 'kilo';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'teske';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'theske';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'spiseske';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'pund';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'glas';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'glass';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'ounce';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'kopper';

-- de
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'Gramm';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO' where unit = 'Kilo';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'Lieter';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CENTILITER' where unit = 'cL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DECILITER' where unit = 'dL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_MILLILITER' where unit = 'mL';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'Teelöffel';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'Esslöffel';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'Glas';

 -- es
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'gramos';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'litros';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CENTILITER' where unit = 'cl';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DECILITER' where unit = 'dl';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_MILLILITER' where unit = 'ml';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'cucharaditas de café';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'cucharadas de sopa';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'libra';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'vaso';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'onza';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = 'galón';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'taza';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'gota(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'gotas';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'puñado(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'puñados';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'trozo(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'trozos';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'pellizco(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'pellizcos';
 
 -- it
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'grammi';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO'  where unit = 'kilo(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'litro(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'cucchiaino(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'cucchiaio(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'libbra(e)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'bicchiere(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'oncia(e)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_FLOZ' where unit = 'oncia liquida';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = 'gallone(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'tazza(e)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'goccia(e)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'manciata(e)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'parte(i)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'pizzico(chi)';

 -- nl
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'gram';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO' where unit = 'kilo';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'liter';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'theelepel(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'eetlepel(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'potje(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'glas/glazen';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'fles/flessen';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_FLOZ' where unit = 'zakje(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = 'stuks';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'kopje(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'druppel(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'hand(en)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'stukje(s)';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'snufje(s)';

 -- pl
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'gram';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO'  where unit = 'kilogram';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'liter';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'litr';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CENTILITER' where unit = 'centylitr';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DECILITER' where unit = 'decylitr';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_MILLILITER' where unit = 'mililitr';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'lyzeczka';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'lyzeczka';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'lyzka';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'lyzka';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'funt';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'szklanka';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'uncja';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = 'galon';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'kubek';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'kropla';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'garsc';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'garsc';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'czesc';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'czesc';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'szczypta';
 
 -- ru
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = '?';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO'  where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = '?';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CENTILITER' where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DECILITER' where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_MILLILITER' where unit = '??';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = '?????? ???????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = '???????? ???????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = '???????? ?????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = '????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = '??????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = '?????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_FLOZ' where unit = '?????? ?????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GALLON' where unit = '??????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = '?????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = '????????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = '??????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = '???????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = '??????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = '??????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = '?????';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = '??????';

 -- sv
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GR'  where unit = 'gram';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_KILO'  where unit = 'kilon';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_LITER' where unit = 'liter';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'tesked';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TEASPOON' where unit = 'teskedar';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'matsked';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_TABLESPOON' where unit = 'matskedar';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_POUND' where unit = 'pound';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_GLASS' where unit = 'glass';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_OUNCE' where unit = 'ounce';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_CUPS' where unit = 'koppar';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_DROP' where unit = 'droppar';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_HANDFUL' where unit = 'handfull';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PART' where unit = 'delar';
 update `#__yoorecipe_ingredients` set unit =  'COM_YOORECIPE_UNITS_PINCH' where unit = 'nypor';

 
