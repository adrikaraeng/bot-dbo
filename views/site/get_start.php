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

    Yii::$app->telegram->sendMessage("Your start activity on <b>$start_date</b>,\n Please choose your needs :", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>