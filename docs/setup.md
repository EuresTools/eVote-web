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

Now run the following commands in the project's root directory to set up the database tables

    ./yii migrate
    ./yii rbac/init

### Dependencies
Make sure your installation of Composer is up to date

    composer self-update

Then install the application's dependencies by executing the following command in the project's root directory

    composer install
