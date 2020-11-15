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
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Hi ".$nama_depan.", \nYou're case has been exist : <b>$tiket</b>\nStatus : <b>$status</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>