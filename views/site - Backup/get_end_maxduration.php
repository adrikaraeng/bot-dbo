<?php 

    use yii\helpers\Html;
    use yii\helpers\Url;

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

    Yii::$app->telegram->sendMessage("Hi ".$nama_depan.", \nYou has been reached the maximum time limit \xF0\x9F\x99\x8F.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>