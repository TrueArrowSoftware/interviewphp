# interviewphp

General Setup:

* Copy files from source folder to web root
* Run `composer update/install` as applicable (or run locally if shared hosting and upload vendor folder)
* Run `Yarn update/install` as applicable (or run locally and update on server node_modules)
* Edit `configure.local.php` to define database connection and admin email
* check `.htaccess` to correct path based on hosting if required.

Database setup

* open a blank `mysql` database
* Run `shoppingcart.sql` file to create sample database
* attach to scripts from general setup steps.
