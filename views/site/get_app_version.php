<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
          [
            [
              'text' => "3.85",
            ],
            [
              'text' => "PARTNER",
            ],
            [
              'text' => "myIH X",
            ],
            [
              'text' => "Exit",
            ],
          ],
        ]
    ];

    Yii::$app->telegram->sendMessage("Pilih versi aplikasi myIndihome atau Partner :", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>