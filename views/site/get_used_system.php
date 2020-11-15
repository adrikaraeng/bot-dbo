<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Using by another user, please try again on 5 minutes later.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>