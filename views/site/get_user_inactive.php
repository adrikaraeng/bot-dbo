<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Hai <b>$nama_depan,</b>\nMohon maaf, saat ini anda terblokir untuk penggunaan @tiketmyindihomebot\xF0\x9F\x99\x8F.\nSilahkan menghubungi kami #DBOmyIndiHome. Terima kasih.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>