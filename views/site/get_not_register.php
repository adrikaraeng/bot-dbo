<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Daftar",
                ]
            ]
        ]
    ];
    Yii::$app->telegram->sendMessage("Hai <b>".$nama_depan."</b>\xF0\x9F\x99\x8F\nAnda belum terdaftar dan belum bisa menggunakan @tiketmyindihomebot, silahkan daftar terlebih dahulu.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>