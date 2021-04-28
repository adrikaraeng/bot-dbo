<?php 
  use yii\helpers\Html;
  use yii\helpers\Url;
  
  $keyboard = [ 
    'resize_keyboard' => true,
    "keyboard" =>[
      [
          [
              'text' => "Start",
          ],
      ]
    ]
  ];
  Yii::$app->telegram->sendMessage($feedback, $chat_id, [
      'reply_markup' => json_encode($keyboard),
  ]);
  
  // if($gambar != NULL && $gambar != ''):
  //   $path = \Yii::getAlias('@webroot/images')."/".$gambar;
  //   Yii::$app->telegram->sendPhoto($path, $chat_id);
  // endif;

  Yii::$app->response->redirect(['user/index']);
?>