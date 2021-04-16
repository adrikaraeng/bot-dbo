<?php 

use yii\helpers\Html;
use yii\helpers\Url;

  $keyboard = [
    'remove_keyboard' => true
  ];

  Yii::$app->telegram->sendMessage("Hai ".$nama_depan."\xE2\x9C\x8C,\nAkun anda nonaktif. Silahkan hubungi kami DBO myIndiHome, jika memerlukan bantuan. Terima kasih.", $chat_id, [
    'reply_markup' => json_encode($keyboard),
  ]);
?>