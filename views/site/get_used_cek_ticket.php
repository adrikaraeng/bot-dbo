<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Home",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("<b>Masih digunakan user lain, silahkan dicoba kembali 5 menit kemudian.</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>