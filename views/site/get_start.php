<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Buat Tiket",
                ],
                [
                    'text' => "Cek Tiket",
                ],
                [
                    'text' => "Search",
                ]
            ],
            [
                [
                    'text' => "History",
                ],
                [
                    'text' => "Exit"
                ]
            ]
        ]
    ];

    Yii::$app->telegram->sendMessage("Hai <b>$nama_depan</b>,$load_info\nWaktu mulai sistem pada <b>$start_date</b>,\nSilahkan pilih kebutuhan anda:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    
?>