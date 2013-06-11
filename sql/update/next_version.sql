# remove old way of storing info about processing data
ALTER TABLE `form_definitions` DROP COLUMN `post_action`;

# remove the email sender field, since it's handled by eZ
ALTER TABLE `form_definitions` DROP COLUMN `email_sender`;

# add new processing types
ALTER TABLE `form_definitions` ADD `email_action` TINYINT NOT NULL AFTER `receipt_body` ,
ADD `store_action` TINYINT NOT NULL AFTER `email_action` ,
ADD `object_action` TINYINT NOT NULL AFTER `store_action` 