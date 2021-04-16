<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    
$keyboard = [ 
    'resize_keyboard' => true,
    "keyboard" =>[
        [
            [
                'text' => "Start",
            ]
        ]
    ]
];

    Yii::$app->telegram->sendMessage("<b>Proses daftar berhasil dibatalkan</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>