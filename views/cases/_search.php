<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CasesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'tiket') ?>

    <?= $form->field($model, 'hp') ?>

    <?= $form->field($model, 'app_version') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'inet_pstn_track') ?>

    <?php // echo $form->field($model, 'keluhan') ?>

    <?php // echo $form->field($model, 'tanggal_masuk') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'gambar') ?>

    <?php // echo $form->field($model, 'feedback') ?>

    <?php // echo $form->field($model, 'login') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'source_email') ?>

    <?php // echo $form->field($model, 'kategori') ?>

    <?php // echo $form->field($model, 'sub_kategori') ?>

    <?php // echo $form->field($model, 'backend') ?>

    <?php // echo $form->field($model, 'urgensi_status') ?>

    <?php // echo $form->field($model, 'channel') ?>

    <?php // echo $form->field($model, 'sub_channel') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
