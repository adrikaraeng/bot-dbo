<?php
use \yii\web\Request;

$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db2 = require __DIR__ . '/db2.php';

$config = [
    'timeZone' => 'Asia/Jakarta',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        // 'telegram' => [
        //     'class' => 'aki\telegram\Telegram',
        //     'botToken' => '1750605224:AAGmgpPzTKvcv_-1FmWm5KxYVm_FzMHD7SY',
        // ],
        'telegram' => [
            'class' => 'mirkhamidov\telegramBot\TelegramBot',
            'botToken' => '1248348390:AAFFW--HaNLA7z7ign7uMhEhPWyWqDXEkzw',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'baseUrl' => $baseUrl,
            'cookieValidationKey' => 'QNtnNYCn7i6SQYqTK8-Cb_W9IdrBlOj_',
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
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'tiketmyindihomebot@gmail.com',
                'password' => 'dbomyindihome2021',
                'port' => '587',
                'encryption' => 'tls'
            ],
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
        'db' => $db,
        'db2' => $db2,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // '<controller:\w+>/<id:\d+>' => '<controller>/view',
                // '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ],
        ],
        
    ],
    'params' => $params,
    // 'defaultRoute' => 'site/login',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    // $config['bootstrap'][] = 'debug';
    // $config['modules']['debug'] = [
    //     'class' => 'yii\debug\Module',
    //     // uncomment the following to add your IP if you are not connecting from localhost.
    //     //'allowedIPs' => ['127.0.0.1', '::1'],
    // ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
        // 'password' => 'adrey'
    ];
}

return $config;
