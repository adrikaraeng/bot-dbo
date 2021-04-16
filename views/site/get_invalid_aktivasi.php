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

    Yii::$app->telegram->sendMessage("Kode OTP yang anda masukkan salah, cek kembali email anda.\n<i>(Tekan <b>Resend</b> Jika belum menerima kode OTP)</i>\nSilahkan masukkan kode:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>