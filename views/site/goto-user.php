<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
$this->registerJS("
    $(document).ready(function() {
        console.log('test');
        var url='".Url::toRoute(['user/index'])."';
        window.location.href=url;
    });
");
    echo "Yes";
?>

