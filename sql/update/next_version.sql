# remove old way of storing info about processing data
ALTER TABLE `form_definitions` DROP COLUMN `post_action`;

# remove the email sender field, since it's handled by eZ
ALTER TABLE `form_definitions` DROP COLUMN `email_sender`;

# add css class column
ALTER TABLE `form_definitions` ADD `css_class` VARCHAR( 255 ) NOT NULL ;

# add new processing types
ALTER TABLE `form_definitions` ADD `email_action` TINYINT NOT NULL AFTER `receipt_body` ,
ADD `store_action` TINYINT NOT NULL AFTER `email_action` ,
ADD `object_action` TINYINT NOT NULL AFTER `store_action`;

--
-- Table structure for table `form_answers`
--

DROP TABLE IF EXISTS `form_answers`;
CREATE TABLE `form_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `definition_id` int(11) NOT NULL,
  `answer_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `definition_id` (`definition_id`),
  CONSTRAINT `form_answers_ibfk_1` FOREIGN KEY (`definition_id`) REFERENCES `form_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `form_answers_attributes`
--

DROP TABLE IF EXISTS `form_answers_attributes`;
CREATE TABLE `form_answers_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `answer_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`,`attribute_id`),
  KEY `attribute_id` (`attribute_id`),
  CONSTRAINT `form_answers_attributes_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `form_answers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `form_answers_attributes_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `form_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;