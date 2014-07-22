/*
    Script updates database from version 1.5.x to 1.6.x
*/

INSERT INTO `form_types` (`id`, `name`, `template`, `validation`, `sep_order`) VALUES('6','File','file.tpl','0','5');
INSERT INTO `form_types` (`id`, `name`, `template`, `validation`, `sep_order`) VALUES('7','Select','select.tpl','0','6');
ALTER TABLE `form_attributes` ADD `allowed_file_types` VARCHAR( 255 ) NOT NULL;