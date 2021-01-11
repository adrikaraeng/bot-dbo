<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                ['text' => "147"],
                ['text' => "C4"],
			],
			[
                ['text' => "Social Media"],
                ['text' => "Plasa Telkom"],
			],
			[
                ['text' => "Sales"],
                ['text' => "Lainnya"],
                ['text' => "Exit"]
			]
		],
    ];

    Yii::$app->telegram->sendMessage("Where this case came from ?", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>