/*
    Script updates database from version 1.7.x to 1.8.x
*/

ALTER TABLE `form_definitions` ADD `email_title` VARCHAR( 255 ) NOT NULL AFTER `email_sender`;