# Setup
This document covers the steps needed to set up the eVoting web application on a server.

## Prerequisites
The host must have a running [MySQL](https://www.mysql.com) server instance and a web server such as [Apache](http://www.apache.org) or [Nginx](http://nginx.org). PHP version 5.5 or higher must also be installed. On Windows, the [WAMP](http://www.wampserver.com/en/) stack is recommended.

[Composer](https://getcomposer.org) must also be installed.

## Installation

### Get the Project
If you have [Git](https://git-scm.com) installed you can clone the [Github repository](https://github.com/EuresTools/eVote-web)

    git clone git@github.com:EuresTools/eVote-web.git
    
Alternatively, you can [download](https://github.com/EuresTools/eVote-web/archive/master.zip) the project.

The project should be placed inside a web accessible directory.

### Database
Create a MySQL user with a username and password that matches the credentials in the `config/db.php` file. Feel free to change the credentials in the file.

Create a new database with the name `yii2_evote` and the `utf8_general_ci` collation. This can be done vie the command line in the following way:

1. Open MySQL.

         mysql -u username -p
2. Enter your MySQL password when prompted.
3. Execute this command to create the database.

        CREATE DATABASE IF NOT EXISTS yii2_evote CHARACTER SET utf8 COLLATE utf8_general_ci;

Set the appropriate permissions for an added user and set the parameters in config/db.php accordingly.


Now run the following commands in the project's root directory to set up the database tables. (Below listed Dependencies must been checked!)

    ./yii migrate
    ./yii migrate --migrationPath=@yii/rbac/migrations
    ./yii rbac/init

Set the correct folder permissions so that the ./web/assets and the runtime folder is writeable for the running apache user.

 	chown www-data web/assets/

Visit the page and login with the default admin user with the following credentials:

    admin/password

Start using the eVote app by creating Organizations and / or Polls.


### Dependencies
Make sure your installation of Composer is up to date

    composer self-update

Composer requires the following plugin installed otherwise it would fail on composer install/update
    
    composer.phar global require "fxp/composer-asset-plugin:~1.0.0"

Then install the application's dependencies by executing the following command in the project's root directory

run composer install on console to install required files (in the correct directory).

    use --no-dev to "NOT" install the required development packages.
	use -vvv to get more detailed debug information on errors

e.g.

	composer.phar install --prefer-dist --no-dev -vvv
	
to update run
	
	composer.phar update

if --no-dev set remove the lines from the ./yii file and /web/index.php

	defined('YII_DEBUG') or define('YII_DEBUG', true);

Otherwise the gii module would be required which is not installed. And remove/comment the gii settings in the /config/console.php file.