<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .detail-case{
      clear:left;
      text-align:left;
      background-color:#f4ef93;
      border:1px solid #eee759;
      padding:7px;
      border-radius:7px;
    }
  </style>
</head>
<body>
  <div class="row">
    <span style="font-size:1.4em;"><b>Semangat pagi <?=$nama_depan." \xE2\x9C\x8C"?></b></span>
    <p>
      <div class="detail-case">
        <span style="font-size:1.2em;">Kamu telah berhasil membuat tiket kendala dengan data sebagai berikut:</span> <br><br>
        Tiket bot : <b><?=$cek_case['tiket']?></b><br>
        Nama Pelanggan : <b><?=$cek_case['nama']?></b><br>
        Nomor HP : <b><?=$cek_case['hp']?></b><br>
        Email : <b><?=$cek_case['email']?></b><br>
        PSTN : <b><?=$cek_case['pstn']?></b><br>
        Nomor Internet : <b><?=$cek_case['inet']?></b><br>
        Versi Aplikasi : <b><?=$cek_case['app_version']?></b><br>
        Nomor Tiket/Order : <b><?=$cek_case['no_tiket']?></b><br>
        Detail Kendala : <b><?=$cek_case['keluhan']?></b><br>
        <?php if($cek_case['gambar'] != NULL && $cek_case['gambar'] != ""):?>
          <img src="<?=$r_source->embed(\Yii::getAlias('@webroot/images/'.$cek_case['gambar']),[
            'fileName' => 'evidence.png',
            'contentType' => 'image/png'
          ])?>">
        <?php endif;?>
        <br>
        Saat ini sudah kami terima dan sedang dalam proses penanganan <?="\xF0\x9F\x99\x8F"?>, silahkan melakukan pengecekan status tiket secara berkala di <a href="https://t.me/tiketmyindihomebot">@tiketmyindihome</a>. <br><br>
        <b>Best Regards</b><br>#DBOmyIndiHomebr<br>Terima kasih

      </div>
    </p>
  </div>
</body>
</html>
<?php $this->endPage()?>