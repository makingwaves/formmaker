<?php /* #?ini charset="utf-8"?

[FormmakerSettings]
SendOnlyFilledData=false
# if enabled, system will check for ShortDateFormat (defined in current locale) and try to execute adequate Regex (defined in this file)
DynamicDateFormat=enabled
DefaultDatepickerFormat=dd/mm/yy
# external script is called just before email messages are sent
#ExternalScript=bin/php/script.php
# external script which can override default page data
#ExternalData=bin/php/set_data.php

[AdditionalElements]
description=enabled
css=enabled
default_value=enabled
allowed_file_types=jpg,jpeg,gif,png,pdf,doc,xls,ppt

# Default date format is %d/%m/%Y, and it's regex is already defined in database, so there is no need to define it here. 
# Eeach block needs to contain Regex and DatepickerFormat.
[ShortDateFormat_%d.%m.%Y]
Regex=/^(([0-2][0-9])|(3[0-1]))\.((0[1-9])|(1[0-2]))\.([1-2][0-9]{3})$/
DatepickerFormat=dd.mm.yy

[ShortDateFormat_%m/%d/%Y]
Regex=/^((0[1-9])|(1[0-2]))\/(([0-2][0-9])|(3[0-1]))\/([1-2][0-9]{3})$/
DatepickerFormat=mm/dd/yy

# Class responsible for email handling. For now you can use eZMail and PHPMailer.
# Currently there are two options implemented: eZMail and PHPMailer.
# If you want to use file attachments go for PHPMailer.
[Mail]
MailClass=PHPMailer
AttachmentsDir=/var/ezflow_site/storage/formmaker/image/

 */