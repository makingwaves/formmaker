/*
    Script updates database from version 1.5.x to 1.6.x
*/


ALTER TABLE `form_definitions` ADD `process_class` VARCHAR(255) NOT NULL AFTER `receipt_body`;
ALTER TABLE `form_definitions` CHANGE `post_action` `post_action` ENUM('email','table','object');

ALTER TABLE `form_attributes` ADD `identifier` VARCHAR(255) NOT NULL AFTER `type_id`;
