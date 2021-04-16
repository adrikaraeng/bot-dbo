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
            <h3 style="text-align:center;color:#fff;">Sign in to Cinderela</h3>
            <div style="text-align:center;color:#fff;">---Case myIndiHome Source Telegram---</div>
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
                <div class="row">
                  <div class="col-md-12" align="center">
                    <div id="my_camera"></div>
                  </div>
                </div>

                <?= $form->field($model, 'username', $user)->textInput(['autofocus' => true, 'placeholder'=>"ID Prener", 'maxLength'=>20])->label(false) ?>

                <?= $form->field($model, 'password', $lock)->passwordInput(['placeholder'=>"Password"])->label(false) ?>

                <div class="form-group" style="text-align:center;">
                    <?= Html::submitButton('Sign In', ['class' => 'btn btn-success', 'name' => 'login-button' ,'style' => "padding:20px;padding-top:10px;padding-bottom:10px;"])?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
  $this->registerJS("
    Webcam.set({
      width: 320,
      height: 240,
      image_format: 'jpeg',
      jpeg_quality: 90
    });
    Webcam.attach('#my_camera');
    
    var url='".Url::toRoute(['user/index'])."';
    $('#login-form').on('beforeSubmit', function(e){
      e.preventDefault();
      var form = $(this);
      $.ajax({
        url : 'login',
        type :'post',
        data : form.serialize(),
        dataType : 'json',
        success: function(res){
          if(res=='Yes'){
            Webcam.snap( function(data_uri) {
              Webcam.upload( data_uri, '".Url::to(['save-webcam'])."');
            });
          }
          $.ajax({
            url : 'goto-user',
            type: 'post',
            data: 'res='+res,
            success: function(res2){
              window.location.href=url;
            }
          });
        }
      });
      // window.location.href=url;
      return false;
    });
  ");
?>
<script>
  navigator.getUserMedia = (navigator.getUserMedia ||
  navigator.webkitGetUserMedia ||
  navigator.mozGetUserMedia ||
  navigator.msGetUserMedia);

  if(navigator.getUserMedia) {
      navigator.getUserMedia({
      audio: true,
      video: true
    },
    function(stream) {
      // returns true if any tracks have active state of true
      var result = stream.getVideoTracks().some(function(track) {
        return track.enabled && track.readyState === 'live';
      });
    },
    function(e) {
      alert("Kamera harus aktif ya :D");
    });
  }
  function gotoHome(){
      window.location.href = '<?=Url::to(['index'])?>';
  }
</script>