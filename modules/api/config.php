<?php

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'enableSession' => false,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'task',
                    'pluralize' => false,
                ],
            ],
        ],
    ],
];

return $config;