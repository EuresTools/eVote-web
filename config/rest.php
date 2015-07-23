<?php

use kartik\datecontrol\Module;
use \yii\web\Request;

//$baseUrl = str_replace('/rest', '', (new Request)->getBaseUrl());
$baseUrl = (new Request)->getBaseUrl();

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'eVote-rest-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'controllerNamespace' => 'rest\controllers',
    'controllerNamespace' => 'app\modules\rest\controllers',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager' 'yii\rbac\PhpManager'
        ],
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    //'controller' => ['v1/poll', 'v1/comment', 'v2/post']
                    'controller' => ['v1/poll', 'v2/poll'],
                    // 'tokens' => [
                    //     //'{id}' => '<id:\\w+>'
                    // ]
                ],
            ],
        ],
        'request' => [
            'baseUrl' => $baseUrl,
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false, // disable session for api interface
            'loginUrl'=>null, // Set the loginUrl property to be null to show a HTTP 403 error instead of redirecting to the login page.
        ],
        'errorHandler' => [
            //'errorAction' => 'rest/error', // if we need a error page?
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['vote'],
                    'exportInterval' => 1,
                    'logFile' => '@app/runtime/logs/votes/votes.log',
                    'logVars' => [],
                ],

            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*',
        ]
    ],
    'params' => $params,
    'modules' => [
        'v1' => [
            'class' => 'app\modules\rest\versions\v1\RestModule',
        ],
        // just for testing
        /*
        'ext_v1' => [
            'class' => 'app\modules\ext_rest\versions\v1\RestModule',
        ],
        'ext_v2' => [
            'class' => 'app\modules\ext_rest\versions\v2\RestModule',
        ],
        */
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['components']['log']['targets']['firebug'] =  [
        'class' => 'app\components\log\FirePHPTarget',
        'levels' => ['trace','info'],
        'categories' => ['firebug'],
    ];

}

return $config;
