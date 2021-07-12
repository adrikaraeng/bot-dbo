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
  
  if($gambar != NULL && $gambar != ''):
    // $path = \Yii::getAlias('@webroot/images')."/".$nama_gambar;
    file_put_contents(\Yii::getAlias('@webroot/images')."/".$nama_gambar, $gambar);
    $tempGambar = \Yii::getAlias("@webroot/images/".$nama_gambar);
    if(Yii::$app->telegram->sendPhoto($tempGambar, $chat_id)){
        $oldFile = Yii::$app->basePath."/web/images/".$nama_gambar;
        if(file_exists($oldFile)):
            unlink($oldFile);
        endif;
    }
  endif;

  // Yii::$app->response->redirect(['user/index']);
?>