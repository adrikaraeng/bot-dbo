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

    Yii::$app->telegram->sendMessage("\xE2\x96\xB6 Pencarian menggunakan <b>Email Customer/CP Customer/Nomor Internet/Nomor PSTN</b>.\nSilahkan input salah satu option:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>