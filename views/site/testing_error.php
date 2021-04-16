<?php 

use yii\helpers\Html;
use yii\helpers\Url;

  $keyboard = [
    'remove_keyboard' => true
  ];

  Yii::$app->telegram->sendMessage("Test", $chat_id, [
    'reply_markup' => json_encode($keyboard),
  ]);
?>