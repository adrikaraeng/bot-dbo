<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    // $keyboard = [
    //     'remove_keyboard' => true
    // ];


    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                ['text' => "Skip >>"]
            ]
		],
    ];
    Yii::$app->telegram->sendMessage("Masukkan gambar, klik <b>skip</b> jika tidak ada.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>