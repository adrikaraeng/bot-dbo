<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$connection = \Yii::$app->db;
if (Yii::$app->user->isGuest) {
  Yii::$app->user->logout();
  return $this->goHome();
}
$id = Yii::$app->user->id;
// echo $id;
$duser = $connection->createCommand("SELECT * FROM user WHERE id='$id'")->queryOne(); 
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<style>
  body{
    background:#444444;
  }
  #webcam-pic{
    display:none;
    position: fixed;
    z-index:999999999999;
    top:3px;
    right:20px;
  }
</style>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => "<b id='dashboard-title'>Manohara</b>",
        'brandUrl' => '',
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Dashboard', 'url' => ['dashboard']],
            ['label' => 'Home', 'url' => ['index']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div id="webcam-pic" class="wow tada" data-wow-iteration="infinite"  data-wow-duration="1800ms">
      <?php if($duser['webcam'] != ''):?>
        <?=Html::img("@web/images/webcam/".$duser['webcam'],['style'=>"width:60px;height:60px;border-radius:50px;border:2px solid #0005d2;"])?>
      <?php else:?>
        <?=Html::img("@web/images/webcam/default.png",['style'=>"width:60px;height:60px;border-radius:50px;border:2px solid #0005d2;"])?>
      <?php endif;?>
    </div>
    <div class="container2">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
