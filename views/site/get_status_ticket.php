<?php 

use yii\helpers\Html;
use yii\helpers\Url;
// Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
// Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
$connection = \Yii::$app->db;
$connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
$connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();


    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];
    Yii::$app->telegram->sendMessage("Nama Pelanggan : <b>$nama_pelanggan</b>\nTicket status : <b>$status</b>\nKeluhan : <b>$keluhan</b>\nProgress Handling : $feedback\n\nBest Regards\n<b>DBO myIndiHome</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
    if($gambar != NULL):
        $path = \Yii::getAlias('@webroot/images')."/".$gambar;
        Yii::$app->telegram->sendPhoto($path, $chat_id);
    endif;
?>