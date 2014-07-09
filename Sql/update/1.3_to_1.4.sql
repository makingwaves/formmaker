/*
    Script updates database from version 1.3.x to 1.4
*/

ALTER TABLE `form_definitions` ADD `summary_body` TEXT NOT NULL AFTER `summary_label`;