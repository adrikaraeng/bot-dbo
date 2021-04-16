<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Continue",
                ],
                [
                    'text' => "Cancel",
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Hai ".$nama_depan.",\nData case ini telah dibuat sebelumnya di\nNomor tiket : <b>$tiket</b>\nKeluhan : <b>$keluhan</b>\n\nTekan <b>Continue</b> untuk tetap melanjutkan buat tiket, atau <b>Cancel</b> untuk membatalkan buat tiket.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    die();
?>