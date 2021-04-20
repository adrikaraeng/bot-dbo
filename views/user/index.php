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
use app\models\ListProgressCases;
use app\models\AppVersion;

use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Cases');
$this->params['breadcrumbs'][] = $this->title;

$connection = \Yii::$app->db;
$connection2 = \Yii::$app->db2;

?>
<style>
  #new-case{
    /* position:fixed; */
    right:10px;
    border: 0.5px solid #80f20d;
    /* max-width: 160px; */
    width: 100%;
    height:530px;
    margin-top:60px;
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
  #list-mine{
    background-color: #9cdef8;
    font-size: 0.8em;
    padding:2px;
    padding-right:5px;
    padding-left:5px;
    margin-bottom:2px;
  }
  
  #onprogress-case{
    /* position:fixed; */
    left:10px;
    border: 0.5px solid #ff8951;
    /* max-width: 160px; */
    width: 100%;
    height:530px;
    margin-top:60px;
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
    margin-bottom: 5px;
  }
  .popover{
    width:100%;
    max-width:520px;
  }
  #gbr-lg{
    max-width:500px;
  }
  
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
  #upload {
    border:4px dotted black;
    width:100%;
    height:auto;
    min-height:250px;
    max-height:350px;
    text-align:center;
    overflow:auto;
    display:flex;
    align-items: center;
    justify-content: center;
    vertical-align: middle;
  }

  #upload:before {
    content: attr(placeholder);
    color: #aaaaaa;
    font-size: 0.8em;
    display: block;
    position:absolute;    
    font-family: "Campton", sans-serif;
  }
</style>

<div id="case-telegram">
  <?php
    $month = date('m');
    $consum_daily_ods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND MONTH(tanggal_masuk)='$month' AND DATE(tanggal_closed)=DATE(tanggal_masuk)")->queryScalar();
    $consum_daily_nods = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='Closed' AND DATE(tanggal_closed)<>DATE(tanggal_masuk) AND MONTH(tanggal_masuk)='$month'")->queryScalar();
    $consum_daily_op = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='On Progress'")->queryScalar();
    $consum_daily_to = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE login='$user->username' AND status_owner='TO'")->queryScalar();
    // if($user->divisi=="SOLVER"):
    //   $consum_new = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>''")->queryScalar();
    // elseif($user->divisi=="MONITORING"):
    //   $consum_new = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE status_owner='New' AND no_tiket IS NULL AND no_tiket=''")->queryScalar();
    // endif;

    $consum_new = $connection->createCommand("SELECT COUNT(*) FROM cases WHERE status_owner='New'")->queryScalar();
  ?>

  <div style="position:fixed;left:5px;top:70px;z-index:9999;">
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
  <div style="top:70px;position:fixed;right:30px;z-index:9999;">
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
    $dbackend = ArrayHelper::map(Backend::find()->where("id_sub_kategori='$model->sub_kategori'")->all(),'id','nama_backend');
    $subchannel = ArrayHelper::map(SubChannel::find()->where("id_channel='$model->channel'")->all(),'id','nama_sub_channel');
    $urgensi = ArrayHelper::map(UrgensiStatus::find()->all(),'urgensi_status','urgensi_status');
    $app = ArrayHelper::map(AppVersion::find()->orderBy("id DESC")->all(),'version','version');
    $source = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$model->telegram_id'")->queryOne();
  ?>

  <div class="user-index">
    <div style="position:fixed;z-index:9999;left:10px;top:50px;">
      <b class="label label-primary">Hi, <?=$user->nama_lengkap?></b>
    </div>
    <div class="row">
      <!-- On Progress List -->
      <div class="col-md-2">

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
            <!-- End <body case> -->
        </div>
        <!-- End on progress -->

      </div>
      <!-- End On Progress List -->
      <div class="col-md-8">
        <div class="col-md-12">
          <div class="row-cases-form">
            <div style="">
              <p style="font-weight:bold;font-size:0.8em;">Export to file</p>
              <?php $form2 = ActiveForm::begin([
                'id'=>'date-range',
                'action' => ['download-source'],
                // 'enableAjaxValidation' => true,
                // 'validationUrl' => Url::toRoute(['/admin/ajax-validation-barang']),
              ]); ?> 
              <div class="col-md-3">
                  <?= $form2->field($report, 'status')->dropDownList([
                      'On Progress' => 'ON PROGRESS',
                      'TO' => 'TO',
                      'New' => 'NEW TICKET',
                      'Closed' => 'CLOSED'
                  ],['prompt' => 'ALL STATUS'])->label(false); ?>
              </div>
              <div class="col-md-3">
                <?= $form2->field($report, 'tglMulai')->widget(
                    DatePicker::className(), [
                        //'value' => date('Y-m-d'),
                        'options' => ['placeholder' => '*) Tanggal Awal'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ]
                )->label(false)?>
              </div>
              <div class="col-md-3">
                <?= $form2->field($report, 'tglAkhir')->widget(
                    DatePicker::className(), [
                        //'value' => date('Y-m-d'),
                        'options' => ['placeholder' => '*) Tanggal Akhir'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ]
                )->label(false)?>
              </div>
              <div class="col-md-3">
                <div class="form-group" style="text-align:center;padding-left:20px;">
                  <?= Html::submitButton(Yii::t('app', 'Download'), ['class' => 'btn btn-primary']) ?>
                </div>
              </div>

              <?php ActiveForm::end(); ?>
              <div style="clear:left;"></div>
            </div>
          </div>
        </div>
        
        <?php if($nonew):?>
        <div class="col-md-12">
          <div class="row-cases-form" style="font-size:0.8em;">
            <?php $form = ActiveForm::begin([
                'id'=>'form-case',
                'action' => ['user/simpan-case', 'id'=>$model->id],
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'options' => ['enctype' => 'multipart/form-data'],
                'validationUrl' => Url::toRoute(['/user/ajax-ceksimpan', 'id'=>$model->id]),
            ]); ?>
            <p style="font-weight:bold;">Case Form</p>
 
              <?php if($model->login == $user->username):?>
                <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to reopen the ticket" onclick="getReopen('<?=$model->id?>')"><span id="take-off" class="label label-danger">Take Off</span></div>
              <?php elseif($model->login == NULL && $model->status_owner == "New"):?>
                <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Clik to take ownership" onclick="getCase('<?=$model->id?>')"><span class="label label-primary">Take Owner</span></div>
              <?php elseif($model->login != $user->username):?>
                <div id="to-case" style="right:10px;cursor:pointer;float:left;" title="Progressed by <?=$model->login?>"><span class="label label-success"><?=$model->login?></span></div>
              <?php endif;?>
              <div style="text-align:right;margin-right:30px;"><b>Tiket : <?=$model->tiket?></b></div>
              <div style="text-align:right;margin-right:30px;font-size:0.7em;font-style:italic;"><?=date('d-m-Y H:i:s', strtotime($model->tanggal_masuk))?></div>
              <div style="clear:left;"><span style="font-weight:bold;">Source :</span><?=$source['layanan']."/".$source['nama_lengkap']?></div>
              <hr>
              <div class="row">
                <div class="col-lg-3">
                  <?= $form->field($model, 'nama')->textInput(['maxlength' => 50, 'readonly'=>true]) ?>

                  <div class="row">
                    <div class="col-lg-6">
                      <?= $form->field($model, 'inet')->textInput(['maxlength' => 50, 'style'=>"font-size:0.9em;"]) ?>
                    </div>
                    <div class="col-lg-6">
                      <?= $form->field($model, 'pstn')->textInput(['maxlength' => 50, 'style'=>"font-size:0.9em;"]) ?>
                    </div>
                  </div>

                  <?= $form->field($model, 'hp')->textInput(['maxlength' => 50, 'readOnly'=>true]) ?>

                  <?= $form->field($model, 'email')->textInput(['maxlength' => 50]) ?>
                  
                  <?php if($model->gambar != NULL):?>
                    <div id="name-file"><b>Evidence</b></div>
                    <div id="gambar-search">
                        <?=Html::img("@web/images/".$model->gambar, [
                          'id' => "gbr-sml",
                          'style' => " width:100%;max-width:100px;height:100%;border:0.5px solid #000;border-radius:5px;cursor:pointer;max-height:100px;",
                          // 'title'=>'Bukti Gambar',
                          'tabindex' => "0",
                          'data-pjax' => '0',
                          'data-trigger' => "focus",
                          'data-html' => "true",
                          'data-toggle' => 'popover',
                          'data-placement' => 'right',
                          'aria-describedby'=>"popover-gbr-lg",
                          'data-content' => $this->render('@app/views/user/gambar-large',[
                            'gambar' => $model->gambar
                          ])
                        ])?>
                    </div>
                  <?php endif;?>

                </div>
                <div class=col-lg-2>
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
                        'options'=>[
                          'placeholder'=>Yii::t('app','Select Sub-Kategori'), 'id' => "sub-kategori-search"
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);?>

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
                            'id' => "app-search-version",
                            'placeholder' => Yii::t('app','Select App Version')
                          ],
                          'pluginOptions' => [
                              'allowClear' => true,
                          ],
                      ]);?>
                </div>
                <div class="col-lg-3">
                    
                  <?= $form->field($model, 'keluhan')->textarea(['rows' => 4,'id'=>'keluhan','placeholder'=>'Remark / Keluhan', 'style'=>'resize:none;','readonly'=>true]) ?>

                  <?= $form->field($model, 'feedback')->textarea(['rows' => 4,'id'=>'feedback','placeholder'=>'Feedback/ Proses penanganan', 'style'=>'resize:none;'])->label() ?>

                  <?= $form->field($model, 'status_owner')->radioList(['Closed'=>'Closed','On Progress'=>'On Progress'],['style'=>"clear:left;",'id'=>'status-progress'])->label(); ?>

                  <?php if($model->status_owner == "TO" && $model->login==$user->username || $model->status_owner == "On Progress" && $model->login==$user->username):?>
                  <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'style' => "width:100%;"]) ?>
                  </div>
                  <?php endif;?>
                </div>
                <div class="col-lg-4">
                  <label for="feedback_gambar">Feedback Gambar</label>
                  <div id="upload" placeholder="Paste an image" contenteditable>
                  </div>
                </div>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
        <?php else:?>
          <div class="col-md-12">
            <div class="row-cases-form" style="font-size:0.8em;">
              <div style="color:#c40000;font-weight:bold;">No results found !</div>
            </div>
          </div>
        <?php endif;?>
      </div>
      <!-- New List -->
      <div class="col-md-2">
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
                          <div id="list-newcase" title="New case" style="cursor:pointer;">
                            <div>
                              <div id="to-case" title="Clik to take ownership"><span class="label label-primary take-owner" onclick="getCase('<?=$cn->id?>')">Take Owner</span></div>
                                
                              <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_new->format("%H:%I")?></div>
                            </div>
                            <div style="padding-bottom:5px;clear:left;">
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
      <!-- End new list -->
    </div>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
      <div id="error-notice" style="position:absolute;">
        <div class="alert alert-danger alert-dismissable">
          <?= Yii::$app->session->getFlash('error') ?>
        </div>
      </div>
      <script>
        $(document).ready(function(){
          // $("#error-notice").show();
          setTimeout(function() { 
            $("#error-notice").hide(); 
          }, 3000);
        });
      </script>
    <?php endif; ?>
  </div>
  <?php if($nonew):?>
  <div class="col-md-12">

    <div id="on-progress-case">
      <div class="row">
        <div style="font-weight:bold;">Detail Progress :</div>
        <table id="tb-onprogress">
            <tr style="background-color:#01fe9f;">
                <th style="padding-left:5px;text-align:right;width:50px;">#</th>
                <th style="text-align:center;width:150px;">Update at</th>
                <th style="text-align:center;width:150px;">Login</th>
                <th style="text-align:center;">Feedback</th>
                <th style="text-align:center;">Backend</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;">Gambar</th>
            </tr>
            <?php $no=1;
                  $last_update = ListProgressCases::find()->where("cases='$model->id'")->orderBy("id DESC")->all();
              foreach($last_update as $on_progress => $onp):?>
            <?php
                if($onp->backend != NULL):
                    $cek_backend = $connection->createCommand("SELECT * FROM backend WHERE id='$onp->backend'")->queryOne();
                    $cek_backend = $cek_backend['nama_backend'];
                else:
                    $cek_backend = "-";
                endif;
                $user_update=$connection->createCommand("SELECT * FROM user WHERE username='$onp->login'")->queryOne();

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
                <td style="text-align:center;"><?=$user_update['nama_lengkap']." [".$onp->login."]"?></td>
                <td style="text-align:center;"><?=$onp->feedback?></td>
                <td style="text-align:center;"><?=$cek_backend?></td>
                <td style="text-align:center;"><?=$onp->status?></td>
                <td style="text-align:center;"><?=$gambar?></td>
            </tr>
            <?php endforeach;?>
        </table>
      </div>
    </div>

  </div>
  <?php endif;?>

  <?php else:?>
  
    <div class="row">
      <div class="col-md-12">
        <div style="position:fixed;z-index:9999;left:10px;top:50px;">
          <b class="label label-primary">Hi, <?=$user->nama_lengkap?></b>
        </div>
        <div class="col-md-2"></div>
        
        <div class="col-md-8">
          <div class="row-cases-form">
            <p style="font-weight:bold;font-size:0.8em;">Export to file</p>
            <?php $form2 = ActiveForm::begin([
              'id'=>'date-range',
              'action' => ['download-source'],
              // 'enableAjaxValidation' => true,
              // 'validationUrl' => Url::toRoute(['/admin/ajax-validation-barang']),
            ]); ?> 

              <div class="col-md-3">
                <?= $form2->field($report, 'status')->dropDownList([
                    'On Progress' => 'ON PROGRESS',
                    'TO' => 'TO',
                    'New' => 'NEW TICKET',
                    'Closed' => 'CLOSED'
                ],['prompt' => '-Status-'])->label(false); ?>
              </div>
              
              <div class="col-md-3">
                <?= $form2->field($report, 'tglMulai')->widget(
                    DatePicker::className(), [
                        //'value' => date('Y-m-d'),
                        'options' => ['placeholder' => '*) Tanggal Awal'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ]
                )->label(false)?>
              </div>
              <div class="col-md-3">
                <?= $form2->field($report, 'tglAkhir')->widget(
                    DatePicker::className(), [
                        //'value' => date('Y-m-d'),
                        'options' => ['placeholder' => '*) Tanggal Akhir'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ]
                )->label(false)?>
              </div>
              <div class="col-md-3">
                <div class="form-group" style="text-align:center;padding-left:20px;">
                  <?= Html::submitButton(Yii::t('app', 'Download'), ['class' => 'btn btn-primary']) ?>
                </div>
              </div>

            <?php ActiveForm::end(); ?>
            <div style="clear:left;"></div>
          </div>
        </div>
        <!-- End col-md-8 -->
        <div class="col-md-2"></div>
      </div>
    </div>
    <div style="top:70px;position:fixed;right:30px;z-index:9999;">
      <span class="btn btn-success" style="padding-left:5px;padding-right:5px;font-size:0.7em;min-width:40px;float:left;height:40px;padding-top:12px;" title="Klik Untuk Cek New Case" onclick="refreshServ()">
        <div style="font-weight:bold;"><span class="fa fa-refresh"></span> Refresh</div>
      </span>
      <span style="float:left;padding-left:6px;">
        <div style="font-size:0.8em;height:40px;padding-top:12px;">New : <b><?=$consum_new?></b></div>
      </span>
    </div>
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
      <div class="row-cases-form">
        <div style="color:#c40000;font-weight:bold;">No results found !</div>
      </div>
    </div>
      <!-- New List -->
      <div class="col-md-2">
        <div id="new-case" style="margin-top:-40px;">

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
                          <div id="list-newcase" title="New case" style="cursor:pointer;">
                            <div>
                              <div id="to-case" title="Clik to take ownership"><span class="label label-primary take-owner" onclick="getCase('<?=$cn->id?>')">Take Owner</span></div>
                                
                              <div style="text-align:right;" title="Usia tiket (Jam:Menit)"><?=$age_new->format("%H:%I")?></div>
                            </div>
                            <div style="padding-bottom:5px;clear:left;">
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
      <!-- End new list -->
  <?php endif;?>
  
</div>

<?php
  $this->registerJS("
    $('#upload').keydown(function (event) {
      if (event.ctrlKey || event.keyCode == 8) {
        return true;
      }
      if (33 <= event.keyCode && event.keyCode <= 40) {
        return true;
      }
      return false;
    });

    $('form#form-case').on('beforeSubmit',function (e) {
      e.preventDefault();
      var form = $(this);
      var formData = form.serialize();
      var img_data = $('#upload img').attr('src');

      if(img_data == null){
        console.log('kosong');
        img_data = 'kosong';
      }else{
        console.log('ada');
      }
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'post',
        processData : false,
        cache: false,
        data: formData+'&img='+img_data,
        success: function(res){
          console.log('success');
        }
      });
      return false;
    });
  ");
?>

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
        success : function(){
          location.reload(); 
          $.ajax({
            url : 'get-reload-reopencase',
            type : 'POST',
            data : "id="+id,
            success : function (data){
              $('#case-telegram').html(data);
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
      processData : false,
      cache: false,
      success : function(){
        $.ajax({
          url : 'get-reload-case',
          type : 'POST',
          data : "id="+id,
          processData : false,
          cache: false,
          success : function (data){
            $('#case-telegram').html(data);
          }
        });
      }
    });
  }
</script>