<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Hai <b>$nama_depan,</b>\nSaat ini status anda terblokir.\nSilahkan menghubungi kami #DBOmyIndiHome. Terima kasih.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>