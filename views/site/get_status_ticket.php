<?php 

use yii\helpers\Html;
use yii\helpers\Url;
// Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
// Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
$connection = \Yii::$app->db;
// $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
// $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();

if($status == "Closed"):
    $sts = "\xE2\x9C\x85";
else:
    $sts = "\xE2\x8C\x9B";
endif;


    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Cek Tiket",
                ],
                [
                    'text' => "Exit",
                ]
            ]
        ]
    ];
    Yii::$app->telegram->sendMessage("Nama Pelanggan : <b>$nama_pelanggan</b>\nStatus Tiket : <b>$status</b> $sts\n\nKeluhan : <b>$keluhan</b>\n\nPenanganan : <b>$feedback</b>\n\nBest Regards\n<b>DBO myIndiHome</b>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
    
    if($gambar != NULL):
        // $path = \Yii::getAlias('@webroot/images')."/".$nama_gambar;
        file_put_contents(\Yii::getAlias('@webroot/images')."/".$nama_gambar, $gambar);
        $tempGambar = \Yii::getAlias("@webroot/images/".$nama_gambar);
        if(Yii::$app->telegram->sendPhoto($tempGambar, $chat_id)){
            $oldFile = Yii::$app->basePath."/web/images/".$nama_gambar;
            if(file_exists($oldFile)):
                unlink($oldFile);
            endif;
        }

    endif;

    // if($gambar != NULL):
    //     $path = \Yii::getAlias('@webroot/images')."/".$gambar;
    //     Yii::$app->telegram->sendPhoto($path, $chat_id);
    // endif;
?>