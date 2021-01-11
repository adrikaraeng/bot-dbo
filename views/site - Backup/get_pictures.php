<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    // $keyboard = [
    //     'remove_keyboard' => true
    // ];


    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                ['text' => "Skip >>"]
            ]
		],
    ];
    Yii::$app->telegram->sendMessage("Insert an image of a case, click <b>skip</b> if don't have.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>