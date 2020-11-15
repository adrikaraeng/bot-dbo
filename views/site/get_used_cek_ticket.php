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

    Yii::$app->telegram->sendMessage("<b>Using by another user, please try again in 5 minutes later</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>