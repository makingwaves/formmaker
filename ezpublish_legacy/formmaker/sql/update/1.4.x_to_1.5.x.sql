/*
    Script updates database from version 1.4.x to 1.5.x
*/


INSERT INTO `form_validators` (`id`, `type`, `description`, `regex`) VALUES (NULL, 'Regex', 'Date (year only)', '/^([1-2][0-9]{3})$/');
UPDATE `form_validators` SET `description` = 'Date (full)' WHERE `form_validators`.`id` =8;

INSERT INTO `form_validators` (`id`, `type`, `description`, `regex`) VALUES (NULL, 'Regex', 'Custom Regex', '');
ALTER TABLE `form_attr_valid` ADD `regex` VARCHAR( 255 ) NOT NULL;