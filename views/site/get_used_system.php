<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("Masih digunakan user lain, silahkan dicoba kembali 5 menit kemudian.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>