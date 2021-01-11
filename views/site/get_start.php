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

    Yii::$app->telegram->sendMessage("Waktu mulai sistem pada <b>$start_date</b>,\n Silahkan pilih kebutuhan anda:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>