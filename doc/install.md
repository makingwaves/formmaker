FormMaker 5 DEVELOPMENT installation guide
==========================================

1. Put the code into folder <ezroot>/src/MakingWaves/FormMakerBundle

2. Make a symbolic link for legacy extension:
`cd <ezroot>/ezpublish_legacy/extension/`
`ln -s ../../src/MakingWaves/FormMakerBundle/ezpublish_legacy/formmaker`

3. Enable extension adding entry to site.ini.append.php:
`[ExtensionSettings]
ActiveExtensions[]=formmaker`

4. Open file composer.json (placed in ezroot) and add Doctrine ORM into "required" section:
`"doctrine/orm": "~2.2,>=2.2.3"`

5. Edit file ezpublish/config/config.yml by adding formmaker.yml setting into 'imports' section, as follows:
`imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: "@FormMakerBundle/Resources/config/formmaker.yml" }`

6. In the same file, add following Doctrine configuration:
`doctrine:
    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        auto_mapping: true`

7. Edit file ezpublish/config/routing.yml by adding following section:
`formmakerbundle:
    resource: "@FormMakerBundle/Resources/config/routing.yml"
    prefix: /formmaker`

8. Go to command line and run the composer:
`php composer.phar update`

9. Make sure that database is compatibile. If you're installing new FormMakerBundle, you should just run following script:
`php ezpublish/console doctrine:schema:update --force`

In case when you're upgrading from old FormMaker extension, run the same script, but before and after it, use the scripts from Sql/Update folder.

10. Install the assets
`php ezpublish/console assets:install`

11. Dump the assets
`php ezpublish/console assetic:dump`

12. Clear the cache:
`php ezpublish/console cache:clear`


Form Maker legacy installation guide
====================================

1. Get the extension. Go to your `extension` folder within your eZ installation, then run: `git clone git@github.com:makingwaves/formmaker.git`

2. Enable extension adding entry to site.ini.append.php:
`[ExtensionSettings]
ActiveExtensions[]=formmaker`

3. Log in to admin panel as administrator, go to *Setup -> Packages* and click "Import new package".
Now browse for package form-1.8-1.ezpkg which is included in formmaker/package and import it.
After successfull import process, you will be asked to install package, so please perform.
In case of any errors please check if proper file rights write are set on your `var` folder.

4. To check if everything went OK, go to *Setup -> Classes*. You should see "Form" class as a first item of "Recently modified classes" list.

5. Now you need to include PHP class, so go to the root directory of your eZ installation and run:
`php bin/php/ezpgenerateautoloads.php`

6. It's also highly recommended to clear the cache, but be aware of running this on your production environment:
`php bin/php/ezcache.php --clear-all --purge`

7. As forms requires changes in your MySQL database, you need to install sql file included in extension (sql/install.sql). You can use following pattern as a instalation command:  
`mysql -u {mysql-username} -p {your-ezpublish-database} < install.sql`

8. If you have editor users in your project and you want them to have a possibility to add/remove forms, you need to grant them proper privilages.
Go to *User accounts => Roles and policies* and click on your editor's group. Now click "Edit" button and add "New policy".
As a module please select "formmaker" and choose "edit" function. Then accept it by clicking "Grant full access". You can perform same action for "remove" function.

9. If you want to allow Editor to change field identifier you need to do the same with "special" function. If this policy is not set Identifier field is readonly when editing form.

10. Next, you need to add following privilages to Anonymous and Editors user groups (for ezjscore purposes):
*module: ezjscore, function: call, function limitation: formmaker*

11. Now you can use "formmaker" tab, which is displayed in your admin panel. After you will create your form, you can assign it to Form class object.
It is also possible to add Form attribute (datatype) into your own class.

Troubles...
If "formmaker" tab loads blank page, please be sure that you have cleared the cache. Perform clearing from the admin panel once more.