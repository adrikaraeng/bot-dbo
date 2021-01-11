<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
    

<div class="d-flex justify-content-center h-100">
    <div class="card">
        <div class="card-body">
            <h1 style="text-align:center;color:#fff;"><?= Html::encode($this->title) ?></h1>
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
            ]); ?>
                <?= $form->field($model, 'username', $user)->textInput(['autofocus' => true, 'placeholder'=>"ID Prener", 'maxLength'=>20])->label(false) ?>

                <?= $form->field($model, 'password', $lock)->passwordInput(['placeholder'=>"Password"])->label(false) ?>

                <div class="form-group" style="text-align:center;">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-success', 'name' => 'login-button' ,'style' => "padding:20px;padding-top:10px;padding-bottom:10px;"])?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    function gotoHome(){
        window.location.href = '<?=Url::to(['index'])?>';
    }
</script>