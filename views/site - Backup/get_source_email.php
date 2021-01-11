<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Insert <b>your email</b>, \nNot customer's email", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>