<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Masukkan tiket anda:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // die();
?>