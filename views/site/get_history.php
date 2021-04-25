<?php 

use yii\helpers\Html;
use yii\helpers\Url;

$connection = \Yii::$app->db;
    
$keyboard = [ 
    'resize_keyboard' => true,
    "keyboard" =>[
        [
            [
                'text' => "Start",
            ],
            [
                'text' => "Cek Tiket",
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