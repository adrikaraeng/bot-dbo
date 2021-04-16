<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Start",
                ],
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Hai ".$nama_depan."\xE2\x9C\x8C,\nSelamat datang di tiket myIndiHome bot.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>