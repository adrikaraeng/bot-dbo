<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
use app\models\UserTelegram;

?>
<style>
  #my_camera{
    width: 320px;
    height: 240px;
    border: 1px solid black;
  }
</style>

<div class="d-flex justify-content-center h-100">
    <div class="card">
        <div class="card-body">
            <h3 style="text-align:center;color:#fff;">Sign in to Manohara</h3>
            <div style="text-align:center;color:#fff;font-style:italic;">--Monitoring Assurance NonTiket Hasil Telegrambot--</div>
            <?php
                $user = [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
                ];
                $lock = [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
                ];
            ?>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                // 'action' => Url::to(['save-webcam']),
            ]); ?>

                <?= $form->field($model, 'username', $user)->textInput(['autofocus' => true, 'placeholder'=>"ID Prener", 'maxLength'=>20])->label(false) ?>

                <?= $form->field($model, 'password', $lock)->passwordInput(['placeholder'=>"Password"])->label(false) ?>

                <div class="form-group" style="text-align:center;">
                    <?= Html::submitButton('Sign In', ['class' => 'btn btn-success', 'name' => 'login-button' ,'style' => "padding:20px;padding-top:10px;padding-bottom:10px;"])?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
  // navigator.getUserMedia = (navigator.getUserMedia ||
  // navigator.webkitGetUserMedia ||
  // navigator.mozGetUserMedia ||
  // navigator.msGetUserMedia);

  // if(navigator.getUserMedia) {
  //     navigator.getUserMedia({
  //     audio: true,
  //     video: true
  //   },
  //   function(stream) {
  //     // returns true if any tracks have active state of true
  //     var result = stream.getVideoTracks().some(function(track) {
  //       return track.enabled && track.readyState === 'live';
  //     });
  //   },
  //   function(e) {
  //     alert("Kamera harus aktif ya :D");
  //   });
  // }
  function gotoHome(){
      window.location.href = '<?=Url::to(['index'])?>';
  }
</script>