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
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Hai <b>$nama_depan,</b>\nAnda telah berhasil aktivasi.\nKlik Start untuk melanjutkan.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>