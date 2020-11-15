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

$this->title = Yii::t('app', 'Cases on progress');
$this->params['breadcrumbs'][] = $this->title;

?>

<div id="case-telegram">

<style>
    #gbr-lg{
      max-width:200px;
    }
  </style>

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
    $connection = \Yii::$app->db;
    $backend = ArrayHelper::map(Backend::find()->where("id_sub_kategori='$model_a->sub_kategori'")->all(),'id','nama_backend');?>

  <div class="user-index">
    <b><?=$user->nama_lengkap?></b>
    <?php
      $month = date('m');
      $consum_daily_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND MONTH(tanggal_masuk)='$month'")->queryScalar();
      $consum_daily_non_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='On Progress'")->queryScalar();
      $consum_daily_to = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='TO'")->queryScalar();
      $source = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$model_a->telegram_id'")->queryOne();
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
    <div class="row-cases-form">

      <?php $form = ActiveForm::begin([
          'id'=>'form-caseop',
          'action' => Url::to(['simpan-progress','id'=>$model_a->id]),
          'enableAjaxValidation' => true,
          'enableClientValidation' => true,
          'options' => ['enctype' => 'multipart/form-data'],
          'validationUrl' => Url::toRoute(['/user/ajax-progress','id'=>$model_a->id]),
      ]); ?>
      
      <?php if($model_a->login == $user->username && $model_a->status_owner != "Closed"):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to reopen the ticket" onclick="getReopen('<?=$model_a->id?>')"><span class="label label-danger">Take Off</span></div>
      <?php elseif($model_a->login == NULL && $model_a->status_owner == "New"):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to take ownership" onclick="getCase('<?=$model_a->id?>')"><span class="label label-primary">Take Owner</span></div>
      <?php elseif($model_a->login != $user->username):?>
        <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Progressed by <?=$model_a->login?>"><span class="label label-success"><?=$model_a->login?></span></div>
      <?php endif;?>
      
      <div style="text-align:right;margin-right:30px;"><b>Tiket : <?=$model_a->tiket?></b></div>
      <div style="text-align:right;margin-right:30px;font-size:0.7em;font-style:italic;"><?=date('d-m-Y H:i:s', strtotime($model_a->tanggal_masuk))?></div>
      <div style="clear:left;"><span style="font-weight:bold;">Nama Pelanggan :</span><?=$model_a->nama?></div>
      <div style="clear:left;"><span style="font-weight:bold;">Keluhan :</span><?=$model_a->keluhan?></div>
      <div style="clear:left;"><span style="font-weight:bold;">Tiket/Order :</span><?=$model_a->no_tiket?></div>
      <div style="clear:left;"><span style="font-weight:bold;">Source :</span><?=$source['layanan']."/".$source['nama_lengkap']?></div>


      <br>
        <div class="row">
          <?php if($model_a->status_owner != "Closed"):?>
          <div class="col-lg-4">
            <?= $form->field($model, 'feedback')->textarea(['rows' => 4,'id'=>'feedback','placeholder'=>'Feedback/ Proses penanganan', 'style'=>'resize:none;'])->label(false) ?>
          </div>
          <div class="col-lg-2">
              <?= $form->field($model, 'backend')->widget(Select2::classname(), [
                  'data' => $backend,
                  'options'=>['placeholder'=>Yii::t('app','Select Backend'), 'id' => "backend-search"],
                  'pluginOptions' => [
                      'allowClear' => true,
                  ],
              ]);?>
              <?= $form->field($model, 'status')->radioList(['Closed'=>'Closed','On Progress'=>'On Progress'],['style'=>"clear:left;",'id'=>'status-progress'])->label(); ?>
              <?php if($model_a->login == $user->username):?>
                  <div class="form-group">
                  <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                  </div>
              <?php endif;?>
          </div>
          <div class="col-lg-2">
            <div style="margin-top:-10px;"><b>Feedback Gambar</b></div>
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
          </div>
          
          <?php endif;?>
          <div class="col-lg-4">
          
            <?php if($model_a->gambar != NULL):?>
              <div style="margin-top:-10px;"><b>Bukti Gambar</b></div>
                <div id="gambar-search">
                    <?=Html::img("@web/images/".$model_a->gambar, [
                      'id' => "gbr-sml",
                      'style' => " width:100%;max-width:250px;height:100%;border:0.5px solid #000;border-radius:5px;cursor:pointer;max-height:250px;",
                      // 'title'=>'Bukti Gambar',
                      'tabindex' => "0",
                      'data-pjax' => '0',
                      'data-trigger' => "focus",
                      'data-html' => "true",
                      'data-toggle' => 'popover',
                      'data-placement' => 'left',
                      'aria-describedby'=>"popover-gbr-lg",
                      'data-content' => $this->render('@app/views/user/gambar-large',[
                        'gambar' => $model_a->gambar
                      ])
                    ])?>
              <?php endif;?>
          
          </div>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>


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

<script>
  
$('#form-caseop').on('submit',function(e){
  var formData = new FormData(this);
  var formUrl = $('#form-caseop').attr('action');
  // var idc = $('#id-caseon').text();

  e.preventDefault();

  $.ajax({
    type: 'POST',
    url: formUrl,
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    dataType: 'json',
    success: function(idc) {
      $.ajax({
        url: 'get-onprogress',
        type : 'POST',
        data : 'id='+idc,
        success : function (newData){
          $('#case-telegram').html(newData);
        }
      });
    }
  });
  e.unbind();
  return false;
});
</script>

  <style>
    #tb-onprogress {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 0.84em;
    }

    #tb-onprogress td, #tb-onprogress th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #tb-onprogress tr:nth-child(even){background-color: #f2f2f2;}

    #tb-onprogress tr:hover {background-color: #ddd;}

    #tb-onprogress th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #4CAF50;
      color: white;
    }
    .input-container{
      display: flex;
      width: 100%;
      margin-bottom: 15px;
    }
    .icon {
      padding: 10px;
      background: dodgerblue;
      color: white;
      min-width: 50px;
      text-align: center;
    }
    .input-field:focus {
      border: 2px solid dodgerblue;
    }
    .popover{
      width:100%;
      max-width:520px;
    }
    #gbr-lg{
      max-width:500px;
    }
  </style>
  <br>
  <div class="row">
    <div style="font-weight:bold;">Detail Progress :</div>
    <table id="tb-onprogress">
        <tr style="background-color:#01fe9f;">
            <th style="padding-left:5px;text-align:right;width:50px;">#</th>
            <th style="text-align:center;width:150px;">Update at</th>
            <th style="text-align:center;">Feedback</th>
            <th style="text-align:center;">Backend</th>
            <th style="text-align:center;">Gambar</th>
        </tr>
        <?php $no=1;foreach($onProgress as $on_progress => $onp):?>
        <?php
            if($onp->backend != NULL):
                $cek_backend = $connection->createCommand("SELECT * FROM backend WHERE id='$onp->backend'")->queryOne();
                $cek_backend = $cek_backend['nama_backend'];
            else:
                $cek_backend = "-";
            endif;

            if($onp->feedback_gambar != NULL):
                $gambar = Html::img("@web/images/".$onp->feedback_gambar, [
                  'id' => "gbr-sml",
                  'style' => " width:100%;max-width:50px;height:100%;border:0.5px solid #000;border-radius:5px;cursor:pointer;max-height:50px;",
                  // 'title'=>'Bukti Gambar',
                  'tabindex' => "0",
                  'data-pjax' => '0',
                  'data-trigger' => "focus",
                  'data-html' => "true",
                  'data-toggle' => 'popover',
                  'data-placement' => 'left',
                  'aria-describedby'=>"popover-gbr-lg",
                  'data-content' => $this->render('@app/views/user/gambar-large',[
                    'gambar' => $onp->feedback_gambar
                  ])
                ]);
            else:
                $gambar = "-";
            endif;
        ?>
        <tr>
            <td style="text-align:center;text-align:right;width:50px;"><?=$no++?></td>
            <td style="text-align:center;width:150px;"><?=date('d-m-Y H:i:s', strtotime($onp->insert_date))?></td>
            <td style="text-align:center;"><?=$onp->feedback?></td>
            <td style="text-align:center;"><?=$cek_backend?></td>
            <td style="text-align:center;"><?=$gambar?></td>
        </tr>
        <?php endforeach;?>
    </table>
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
  function getCase(id)
  {
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

  $(function () {
    $('[data-toggle="popover"]').popover({
      container: 'body',
      html: true,
    });
  });
</script>