<?php 

use yii\helpers\Html;
use yii\helpers\Url;

$connection = \Yii::$app->db;

$connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();
    
$keyboard = [ 
    'resize_keyboard' => true,
    "keyboard" =>[
        [
            [
                'text' => "Search",
            ],
            [
                'text' => "Start",
            ],
            [
                'text' => "Exit",
            ]
        ]
    ]
];

    Yii::$app->telegram->sendMessage($model, $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>