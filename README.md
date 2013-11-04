Form Maker 2.0.1
================

Owner
-----
**Making Waves** (www.makingwaves.no)

Authors
-------
* [Piotr Szczygieł](https://github.com/piotr-szczygiel/)
* [Bogdan Juszczak](https://github.com/bogdanjuszczak/)

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

The next step of adding a form is creating some attributes. You can create as many attributes as you want. All of them are described on Form attributes page. After that you should be able to see your form on a forms list.  
  
The final step, before you will be able to see your from on front page, is to create form eZ object.

1. Go to **Content structure** tab  
2. Enter the destination you want using the directory tree in a left hand menu  
3. Click on **Create new** button and add new **Form** object  
![Add form object](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/create%20new%20form.jpg)  
4. You will be asked to put the **form name** (node name) and **pick the form** that you want to connect with the node. In come cases you may want **pick the the view** which will be used to render the form (described in Using Form Views chapter).  
![Add form object](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/create%20new%20form%202.jpg)  
5. Publish the object  
6. That's it! Your form is available on front page.
![Thats it](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/form%20front.jpg)

3. Editing form
===============
To edit the form, please find the form you like on **List of forms** page and the click **Edit** link.
![List of forms](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/list%20of%20forms.jpg)
You will be redirected to edit form page, which looks like below:
![Edit form](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/edit%20form.jpg)

Click on the show button to expand form options container, which is the same with form definition described in [Adding new form](https://github.com/makingwaves/formmaker#2-adding-new-form).  
The difference between adding new form and editing existing one can be also noticed (beside Editing form... label :)) in a toolbar. As you see on the image above, there are four buttons and one select list on it:
* **Save and exit** - saves the form and redirects editor to **list of forms**
* **Save** - saving without redirecting (useful when you want to see published changes in separate browser window)
* **Cancel** - doesn't save the changes and redirects to **list of forms**
* **Add attribute** - adds the attribute selected on a list

4. Form attributes
==================
Each form can have as many attributes as you want. They can be added by using **Add attribute** button visible on a top and bottom toolbars.
![Edit form](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/edit%20form.jpg)
To make it more readable, each attribute type has it's own background colour. Let's take the text attribute for instance:
![Text attribute](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/text%20attribute.jpg)

As you can see in a top right corner, editor is possible to **enable/disable** the attribute or **remove** it. When attribute is disabled, it is visible in admin panel, but of course it's not available on a form page.  
  
Last important thing about attributes is the **ordering**. To make it more easy in use, we provided the possibility to drag and drop each attribute. Just grab the header of an attribute you want and place wherever you want - see the picture.
![Drag and drop](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/dnd.jpg)

4.1. Text
---------
Text attribute is a simple text typed input field. However, it can look different depending on validators that you will use. But for now, let's take following attribute:
![Text attribute admin](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/text%20attribute%20admin.jpg)
* **Label** (required) - short name of the attribute, will be displayed on front
* **Identifier** - can by used by developers to identify the field
* **Mandatory** - indicates whether field will be required or not
* **Description**
* **Default value** - will be placed inside the input field
* **CSS class** - string placed here will be added as a CSS class to <input /> tag
* **Validation** - you can pick one of possible validation methods

Text attribute filled with data will be rendered on a form page as follows:  

![Text attribute front](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/text%20attribute%20front.jpg)  
  
In many cases you may want to use some validation. You can choose from following:

* **Custom Regex** - this is advanced validator for users who know how to create and use regular expressions. After selecting this option, additional field (**Validation Regex**) will show up, where you can put the expression string. Please note that it is **mandatory to use slashes** ( / ) as the opening and ending characters of the string.  

![Custom regex](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/custom_regex.jpg)  

Example regular expression accepts only letters. Putting other characters in such text field will produce a validation error:  

![Custom regex front](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/custom_regex%20front.jpg)

4.2. Checkbox
-------------
![Checkbox attribute](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/checkbox%20attribute.jpg)  

* **Label** (required) - short name of the attribute, will be displayed on front
* **Identifier** - can by used by developers to identify the field
* **Mandatory** - indicates whether field will be required or not. In checkbox context it means that input needs to be selected to proceed.
* **Description**
* **Default value** - if checked, input on front will be checked by default
* **CSS class** - string placed here will be added as a CSS class to <input /> tag
* **Checkbox** attribute filled with data will be rendered on a form page as follows:

![Checkbox attribute front](https://raw.github.com/makingwaves/formmaker/release/doc/screenshots/checkbox%20attribute%20front.jpg)
























