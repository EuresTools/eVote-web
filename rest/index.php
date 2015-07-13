<?php
// change the following paths if necessary
require_once(__DIR__ . '/../globals.php');

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/rest.php');

ini_set('memory_limit', '1024M');
set_time_limit(60);

(new yii\web\Application($config))->run();
