ALTER TABLE `form_types` CHANGE `id` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;
UPDATE `form_types` SET `string_id`="text" WHERE id=1;
UPDATE `form_types` SET `string_id`="textarea" WHERE id=2;
UPDATE `form_types` SET `string_id`="checkbox" WHERE id=3;
UPDATE `form_types` SET `string_id`="radio" WHERE id=4;
UPDATE `form_types` SET `string_id`="separator" WHERE id=5;
UPDATE `form_types` SET `string_id`="file" WHERE id=6;
UPDATE `form_types` SET `string_id`="select" WHERE id=7;