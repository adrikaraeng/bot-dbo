<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Create Ticket",
                ],
                [
                    'text' => "Check Ticket",
                ],
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("<b>Ticket not found</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>