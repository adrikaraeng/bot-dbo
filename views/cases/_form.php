<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cases */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cases-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tiket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'app_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inet_pstn_track')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keluhan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanggal_masuk')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'TO' => 'TO', 'On Progress' => 'On Progress', 'Closed' => 'Closed', 'New' => 'New', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'gambar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'login')->textInput() ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kategori')->textInput() ?>

    <?= $form->field($model, 'sub_kategori')->textInput() ?>

    <?= $form->field($model, 'backend')->textInput() ?>

    <?= $form->field($model, 'urgensi_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'channel')->textInput() ?>

    <?= $form->field($model, 'sub_channel')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
