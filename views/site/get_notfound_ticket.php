<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Buat Tiket",
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

    Yii::$app->telegram->sendMessage("<b>Tiket kendala tidak ditemukan</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>