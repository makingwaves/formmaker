Form Maker 2.0.1
================

Owner
-----
**Making Waves** (www.makingwaves.no)

Authors
-------
* Piotr Szczygieł (piotr.szczygiel@makingwaves.pl)
* Bogdan Juszczak (bogdan.juszczak@makingwaves.pl)

Description
-----------
This is an exclusive eZPublish extension, which provides possibility to creating forms in easy way.

Installation
------------
You can find installation guide in *doc/install.md*


What you can do by using FormMaker?
===================================
* Create a forms in a very quick way
* Define what to do with form output (send by email, store in database or create your own output method)
* Set multiple email receivers (also user can receive his own copy)
* Define receipt and confirmation page
* Split one form into couple steps/pages (by using page separator)
* Set form view type
* Some advanced things like: injecting data from external source or injecting a script

1. Main menu options
====================
After FormMaker is successfully installed you can spot the new tab in eZ admin panel top menu. After entering it, editor is allowed to use one of three options:  

1.1. List of forms
------------------
This is a default action. All forms saved in database are listed in this table. Editor can edit or remove each of them. It it also possible to sort forms by table headers (name, created, author).  
![List of forms](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/list%20of%20forms.jpg)

1.2. Create new form
----------------------------------------------
![Create new form](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/create%20new%20form%203.jpg)

1.3. See answers
----------------
Action displays a list of answers that are stored in database. A table rendered here provides an information about answer form and also displays answer quick summary (described closely later in chapter Viewing answers)
![See answers](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/see%20answers.jpg)

2. Adding new form
==================
When adding a new form, FormMaker comes with a several options (not all are required).  
![Adding new form](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/add%20new%20form.jpg)  

* **Form name (required)** - this name will be used for identifying a from on a list.
* **First page label** (required) - a label which will be displayed on a first page. If you want to have a more than one page, you will define the labels in page separator.
* **Form CSS class** - just a CSS class that will be added into <form /> tag.
* **I want a confirmation page with the following label** (checkbox and text field) - if checked, confirmation page will be displayed as a last step of a form. More informations can be found under Setting confirmation page.
* **Receipt page label** (required) - receipt page is always displayed (as a last form step), so it needs a header, which needs to be defined here.
* **Receipt page intro text** - here you can place the plain text that will display under the receipt header.
* **Receipt page body text** - here you can place the plain text that will display under the receipt intro text (different styled).
* **Send data via email** (checkbox) - this option is checked by default (but of course you can disable it whenever you want). Means that all form input will be sent to recipients.
* **E-mail title** - the subject of e-mail message
* **E-mail recipients** - addresses separated by semicolons
* **Store data in database** (checkbox) - when checked, all form input will be stored in database. Then editor can use "See answers" option to list stored data (5. Viewing answers).
* **Use process class method** and **Process class name** - third way of processing input data. Developer can define here the PHP class name which will be executed when form is send. If you need more information about this feature, ask [Patryk Manterys](https://github.com/pmanterys).

























