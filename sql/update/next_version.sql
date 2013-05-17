# remove old way of storing info about processing data
ALTER TABLE `form_definitions` DROP COLUMN `post_action`;

# add new processing types
ALTER TABLE `form_definitions` ADD `email_action` TINYINT NOT NULL AFTER `receipt_body` ,
ADD `store_action` TINYINT NOT NULL AFTER `email_action` ,
ADD `object_action` TINYINT NOT NULL AFTER `store_action` 