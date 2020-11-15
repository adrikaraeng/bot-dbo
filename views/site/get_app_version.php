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
                    'text' => "3.81",
                ],
                [
                    'text' => "3.80",
                ],
                [
                    'text' => "3.70",
                ],
            ],
            [
                [
                    'text' => "3.10",
                ],
                [
                    'text' => "3.00",
                ],
                [
                    'text' => "PARTNER",
                ],
                [
                    'text' => "Exit",
                ]
            ],
        ]
    ];

    Yii::$app->telegram->sendMessage("Select the version of myIndiHome customer or partner :", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>