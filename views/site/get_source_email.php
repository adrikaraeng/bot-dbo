<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Masukkan <b>email anda</b>, \nbukan email customer", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>