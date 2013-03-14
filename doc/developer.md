Form Maker developer's guide
============================

Good practices when working with FormMaker:
-------------------------------------------
* ALWAYS check the eZ debug informations when implementing or testing. It's **not allowed to commit the code which generates notices, warnings or errors**.
* Remember that master branch is (or at least should be) always equal to latest tag
* Tags are always created based on master branch
* Remember to update *doc/changelog* file

When to create new versions:
----------------------------
* New version can be created **only in case when you want to install it** for customer or other server
* Do not create new versions when you don't need it

How to choose the number for new version:
-----------------------------------------
* Version number convention is based basically on **changes made to database structure**
* In case when we made some changes in database structure we're incrementing the number after first decimal (i.e **1.1.0**, **1.2.0**)
* Otherwise we're incrementing the number after second decimal (i.e **1.2.1**, **1.2.2**)
* First number can be incremented in case of major changes

How to implement new task:
--------------------------
1. Create your working branch based on release branch
2. Update your database using *sql/install.sql* file
3. Implement your change
4. In case when you made the changes in database please do following:
   * update *sql/install.sql* file using script *bin/sh/sql_schema.sh* (you don't need to empty your database before using it)
   * update (or create if doesn't exist) file *sql/update/next_version.sql*
5. Go to file *doc/changelog* and put the descriptive comment of your change under "Next version" header (in case when this header doesn't exist, create it)
6. Push your branch to github and create a pull request to release branch
7. Await for the feedback from collegues from FormMaker team
8. In case of positive feedback, merge the branch into release
9. Remove your working branch from github

How to create new version:
--------------------------
1. Checkout and update your local release branch code
2. Go to file *doc/changelog* and change "Next version" header into "Version X.Y.Z"
3. Change the version number in file *INSTALL*
4. Rename file (if exists) *sql/update/next_version.sql* into *sql/update/A.B.C_to_X.Y.Z.sql*
5. Push the changes into release branch
6. Merge branch release into master
7. Push master branch into github
8. Create the new tag (`git tag X.Y.Z`) basing on master branch and push it to github