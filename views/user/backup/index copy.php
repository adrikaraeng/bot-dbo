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
<style>
    #new-case{
        position:fixed;
        right:10px;
        border: 0.5px solid #80f20d;
        max-width: 160px;
        width: 100%;
        height:530px;
        top:110px;
        padding:5px;
    }
    #new-case .body-case{
        height: 490px;
        overflow:auto;
    }
    #list-newcase{
        background-color: #80f20d;
        font-size: 0.8em;
        padding:2px;
        padding-right:5px;
        padding-left:5px;
        margin-bottom:2px;
    }
    #list-to{
        background-color: #dfc971;
        font-size: 0.8em;
        padding:2px;
        padding-right:5px;
        padding-left:5px;
        margin-bottom:2px;
    }
    
    #onprogress-case{
        position:fixed;
        left:10px;
        border: 0.5px solid #ff8951;
        max-width: 160px;
        width: 100%;
        height:530px;
        top:110px;
        padding:5px;
    }
    #onprogress-case .body-case{
        height: 490px;
        overflow:auto;
    }
    #list-onprogress{
        background-color: #e41b20;
        color: #fff;
        font-size: 0.8em;
        padding:2px;
        padding-right:5px;
        padding-left:5px;
        margin-bottom:2px;
        cursor: pointer;
    }
    .row-cases-form{
      border: 0.5px solid #c3c3c3;
      border-radius:5px;
      padding:5px;
    }
    .popover{
      width:100%;
      max-width:520px;
    }
    #gbr-lg{
      max-width:500px;
    }
</style>

<div id="case-telegram">
  <div id="onprogress-case">
      <div class="head-case">On Progress Case</div>
      <div class="body-case">
          <?php if($case_onprogress != NULL):?>
              <?php foreach($case_onprogress as $c_onprog => $cop):?>
                  <div id="list-onprogress" onclick="getOnprogressCase('<?=$cop->id?>')">
                      <div><b><?=$cop->tiket?></b></div>
                      <div><?=$cop->nama?></div>
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
    $consum_daily_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND MONTH(tanggal_masuk)='$month'")->queryScalar();
    $consum_daily_non_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='On Progress'")->queryScalar();
    $consum_daily_to = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='TO'")->queryScalar();
  ?>

  <div style="position:fixed;left:20px;top:50px;">
    <span class="btn btn-success" style="font-size:0.7em;margin-right:5px;">
      <div>ODS</div>
      <div><?=$consum_daily_ods?></div>
    </span><span class="btn btn-danger" style="font-size:0.7em;margin-right:5px;">
      <div>OP</div>
      <div><?=$consum_daily_non_ods?></div>
    </span><span class="btn btn-warning" style="font-size:0.7em;">
      <div>TO</div>
      <div><?=$consum_daily_to?></div>
    </span>
  </div>

<?php if($model != NULL):?>
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
        <?php if($model->login == $user->username):?>
          <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to reopen the ticket" onclick="getReopen('<?=$model->id?>')"><span class="label label-danger">Take Off</span></div>
        <?php elseif($model->login == NULL && $model->status_owner == "New"):?>
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
            
            <?= $form->field($model, 'sub_channel')->widget(Select2::classname(), [
                  'data' => $subchannel,
                  'options'=>[
                    'id' => "sub-channel-search",
                    'placeholder' => Yii::t('app','Select Sub-channel')
                  ],
                  'pluginOptions' => [
                      'allowClear' => true,
                  ],
              ]);?>
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
  </div>
  <?php else:?>
    <div style="color:#c40000;font-weight:bold;">No results found !</div>
  <?php endif;?>

  <div id="new-case">
    <div class="head-case">List New Case</div>
    <div class="body-case">
        <?php if($case_new != NULL):?>
            <?php foreach($case_new as $c_new => $cn):?>
                <?php if($cn->status_owner == 'New'):?>
                    <div id="list-newcase" title="New case" onclick="getSeecase('<?=$cn->id?>')" style="cursor:pointer;">
                        <div id="to-case" style="right:10px;cursor:pointer;" title="Clik to take ownership"><span class="label label-primary" onclick="getCase('<?=$cn->id?>')">Take Owner</span></div>
                        <div><b><?=$cn->tiket?></b></div>
                        <div><?=$cn->nama?></div>
                        <div style="font-size:0.6em;text-align:right;"><?=date('d/m/y H:i:s', strtotime($cn->tanggal_masuk))?></div>
                    </div>
                <?php elseif($cn->status_owner == 'TO'):?>
                    <?php 
                      $user_ = $connection->createCommand("SELECT * FROM user WHERE username='$cn->login'")->queryOne();  
                    ?>
                    <div id="list-to" title="TO by <?=$user_['nama_lengkap']?>" onclick="getSeecase('<?=$cn->id?>')" style="cursor:pointer;">
                      <?php if($user->username == $cn->login):?>
                        <div id="to-case" style="right:10px;cursor:pointer;" title="Clik to reopen the ticket"><span class="label label-danger" onclick="getReopen('<?=$cn->id?>')">Take Off</span></div>
                      <?php endif;?>
                        <div><b><?=$cn->tiket?></b></div>
                        <div><?=$cn->nama?></div>
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
  function getOnprogressCase(id)
  {
    $.ajax({
      url : 'get-onprogress',
      type : 'POST',
      data : "id="+id,
      success : function (newData){
        $('#case-telegram').html(newData);
      }
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
  function getReopen(id)
  {
    // alert(id);
    if(confirm('Yakin untuk re open tiket ini?')){
      $.ajax({
        url : 'get-reopencase',
        type : 'POST',
        data : "id="+id,
        success : function(data){
          $.ajax({
            url : 'get-reopencase',
            type : 'POST',
            data : "id="+id,
            success : function (newData){
              $('#case-telegram').html(newData);
            }
          });
        }
      });
    }
  }
  function getCase(id)
  {
    // alert(id);
    $.ajax({
      url : 'get-case',
      type : 'POST',
      data : "id="+id,
      success : function(data){
        $.ajax({
          url : 'get-case',
          type : 'POST',
          data : "id="+id,
          success : function (newData){
            $('#case-telegram').html(newData);
          }
        });
      }
    });
  }
</script>