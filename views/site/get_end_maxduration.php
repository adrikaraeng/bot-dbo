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

    Yii::$app->telegram->sendMessage("Hai ".$nama_depan.", \nAnda telah mencapai batas waktu maksimum. Silahkan dicoba lagi \xF0\x9F\x99\x8F.", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>