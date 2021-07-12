<?php 

use yii\helpers\Html;
use yii\helpers\Url;

$connection = \Yii::$app->db;

$connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
// $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();

$keyboard = [ 
  'resize_keyboard' => true,
  "keyboard" =>[
    [
      [
        'text' => "Start",
      ],
    ]
  ]
];

Yii::$app->telegram->sendMessage("Hai ".$nama_depan.", \nTiket anda berhasil dibuat dengan kode <b>$tiket</b>", $chat_id, [
  'reply_markup' => json_encode($keyboard),
]);
?>