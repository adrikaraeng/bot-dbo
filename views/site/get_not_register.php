<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];
    Yii::$app->telegram->sendMessage("Hi ".$nama_depan."\xF0\x9F\x99\x8F,\nSilahkan registrasi terlebih dahulu,\nKontak kami di @DBOmyIndihome.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>