<?php 

use yii\helpers\Html;
use yii\helpers\Url;


    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Resend",
                ],
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Kode OTP telah terkirim ke email anda.\n<i>(Tekan <b>Resend</b> Jika belum menerima kode OTP)</i>\nMasukkan kode:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>