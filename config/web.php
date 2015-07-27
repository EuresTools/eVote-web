<?php
use kartik\datecontrol\Module;

$params = require(__DIR__ . '/params.php');

$config = [
    'sourceLanguage' => 'en-GB',
    'language' => 'en-GB',

    // 'sourceLanguage' => 'en-US',
    // 'language' => 'en-US', //'de',

    'timeZone'=> 'UTC',
    //'timeZone'=> 'Europe/Berlin',

    'id' => 'eVote',
    'name'=>'eVote',

    'layout'=>'default',
    'defaultRoute' => 'vote/index',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'languageSwitcher'],

    'components' => [
         'i18n' => [
            'class' => 'app\components\I18N',
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    // only for development
                    //'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
                // 'yii*' => [
                //     'class' => 'yii\i18n\PhpMessageSource',
                //     'basePath' => '@app/messages',
                //     'sourceLanguage' => 'en',
                //     'fileMap' => [
                //         'yii' => 'yii.php',
                //     ],
                // ],
            ],
        ],
        'languageSwitcher' => [
            'class' => 'app\components\languageSwitcher',
        ],
        'formatter' => [
            'defaultTimeZone' => 'UTC',
            'timeZone' => 'Europe/Berlin',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager' 'yii\rbac\PhpManager'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'enableStrictParsing' => true,
            'rules' => [
                //'/' => 'site/index', // must be removed otherwise the defaultRoute wouldn't work

                /*
                'poll/<poll_id:\d+>/members' => 'member/index',
                'poll/<poll_id:\d+>/members/<id:\d+>' => 'member/view',
                'poll/<poll_id:\d+>/members/<action:\w+>' => 'member/<action>',
                'poll/<poll_id:\d+>/members/<action:\w+>/<id:\d+>' => 'member/<action>',
                */

                [
                    // PollSpecificUrlRule rule for links like /poll/poll_id/controller
                    // 'class' => 'app\components\UrlRules\PollSpecificUrlRule',
                    'pattern' =>'poll/<poll_id:\d+>/<controller:(member|code|vote|email)>s',
                    'route' => '<controller>'
                ],
                [
                    // PollSpecificUrlRule rule for links like /poll/poll_id/controller/id
                    // 'class' => 'app\components\UrlRules\PollSpecificUrlRule',
                    'pattern' =>'poll/<poll_id:\d+>/<controller:(member|code|vote|email)>s/<id:\d+>',
                    'route' => '<controller>/view'
                ],
                [
                    // PollSpecificUrlRule rule for links like /poll/poll_id/controller/action
                    // 'class' => 'app\components\UrlRules\PollSpecificUrlRule',
                    'pattern' =>'poll/<poll_id:\d+>/<controller:(member|code|vote|email)>s/<action:[-a-zA-Z]*>',
                    'route' => '<controller>/<action>'
                ],
                [
                    // PollSpecificUrlRule rule for links like /poll/poll_id/controller/action/id
                    //'class' => 'app\components\UrlRules\PollSpecificUrlRule',
                    'pattern' =>'poll/<poll_id:\d+>/<controller:(member|code|vote|email)>s/<action:[-a-zA-Z]*>/<id:\d+>',
                    'route' => '<controller>/<action>'
                ],


                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                // not needed
                // '<controller:\w+>' => '<controller>/index',  // breaks admin module.
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'q02_5UU7HN4HNw-QBGER-fK39vMlNOFt',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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

            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'admin/*', // add or remove allowed actions to this list
            'debug/*',
            'gii/*',
            '*',
        ]
    ],
    'params' => $params,
    'modules' => [
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
            'displaySettings' => [
                // Module::FORMAT_DATE => 'd MMM, yyyy',
                // Module::FORMAT_TIME => 'HH:mm a',
                // Module::FORMAT_DATETIME => 'd MMMM, yyyy HH:mm', // 'dd-MM-yyyy HH:mm:ss a',
            ],
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            'displayTimezone' => 'Europe/Berlin',

            // set your timezone for date saved to db
            'saveTimezone' => 'UTC', // save as UTC

            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['pluginOptions'=>['autoclose'=>true]], // example
                Module::FORMAT_DATETIME =>  ['pluginOptions'=>['autoclose'=>true]],  // setup if needed
                Module::FORMAT_TIME => ['pluginOptions'=>['autoclose'=>true]], // setup if needed
            ],

            /*
            'widgetSettings' => [
                'class' => 'yii\jui\DateTimePicker',
                'displaySettings' => [
                    Module::FORMAT_DATETIME => 'd M, yyyy HH:mm',
                ],
            ],
            */
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
        ],
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
            // other module settings
            'downloadAction' => 'gridview/export/download',
            // i18n requires an alias named '@kvgrid' => '@vendor/kartik-v/yii2-grid/messages',
            /*
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true
            ],
            */
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';


     $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'generators' => [
            // neue gii generators from extension
            'eurescomModel' => [
                    'class' => 'app\components\mygii\generators\model\Generator',
            ],
            'eurescomCrud' => [
                    'class' => 'app\components\mygii\generators\crud\Generator',
            ],
        ]
    ];

    $config['components']['log']['targets']['firebug'] =  [
        'class' => 'app\components\log\FirePHPTarget',
        'levels' => ['trace','info'],
        'categories' => ['firebug'],
    ];
}

return $config;
