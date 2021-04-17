<?php 

use yii\helpers\Html;
use yii\helpers\Url;

    $keyboard = [
        'remove_keyboard' => true
    ];

    Yii::$app->telegram->sendMessage("<b><i>Maaf, sistem mengalami antrian</i></b>\nSilahkan input case anda dengan mengikuti <b>Format</b>: \n\n#[NAMA_PELANGGAN]\n#[EMAIL_PELANGGAN]\n#[HP]\n#[PSTN]\n#[INET]\n#[NOMOR TIKET/ ORDER]\n#[DESKRIPSI_KELUHAN] \n\n<b>Contoh:</b>\n#David Moergan\n#david@gmail.com\n#085012345678\n#\n#11223344556677\n#IN3642348\n#Sudah berhasil daftar, tapi SOD tidak aktif\n\n<b>Noted:</b>\n<i>(1) Nama Pelanggan, email, HP, dan Keluhan harus diinput</i>\n<i>(2) Data yang diinput berurutan mengikuti format</i>\n<i>(3) Jika ada data yang NULL diluar dari noted (1), Tetap menggunakan tanda <b>#</b> dan input berurutan mengikuti format.</i>", $chat_id, [
        'reply_markup' => json_encode($keyboard),
    ]);
    // callback(0);
?>