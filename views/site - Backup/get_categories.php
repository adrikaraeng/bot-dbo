<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [ 
        'resize_keyboard' => true,
        "keyboard" =>[
            [
                [
                    'text' => "Sign Up",
                ],
                [
                    'text' => "Log In",
                ]
            ],
            [
                [
                    'text' => "Reset Akun",
                ],
                [
                    'text' => "Tambah No.Layanan (Mapping)",
                ]
            ],
            [
                [
                    'text' => "Tagihan",
                ],
                [
                    'text' => "Transaksi Add On",
                ]
            ],
            [
                [
                    'text' => "Unsubscribe",
                ],
                [
                    'text' => "Lapor Gangguan",
                ]
            ],
            [
                [
                    'text' => "POIN",
                ],
                [
                    'text' => "Order PSB",
                ]
            ],
            [
                [
                    'text' => "Top Up Kuota",
                ],
                [
                    'text' => "Dompet myIndiHome",
                ]
            ],
            [
                [
                    'text' => "FUP",
                ],
                [
                    'text' => "Cancel",
                ]
            ],
        ]
    ];

    Yii::$app->telegram->sendMessage("Choose the category case:", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
?>