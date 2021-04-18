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

    Yii::$app->telegram->sendMessage("\xF0\x9F\x94\x94 Pencarian case menggunakan\n<b>Email customer/Nomor HP/Nomor Internet/Nomor PSTN</b>.\nSilahkan input salah satu option:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>