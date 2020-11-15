<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Kategori;
use app\models\SubKategori;
use app\models\SubChannel;
use app\models\Backend;
use app\models\UrgensiStatus;
use app\models\AppVersion;

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Cases');
$this->params['breadcrumbs'][] = $this->title;

$connection = \Yii::$app->db;
?>
<div id="case-telegram">
  <div id="onprogress-case">
    <div class="head-case">On Progress Case</div>
    <div class="body-case">
        <?php if($case_onprogress != NULL):?>
            <?php foreach($case_onprogress as $c_onprog => $cop):?>
                <?php
                  $date_awal = new DateTime($cop->tanggal_masuk);
                  if($cop->status_owner=="Closed"):
                    $date_akhir = new DateTime($cop->tanggal_closed);
                  else:
                    $date_akhir =  new DateTime(date('Y-m-d H:i:s'));
                  endif;
                  if($cop->follow_up == NULL || $cop->follow_up == ''):
                    $cop->follow_up = 0;
                  endif;

                  $age_mine=$date_awal->diff($date_akhir);
                  // echo date('Y-m-d H:i:s');
                ?>
                <div id="list-onprogress" onclick="getOnprogressCase('<?=$cop->id?>')">
                  <div>
                    <div style="text-align:left;float:left;"><b><?=$cop->tiket?></b></div>
                    <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_mine->format("%H:%I")?></div>
                  </div>
                  <div><?=$cop->nama?><span style="float:right;font-size:0.9em;font-weight:bold;">FU: <?=$cop->follow_up?></span></div>
                  <div style="font-size:0.7em;">
                    <span style="float:left;"><b><?=$cop->login?></b></span>
                    <span style="float:right;clear:right;"><?=date('d-m-Y H:i:s', strtotime($cop->tanggal_masuk))?></span>
                  </div>
                  <div style="clear:left;"></div>
                </div>
            <?php endforeach;?>
        <?php else:?>
            <i>Data not found</i>
        <?php endif;?>
    </div>
  </div>

  <?php
    $month = date('m');
    $consum_daily_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND MONTH(tanggal_masuk)='$month' AND DATE(tanggal_closed)=DATE(tanggal_masuk)")->queryScalar();
    $consum_daily_nods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND DATE(tanggal_closed)<>DATE(tanggal_masuk) AND MONTH(tanggal_masuk)='$month'")->queryScalar();
    $consum_daily_op = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='On Progress'")->queryScalar();
    $consum_daily_to = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='TO'")->queryScalar();
    if($user->divisi=="SOLVER"):
      $consum_new = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>''")->queryScalar();
    elseif($user->divisi=="MONITORING"):
      $consum_new = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE status_owner='New' AND no_tiket IS NULL AND no_tiket=''")->queryScalar();
    endif;
  ?>

  <div style="position:fixed;left:5px;top:50px;">
    <span class="btn btn-success" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;" title="One Day Service">
      <div style="font-weight:bold;">ODS</div>
      <div><?=$consum_daily_ods?></div>
    </span>
    <span class="btn btn-primary" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;" title="Non-One Day Service">
      <div style="font-weight:bold;">NODS</div>
      <div><?=$consum_daily_nods?></div>
    </span>
    <span class="btn btn-danger" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;" title="On Progress">
      <div style="font-weight:bold;">OP</div>
      <div><?=$consum_daily_op?></div>
    </span>
    <span class="btn btn-warning" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;" title="Take Owner">
      <div style="font-weight:bold;">TO</div>
      <div><?=$consum_daily_to?></div>
    </span>
  </div>
  
<?php if($model != NULL):?>
  
  <div style="top:50px;position:fixed;right:30px;">
    <span class="btn btn-success" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;float:left;height:40px;padding-top:12px;" title="Klik Untuk Cek New Case" onclick="refreshCase('<?=$model->id?>')">
      <div style="font-weight:bold;"><span class="fa fa-refresh"></span> Refresh</div>
    </span>
    <span style="float:left;padding-left:6px;">
      <div style="font-size:0.8em;height:40px;padding-top:12px;">New : <b><?=$consum_new?></b></div>
    </span>
  </div>

<?php 
  $kategori = ArrayHelper::map(Kategori::find()->all(),'id','nama_kategori');
  $subkategori = ArrayHelper::map(SubKategori::find()->where("id_kategori='$model->kategori'")->all(),'id','sub_kategori');
  $subchannel = ArrayHelper::map(SubChannel::find()->where("id_channel='$model->channel'")->all(),'id','nama_sub_channel');
  $urgensi = ArrayHelper::map(UrgensiStatus::find()->all(),'urgensi_status','urgensi_status');
  $app = ArrayHelper::map(AppVersion::find()->orderBy("id DESC")->all(),'version','version');
  $source = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$model->telegram_id'")->queryOne();
?>

<div class="user-index">
  <b><?=$user->nama_lengkap?></b>
  
  <div class="row-cases-form">
    <?php $form = ActiveForm::begin([
        'id'=>'form-case',
        'action' => ['user/simpan-case', 'id'=>$model->id],
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'options' => ['enctype' => 'multipart/form-data'],
        'validationUrl' => Url::toRoute(['/user/ajax-ceksimpan', 'id'=>$model->id]),
    ]); ?>
      <?php if($model->login == $user->username && $model->status_owner == "On Progress" || $model->login == $user->username && $model->status_owner == "TO"):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to reopen the ticket" onclick="getReopen('<?=$model->id?>')"><span class="label label-danger">Take Off</span></div>
      <?php elseif($model->status_owner == "New"):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to take ownership" onclick="getCase('<?=$model->id?>')"><span class="label label-primary">Take Owner</span></div>
      <?php elseif($model->login != $user->username):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Progressed by <?=$model->login?>"><span class="label label-success"><?=$model->login?></span></div>
      <?php endif;?>
    
      <div style="text-align:right;margin-right:30px;"><b>Tiket : <?=$model->tiket?></b></div>
      <div style="text-align:right;margin-right:30px;font-size:0.7em;font-style:italic;"><?=date('d-m-Y H:i:s', strtotime($model->tanggal_masuk))?></div>
      <div style="clear:left;"><span style="font-weight:bold;">Source :</span><?=$source['layanan']."/".$source['nama_lengkap']?></div>

      <div class="row">
        <div class="col-lg-3">
          <?= $form->field($model, 'nama')->textInput(['maxlength' => 50, 'readonly'=>true]) ?>

          <div class="row">
            <div class="col-lg-6">
              <?= $form->field($model, 'inet')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-lg-6">
              <?= $form->field($model, 'pstn')->textInput(['maxlength' => 50]) ?>
            </div>
          </div>

          <?= $form->field($model, 'hp')->textInput(['maxlength' => 50, 'readOnly'=>true]) ?>

          <?= $form->field($model, 'email')->textInput(['maxlength' => 50]) ?>
          
        </div>
        <div class=col-lg-3>
          <?= $form->field($model, 'no_tiket')->textInput(['maxlength' => 50, 'readOnly'=>true]) ?>

          <?= $form->field($model, 'kategori')->widget(Select2::classname(), [
                'data' => $kategori,
                'options'=>[
                  'placeholder' => Yii::t('app','Select Kategori'),
                  'onchange' => '
                    $.post("get-subkategori?id_kategori='.'"+$(this).val(), function(data){
                      $("#sub-kategori-search").html(data);
                    });
                  '
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);?>
            
          <?= $form->field($model, 'sub_kategori')->widget(Select2::classname(), [
                'data' => $subkategori,
                'options'=>['placeholder'=>Yii::t('app','Select Sub-Kategori'), 'id' => "sub-kategori-search"],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);?>

            
          <?php if($model->backend != NULL):
            $bc = $connection->createCommand("SELECT * FROM backend WHERE id='$model->backend'")->queryOne();
            $dbackend = [$bc['id'] => $bc['nama_backend']];
          else:
            $dbackend = NULL;
          endif;?>

          <?= $form->field($model, 'backend')->widget(DepDrop::classname(), [
                'data' => $dbackend,
                'type'=>DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'backend-search'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['sub-kategori-search'],
                    'url'=>Url::to(['/user/getbackend']),
                    'placeholder'=>Yii::t('app','Select Backend'),
                ]
              ]);
            ?>
          
            <?= $form->field($model, 'app_version')->widget(Select2::classname(), [
                  'data' => $app,
                  'options'=>[
                    'id' => "app-search",
                    'placeholder' => Yii::t('app','Select App Version')
                  ],
                  'pluginOptions' => [
                      'allowClear' => true,
                  ],
              ]);?>
        </div>
        <div class="col-lg-3">
            
          <?= $form->field($model, 'keluhan')->textarea(['rows' => 4,'id'=>'keluhan','placeholder'=>'Remark / Keluhan', 'style'=>'resize:none;','readonly'=>true]) ?>

          <?= $form->field($model, 'feedback')->textarea(['rows' => 4,'id'=>'feedback','placeholder'=>'Feedback/ Proses penanganan', 'style'=>'resize:none;'])->label(false) ?>

          <?= $form->field($model, 'status_owner')->radioList(['Closed'=>'Closed','On Progress'=>'On Progress'],['style'=>"clear:left;",'id'=>'status-progress'])->label(); ?>

          <?php if($model->status_owner == "TO" && $model->login==$user->username || $model->status_owner == "On Progress" && $model->login==$user->username):?>
          <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
          </div>
          <?php endif;?>
        </div>
        <div class="col-lg-3">
          <div style="margin-top:10px;"><b>Feedback Gambar</b></div>
          <?=
              FileInput::widget([
                  'model' => $model,
                  'attribute' => 'feedback_gambar',
                  'options' => ['multiple'=>false, 'accept' => 'image/*'],
                  'pluginOptions' => [
                      'allowedFileExtensions' => ['jpg','jpeg','png'],
                      'previewFileType' => 'image',
                      'showPreview' => false,
                      'showUpload' => false,
                      'maxFileSize'=> 3052,
                      'browseOnZoneClick' => true,
                      // 'maxFileCount' => 5,
                      'browseLabel' => '',
                      'browseClass' => "btn btn-primary",
                      // 'browseIcon' => "<i class='fa fa-folder-open' style='font-size:1.9em;color:#fff;'></i>"
                  ]
              ]);
          ?>
          <?php if($model->gambar != NULL):?>
            <div id="name-file"><b>Bukti Gambar</b></div>
            <div id="gambar-search">
                <?=Html::img("@web/images/".$model->gambar, [
                  'id' => "gbr-sml",
                  'style' => " width:100%;max-width:300px;height:100%;border:0.5px solid #000;border-radius:5px;cursor:pointer;max-height:300px;",
                  // 'title'=>'Bukti Gambar',
                  'tabindex' => "0",
                  'data-pjax' => '0',
                  'data-trigger' => "focus",
                  'data-html' => "true",
                  'data-toggle' => 'popover',
                  'data-placement' => 'left',
                  'aria-describedby'=>"popover-gbr-lg",
                  'data-content' => $this->render('@app/views/user/gambar-large',[
                    'gambar' => $model->gambar
                  ])
                ])?>
            <?php endif;?>
          </div>
        </div>
      </div>
    <?php ActiveForm::end(); ?>
</div>
<?php else:?>
  
  <div style="top:50px;position:fixed;right:30px;">
    <span class="btn btn-success" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;float:left;height:40px;padding-top:12px;" title="Klik Untuk Cek New Case" onclick="refreshServ()">
      <div style="font-weight:bold;"><span class="fa fa-refresh"></span> Refresh</div>
    </span>
    <span style="float:left;padding-left:6px;">
      <div style="font-size:0.8em;height:40px;padding-top:12px;">New : <b><?=$consum_new?></b></div>
    </span>
  </div>
  <div style="color:#c40000;font-weight:bold;">No results found !</div>
<?php endif;?>


    <div id="new-case">
      <div class="head-case">List New Case</div>
      <div class="body-case">
        <?php if($case_new != NULL):?>
            <?php foreach($case_new as $c_new => $cn):?>
                <?php
                  $date_awal = new DateTime($cn->tanggal_masuk);
                  if($cn->status_owner=="Closed"):
                    $date_akhir = new DateTime($cn->tanggal_closed);
                  else:
                    $date_akhir =  new DateTime(date('Y-m-d H:i:s'));
                  endif;

                  $age_new=$date_awal->diff($date_akhir);  
                ?>
                <?php if($cn->follow_up == NULL || $cn->follow_up == ''):?>
                 <?php $cn->follow_up = 0;?>
                <?php endif;?>

                <?php if($cn->status_owner == 'New'):?>
                  <div id="list-newcase" title="New case">
                    <div>
                      <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to take ownership"><span class="label label-primary" onclick="getCase('<?=$cn->id?>')">Take Owner</span></div>
                        
                      <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_new->format("%H:%I")?></div>
                    </div>
                    <div style="padding-bottom:5px;">
                      <div><b><?=$cn->tiket?></b><span style="float:right;font-size:0.9em;font-weight:bold;">FU: <?=$cn->follow_up?></span></div>
                      <div style="float:left;text-align:left;clear:left;"><?=$cn->nama?></div>
                      <div style="font-size:0.8em;text-align:right;"><?=date('d/m/y H:i:s', strtotime($cn->tanggal_masuk))?></div>
                    </div>
                  </div>
                <?php elseif($cn->status_owner == 'TO' && $cn->login == $user->username):?>
                    <?php 
                      $user_ = $connection->createCommand("SELECT * FROM user WHERE username='$cn->login'")->queryOne();  
                    ?>
                    <div id="list-mine" title="TO by <?=$user_['nama_lengkap']?>" onclick="getSeecase('<?=$cn->id?>')" style="cursor:pointer;">
                        <div>
                          <div style="float:left;text-align:left;"><b><?=$cn->tiket?></b></div>
                          <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_new->format("%H:%I")?></div>
                        </div>
                        <div><?=$cn->nama?><span style="float:right;font-size:0.9em;font-weight:bold;">FU: <?=$cn->follow_up?></span></div>
                        <div style="font-size:0.7em;">
                          <span style="float:left;"><b><?=$cn->login?></b></span>
                          <span style="float:right;clear:right;"><?=date('d/m/y H:i:s', strtotime($cn->tanggal_masuk))?></span>
                          <div style="clear:left;"></div>
                        </div>
                    </div>
                <?php else:?>
                  <?php 
                    $user_ = $connection->createCommand("SELECT * FROM user WHERE username='$cn->login'")->queryOne();  
                  ?>
                  <div id="list-to" title="TO by <?=$user_['nama_lengkap']?>" style="cursor:pointer;">
                      <div>
                        <div style="float:left;text-align:left;"><b><?=$cn->tiket?></b></div>
                        <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_new->format("%H:%I")?></div>
                      </div>
                      <div><?=$cn->nama?><span style="float:right;font-size:0.9em;font-weight:bold;">FU: <?=$cn->follow_up?></span></div>
                      <div style="font-size:0.7em;">
                        <span style="float:left;"><b><?=$cn->login?></b></span>
                        <span style="float:right;clear:right;"><?=date('d/m/y H:i:s', strtotime($cn->tanggal_masuk))?></span>
                        <div style="clear:left;"></div>
                      </div>
                  </div>
                <?php endif;?>
            <?php endforeach;?>
        <?php else:?>
            <i>Data not found</i>
        <?php endif;?>
      </div>
    </div>
</div>
<script>
  function refreshServ()
  {
    $.ajax({
      url: 'temp-index',
      type: 'POST',
    })
  }
  function refreshCase(id)
  {
    $.ajax({
      url: 'get-refresh',
      type: 'POST',
      data: "id="+id
    });
  }
  function getSeecase(id)
  {
    $.ajax({
      url : 'get-seecase',
      type : 'POST',
      data : "id="+id,
      success : function (newData){
        $('#case-telegram').html(newData);
      }
    });
  }
  function getCase(id)
  {
    $.ajax({
      url : 'get-case',
      type : 'POST',
      data : "id="+id,
      success : function(data){
        $('#case-telegram').html(data);
        location.reload();
        // $.ajax({
        //   url : 'get-case',
        //   type : 'POST',
        //   data : "id="+id,
        //   success : function (newData){
        //   }
        // });
      }
    });
  }

  $(function () {
    $('[data-toggle="popover"]').popover({
      container: 'body',
      html: true,
    });
  });
</script>