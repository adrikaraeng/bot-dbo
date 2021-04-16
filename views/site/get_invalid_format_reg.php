<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    
$keyboard = [ 
    'resize_keyboard' => true,
    "keyboard" =>[
        [
            [
                'text' => "Cancel",
            ]
        ]
    ]
];

    Yii::$app->telegram->sendMessage("<b><i>Kesalahan input</i></b>\nSilahkan daftar dengan mengikuti <b>Format dan Data yang benar</b>: \n\n#[NAMA LENGKAP]\n#[EMAIL]\n#[NOMOR HP]\n#[REGIONAL]\n#[WITEL]\n#[LAYANAN]\n#[ID PRENER] \n\n<b>Contoh:</b>\n#Hendra\n#hendra@gmail.com\n#085012345678\n#6\n#Telkom Balikpapan\n#Plasa Telkom\n#355448", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>