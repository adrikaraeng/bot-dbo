<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Cases;
use app\models\Cases2;
use app\models\Kategori;
use app\models\SubKategori;
use app\models\Backend;
use app\models\ListProgressCases;
use app\models\UserSearch;
use app\models\DatesearchForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetImage()
    {
        if(!empty(isset($_POST['img']))){

            $data = $_POST['img'];
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            // $img = base64_decode($data['1']);

            file_put_contents(Yii::getAlias('@webroot/images/test-img.png'), $data);
            print_r('sukses');
        }else{
            print_r('gagal');
        }
    }
    public function actionDashboard()
    {
        $this->layout="dashboard";
        return $this->render('dashboard',[

        ]);
    }

    public function actionTempIndex()
    {
        return $this->redirect(['index']);
    }

    public function actionDownloadSource()
    {
      $connection = \Yii::$app->db;
        $model = new DatesearchForm;
        if($model->load(Yii::$app->request->post())):
          $mulai = $_POST['DatesearchForm']['tglMulai'];
          $akhir = $_POST['DatesearchForm']['tglAkhir'];
          $status = $_POST['DatesearchForm']['status'];

          if(empty($status)):
            $data = $connection->createCommand("SELECT * FROM cases AS a WHERE date(a.tanggal_masuk) >= '$mulai' AND date(a.tanggal_masuk) <= '$akhir' ORDER BY a.tanggal_masuk ASC")->queryAll();
          else:
            $data = $connection->createCommand("SELECT * FROM cases AS a WHERE date(a.tanggal_masuk) >= '$mulai' AND date(a.tanggal_masuk) <= '$akhir' AND status_owner='$status' ORDER BY a.tanggal_masuk ASC")->queryAll();
          endif;
          
          $filename = 'manohara-'.date($mulai).'__'.date($akhir).'.xls';
          
          header("Content-type: application/vnd-ms-excel");
          header("Content-Disposition: attachment; filename=".$filename);
          echo '<table width="100%">';
            echo '<thead>';
              echo '<tr>';
                echo '<th>Login</th>';
                echo '<th>Insert Date</th>';
                echo '<th>Nama Pelanggan</th>';
                echo '<th>Internet</th>';
                echo '<th>PSTN</th>';
                echo '<th>Handphone</th>';
                echo '<th>Email</th>';
                echo '<th>Status</th>';
                echo '<th>Age</th>';
                echo '<th>Tiket</th>';
                echo '<th>Versi App</th>';
                echo '<th>Sub Channel</th>';
                echo '<th>Kategori</th>';
                echo '<th>Sub Kategori</th>';
                echo '<th>Backend</th>';
                echo '<th>Remark</th>';
                echo '<th>Feedback</th>';
                echo '<th>Link evidence</th>';
                echo '<th>Closed Date</th>';
                echo '<th>Closed By</th>';
                echo '<th>Bot Tiket</th>';
                echo '<th>Created By</th>';
                echo '<th>Prener</th>';
                echo '<th>Email</th>';
                echo '<th>Source(bot)</th>';
                echo '<th>Source Email(bot)</th>';
                echo '<th>Services</th>';
                echo '<th>Regional</th>';
                echo '<th>Witel</th>';
              echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
              foreach($data as $d => $row):
                $login = $connection->createCommand("SELECT * FROM user WHERE username='$row[login]'")->queryOne();
                if($login){
                  $user = $login['nama_lengkap'];
                }else{
                  $user = "";
                }
                $sub_channel = $connection->createCommand("SELECT * FROM sub_channel WHERE id_sub_channel='$row[sub_channel]'")->queryOne();
                if($sub_channel){
                  $subchannel = $sub_channel['nama_sub_channel'];
                }else{
                  $subchannel = "";
                }
                $kategori = $connection->createCommand("SELECT * FROM kategori WHERE id='$row[kategori]'")->queryOne();
                if($kategori){
                  $kat = $kategori['nama_kategori'];
                }else{
                  $kat = "";
                }
                $sub_kategori = $connection->createCommand("SELECT * FROM sub_kategori WHERE id='$row[sub_kategori]'")->queryOne();
                if($sub_kategori){
                  $subkat = $sub_kategori['sub_kategori'];
                }else{
                  $subkat = "";
                }
                $backend = $connection->createCommand("SELECT * FROM backend WHERE id='$row[backend]'")->queryOne();
                if($backend){
                  $backend_ = $backend['nama_backend'];
                }else{
                  $backend_ = "";
                }
                $user_tele = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$row[telegram_id]'")->queryOne();
                if($row['status_owner'] != "Closed"):
                  $date1 = date_create($row['tanggal_masuk']);
                  $date2 = date_create(date('Y-m-d H:i:s'));
                else:
                  $date1 = date_create($row['tanggal_masuk']);
                  $date2 = date_create($row['tanggal_closed']);
                endif;
                $interval = date_diff($date1,$date2);
                $intv = $interval->format('%a');
                if($row['gambar']){
                  $evidence = Url::to('@web/images/'.$row['gambar'],true);
                }else{
                  $evidence = "";
                }
                $closedby = $connection->createCommand("SELECT * FROM user WHERE username='$row[closed_by]'")->queryOne();
                if($closedby){
                  $closed_by = $closedby['nama_lengkap'];
                }else{
                  $closed_by = "";
                }
                if($row['tanggal_closed'] == ''|| $row['tanggal_closed'] == NULL):
                  $closed_date = '';
                else:
                  $closed_date = date('d-m-Y H:i:s', strtotime($row['tanggal_closed']));
                endif;
              echo '<tr>';
                echo '<td>'.$user.'</td>';
                echo '<td>'.date('d-m-Y H:i:s',strtotime($row['tanggal_masuk'])).'</td>';
                echo '<td>'.$row['nama'].'</td>';
                echo '<td>'.$row['inet'].'</td>';
                echo '<td>'.$row['pstn'].'</td>';
                echo '<td>'.$row['hp'].'</td>';
                echo '<td>'.$row['email'].'</td>';
                echo '<td>'.$row['status_owner'].'</td>';
                echo '<td>'.$intv.'</td>';
                echo '<td>'.$row['no_tiket'].'</td>';
                echo '<td>'.$row['app_version'].'</td>';
                echo '<td>'.$subchannel.'</td>';
                echo '<td>'.$kat.'</td>';
                echo '<td>'.$subkat.'</td>';
                echo '<td>'.$backend_.'</td>';
                echo '<td>'.$row['keluhan'].'</td>';
                echo '<td>'.$row['feedback'].'</td>';
                echo '<td>'.$evidence.'</td>';
                echo '<td>'.$closed_date.'</td>';
                echo '<td>'.$closed_by.'</td>';
                echo '<td>'.$row['tiket'].'</td>';
                echo '<td>'.$user_tele['nama_lengkap'].'</td>';
                echo '<td>'.$user_tele['prener'].'</td>';
                echo '<td>'.$user_tele['email'].'</td>';
                echo '<td>'.$row['source'].'</td>';
                echo '<td>'.$row['source_email'].'</td>';
                echo '<td>'.$user_tele['layanan'].'</td>';
                echo '<td>'.$user_tele['regional'].'</td>';
                echo '<td>'.$user_tele['witel'].'</td>';
              echo '</tr>';
              endforeach;
            echo '</tbody>';
          echo '</table>';
          exit;
        endif;
        return $this->redirect(['index']);
    }
    
    public function actionRefresh_new($id)
    {
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        
        $user = User::findOne(Yii::$app->user->id);

        if($id == NULL || $id == ''):
            // if($user->divisi == "MONITORING"):
            //     $model0 = Cases::find()->where("status_owner='New' AND no_tiket IS NULL OR status_owner='New' AND no_tiket=''")->orderBy("rand()")->one();
            //     if($model0 != NULL):
            //         $model0->status_owner='TO';
            //         $model0->login=$user->username;
            //         $model0->save(false);
                    
            //         $connection->createCommand()->insert('list_progress_cases', [
            //             'cases' => $model0->id,
            //             'login' => $user->username,
            //             'status' => $model0->status_owner,
            //             'insert_date' => date('Y-m-d H:i:s')
            //         ])->execute();
            //     endif;
            // elseif($user->divisi == "SOLVER"):
            //     $model0 = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL OR status_owner='New' AND no_tiket<>''")->orderBy("rand()")->one();
            //     if($model0 != NULL):
            //         $model0->status_owner='TO';
            //         $model0->login=$user->username;
            //         $model0->save(false);
                    
            //         $connection->createCommand()->insert('list_progress_cases', [
            //             'cases' => $model0->id,
            //             'login' => $user->username,
            //             'status' => $model0->status_owner,
            //             'insert_date' => date('Y-m-d H:i:s')
            //         ])->execute();
            //     endif;
            // endif;
            $model0 = Cases::find()->where("status_owner='New' OR status_owner='New'")->orderBy("rand()")->one();
            if($model0 != NULL):
                $model0->status_owner='TO';
                $model0->login=$user->username;
                $model0->save(false);
                
                $connection->createCommand()->insert('list_progress_cases', [
                    'cases' => $model0->id,
                    'login' => $user->username,
                    'status' => $model0->status_owner,
                    'insert_date' => date('Y-m-d H:i:s')
                ])->execute();
            endif;
            $model = Cases::find()->where("status_owner='TO' AND login='$user->username'")->one();
        else:
            $model = Cases::find()->where("id='$id'")->one();
        endif;
        // if($user->divisi=="MONITORING"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket='' OR status_owner='TO' AND no_tiket IS NULL AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // elseif($user->divisi=="SOLVER"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // endif;
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $side = "A";
        $nonew = Cases::find()->where("status_owner='New' AND login='$user->username'")->orderBy("login ASC")->one();
        $report = new DatesearchForm;
        return $this->render('index', [
            'user' => $user,
            'case_new' => $case_new,
            'case_onprogress' => $case_onprogress,
            'model' => $model,
            'side' => $side,
            'report' => $report,
            'nonew' => $nonew
        ]);
    }
    public function actionGetRefresh()
    {
        $id=$_POST['id'];
        return $this->redirect(['refresh_new', 'id'=> $id]);
    }

    public function actionSimpanProgress($id)
    {
        $connection = \Yii::$app->db;
        $connection2 = \Yii::$app->db2;
        
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $model_a = Cases::findOne($id);

        $cek_rebeca = $connection2->createCommand("SELECT * FROM cases WHERE telebot_tiket='$model_a->tiket' AND `date`=date('$model_a->tanggal_masuk')")->queryOne();
        
        $model = new ListProgressCases;

        if($model->load(Yii::$app->request->post())):    
            $model->insert_date = date('Y-m-d H:i:s');

            $imageName = date('YmdHis').time();
            // if( strlen(trim(UploadedFile::getInstance($model,'feedback_gambar'))) > 0 ):
            //     $gambar = UploadedFile::getInstance($model,'feedback_gambar');
            //     if($gambar != NULL):
            //         $gambar->saveAs('images/'.$imageName.'.'.$gambar->extension);
            //         $model->feedback_gambar = $imageName.'.'.$gambar->extension;
            //         $model_a->feedback_gambar = $model->feedback_gambar;
            //     endif;
            // else:
            //   $model->feedback_gambar = "";
            //   $model_a->feedback_gambar = $model->feedback_gambar;
            // endif;
            
            if($_POST['img'] != 'kosong'){
                $data = $_POST['img'];
                $data = str_replace('data:image/png;base64,', '', $data);
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                // $img = base64_decode($data['1']);
    
                file_put_contents(Yii::getAlias('@webroot/images/'.$imageName.'.png'), $data);
                $model->feedback_gambar = $imageName.'.png';
                $model_a->feedback_gambar = $model->feedback_gambar;
                // print_r('image saved');
            }else{
                $model->feedback_gambar = NULL;
                $model_a->feedback_gambar = $model->feedback_gambar;
                // print_r('no image');
            }

            $model_a->feedback = $_POST['ListProgressCases']['feedback'];
            if($model->status == "Closed"):
                $status_r = "CLOSED";
                $model_a->closed_by = $user->username;
                $model_a->status_owner = "Closed";
                $model_a->tanggal_closed = $model->insert_date;

                if($cek_rebeca != NULL):
                    $date_awal = date_create($cek_rebeca['date']);
                    $date_closed = date_create(date('Y-m-d', strtotime($model->insert_date)));
                    $dateClose = date('Y-m-d', strtotime($model->insert_date));

                    $diff=date_diff($date_awal,$date_closed);
                    $age = $diff->format("%a");
                    $connection2->createCommand("UPDATE cases SET `status`='CLOSED', closed_date='$dateClose', closed_age='$age', closed_by='$user->username', feedback_error='$model_a->feedback' WHERE id_case='$cek_rebeca[id_case]'")->execute();
                endif;
            else:
                $status_r = "ON PROGRESS";
                $model_a->closed_by = NULL;
            endif;

            $model->cases = $model_a->id;
            $model->login = $user->username;
            $backend = Backend::findOne($model->backend);
            if($model->save(false)):

                $connection->createCommand()->insert('history_user', [
                    'user' => $user->id,
                    'cases' => $model_a->id,
                    'status' => $status_r,
                    'date_activity' => date('Y-m-d H:i:s')
                ])->execute();

                $model_a->save(false);

                if($cek_rebeca != NULL):
                    $tanggal = date('Y-m-d', strtotime($model->insert_date));
                    $waktu = date('H:i:s', strtotime($model->insert_date));

                    $connection2->createCommand("UPDATE cases SET  feedback_error='$model->feedback' WHERE id_case='$cek_rebeca[id_case]'")->execute();
                    $up_log_case = $connection2->createCommand()->insert('log_update_case', [
                        'id_case' => $cek_rebeca['id_case'],
                        'date' => "$tanggal",
                        'time' => "$waktu",
                        'login' => $user->username,
                        'remark_error' => "$model_a->keluhan",
                        'feedback_error' => "$model->feedback",
                        'status' => "$status_r",
                        'id_backend' => $model->backend,
                        'value_backend' => $backend->nama_backend
                        //'owner_group' => $model->owner_group
                    ])->execute();
                endif;
            endif;
        endif;

        return json_encode($model_a->id);
    }

    public function actionAjaxProgress($id)
    {
        $model = new ListProgressCases;
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())):
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        endif; 
    }

    public function actionGetOnprogress()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);

        $id = $_POST['id'];
        $model_a = Cases::findOne($id);
        $onProgress = ListProgressCases::find()->where("cases='$model_a->id'")->orderBy("id DESC")->all();
        $model = new ListProgressCases;
        
        // if($user->divisi=="MONITORING"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket='' OR status_owner='TO' AND no_tiket IS NULL AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // elseif($user->divisi=="SOLVER"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // endif;
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // $case_onprogress = Cases::find()->where("status_owner='On Progress'")->orderBy("DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $side = "B";
        return $this->renderAjax('on-progress',[
            'model' => $model,
            'model_a' => $model_a,
            'onProgress' => $onProgress,
            'user' => $user,
            'side' => $side,
            'case_new' => $case_new,
            'case_onprogress' => $case_onprogress
        ]);
    }

    public function actionSimpanCase($id)
    {
        $connection = \Yii::$app->db;
        $connection2 = \Yii::$app->db2;
        
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);

        $model = Cases::findOne($id);

        if($model->load(Yii::$app->request->post())):
            $category = $_POST['Cases']['kategori'];
            $subCategory = $_POST['Cases']['sub_kategori'];
            $backEnd = $_POST['Cases']['backend'];
            $feedback = $_POST['Cases']['feedback'];
            $statusOwner = $_POST['Cases']['status_owner'];

            if(empty($category) || empty($subCategory) || empty($backEnd) || empty($feedback) || empty($statusOwner)){
                Yii::$app->session->setFlash('error', "Periksa kembali form inputan!"); 
                return $this->redirect(['index']);
            }

            if($model->status_owner == "Closed" || $_POST['Cases']['status_owner'] == "Closed"):
                $model->tanggal_closed = date('Y-m-d H:i:s');
                $model->closed_by = $user->username;
                $date_awal = date_create(date('Y-m-d', strtotime($model->tanggal_masuk)));
                $date_closed = date_create(date('Y-m-d', strtotime($model->tanggal_closed)));
                $diff=date_diff($date_awal,$date_closed);
                $age = $diff->format("%a");
            else:
                $model->tanggal_closed = NULL;
                $age = NULL;
            endif;
            
            $imageName = date('YmdHis').time();
            // if( strlen(trim(UploadedFile::getInstance($model,'feedback_gambar'))) > 0 ):
            //     $gambar = UploadedFile::getInstance($model,'feedback_gambar');
            //     if($gambar != NULL):
            //         $gambar->saveAs('images/'.$imageName.'.'.$gambar->extension);
            //         $model->feedback_gambar = $imageName.'.'.$gambar->extension;
            //     endif;
            // endif;

            if($_POST['img'] != 'kosong'){
                $data = $_POST['img'];
                $data = str_replace('data:image/png;base64,', '', $data);
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                // $img = base64_decode($data['1']);
    
                file_put_contents(Yii::getAlias('@webroot/images/'.$imageName.'.png'), $data);
                $model->feedback_gambar = $imageName.'.png';
                print_r('image saved');
            }else{
                $model->feedback_gambar = NULL;
                print_r('no image');
            }

            $model->urgensi_status = "Normal";
            
            $model2 = new ListProgressCases;
            $model2->cases = $model->id;
            $model2->login = $user->username;
            $model2->feedback = $model->feedback;
            $model2->feedback_gambar = $model->feedback_gambar;
            $model2->status = $model->status_owner;
            $model2->insert_date = $model->tanggal_masuk;
            $model2->backend = $model->backend;
            $model2->save(false);

            if($model->save(false)):

              $tanggal = date('Y-m-d', strtotime($model->tanggal_masuk));
              $waktu = date('H:i:s', strtotime($model->tanggal_masuk));

                $cekCaserebeca = $connection2->createCommand("SELECT * FROM cases WHERE telebot_tiket='$model->tiket'")->queryOne();
                $model2 = Cases::findOne($id);

                if($model2->status_owner == "Closed"):
                    $closed_date = date('Y-m-d', strtotime($model->tanggal_closed));
                    $status_r = "CLOSED";
                    $closed_by = $user->username;
                else:
                    $closed_date = NULL;
                    $status_r = "ON PROGRESS";
                    $closed_by = NULL;
                endif;

                // $connection->createCommand()->insert('history_user', [
                //     'user' => $user->id,
                //     'cases' => $model->id,
                //     'status' => $status_r,
                //     'date_activity' => date('Y-m-d H:i:s')
                // ])->execute();

                if($cekCaserebeca == NULL):
                    $insert_rebeca = $connection2->createCommand()->insert('cases', [
                      'date' => "$tanggal",
                      'time' => "$waktu",
                      'login' => $user->username,
                      'nama_pelanggan' => "$model->nama",
                      'nomor_internet' => "$model->inet",
                      'nomor_pstn' => "$model->pstn",
                      'handphone' => "$model->hp",
                      'email' => "$model->email",
                      'nomor_tiket' => "$model->no_tiket",
                      'kode_error' => "0",
                      'id_channel' => "$model->channel",
                      'id_sub_channel' => "$model->sub_channel",
                      'id_kategori' => "$model->kategori",
                      'id_sub_kategori' => "$model->sub_kategori",
                      'id_backend' => "$model->backend",
                      'versi_apk' => "$model->app_version",
                      'status' => "$status_r",
                      'remark_error' => "$model->keluhan",
                      'feedback_error' => "$model->feedback",
                      'closed_date' => "$closed_date",
                      'closed_age' => "$age",
                      'status_urgency' => "Normal",
                      'closed_by' => "$closed_by",
                      'telebot_tiket' => "$model->tiket",
                      //'owner_group' => $model->owner_group
                    ])->execute();

                    $cekCaserebeca = $connection2->createCommand("SELECT * FROM cases WHERE telebot_tiket='$model->tiket'")->queryOne();
                    // $cek_case_rebeca = $connection2->createCommand("SELECT * FROM cases WHERE nomor_tiket='$model->no_tiket'")->queryOne();
                    $insert_rebeca = $connection2->createCommand()->insert('log_update_case', [
                      'id_case' => $cekCaserebeca['id_case'],
                      'date' => "$tanggal",
                      'time' => "$waktu",
                      'login' => $user->username,
                      'remark_error' => "$model->keluhan",
                      'feedback_error' => "$model->feedback",
                      'status' => "$status_r",
                      'id_backend' => $model->backend
                      //'owner_group' => $model->owner_group
                    ])->execute();
                else:
                  $connection2->createCommand("UPDATE cases SET `status`='$status_r', closed_date='$closed_date', closed_age='$age', closed_by='$closed_by' WHERE id_case='$cekCaserebeca[id_case]'")->execute();

                  $insert_rebeca = $connection2->createCommand()->insert('log_update_case', [
                    'id_case' => $cekCaserebeca['id_case'],
                    'date' => "$tanggal",
                    'time' => "$waktu",
                    'login' => $user->username,
                    'remark_error' => "$model->keluhan",
                    'feedback_error' => "$model->feedback",
                    'status' => "$status_r",
                    'id_backend' => $model->backend
                    //'owner_group' => $model->owner_group
                  ])->execute();
                endif;//endif_caserebeca_checked

                return $this->redirect(['index']);
            endif;//endif_saved
        endif;//endif_post
    }

    public function actionAjaxCeksimpan($id)
    {
        $model = Cases::findOne($id);
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())):
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        endif;       
    }

    public function actionGetSeecase()
    {
      if($_POST['id']):
          $id = $_POST['id'];
      else:
          $id = NULL;
      endif;
      $connection = \Yii::$app->db;
      if (Yii::$app->user->isGuest) {
          Yii::$app->user->logout();
          return $this->goHome();
      }
      $user = User::findOne(Yii::$app->user->id);
    //   if($user->divisi=="MONITORING"):
    //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket='' OR status_owner='TO' AND no_tiket IS NULL AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
    //   elseif($user->divisi=="SOLVER"):
    //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
    //   endif;
      $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
    //   $case_onprogress = Cases::find()->where("status_owner='On Progress'")->orderBy("DATE(tanggal_masuk) ASC")->all();
      $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

      $model = Cases::findOne($id);
      $last_update = ListProgressCases::find()->where("cases='$model->id'")->orderBy("id DESC")->all();
        
      $side = "A";

      $nonew = Cases::find()->where("status_owner<>'New' AND login='$user->username'")->orderBy("login ASC")->one();
      $report = new DatesearchForm;
      return $this->renderAjax('update-case',[
          'user' => $user,
          'case_new' => $case_new,
          'case_onprogress' => $case_onprogress,
          'model' => $model,
          'side' => $side,
          'last_update' => $last_update,
          'report' => $report,
          'nonew' => $nonew
      ]);
    }

    public function actionGetReloadReopencase()
    {
        if($_POST['id']):
            $id = $_POST['id'];
        else:
            $id = NULL;
        endif;
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $model = Cases::findOne($id);
        // $model->login=$user->username;
        if($model->status_owner == "TO" || $model->status_owner == "On Progress"):
            $model->status_owner = "New";
            $model->login = NULL;
        endif;
        
        if($model->save(false)):

            return $this->redirect(['index']);

        endif;
    }

    public function actionGetReopencase()
    {
        if($_POST['id']):
            $id = $_POST['id'];
        else:
            $id = NULL;
        endif;
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $model = Cases::findOne($id);
        // $model->login=$user->username;
        if($model->status_owner == "TO" || $model->status_owner == "On Progress"):
            $model->status_owner = "New";
            $model->login = NULL;
        endif;
        
        if($model->save(false)):
            
            $connection->createCommand()->insert('list_progress_cases', [
                'login' => $user->username,
                'cases' => $model->id,
                'status' => 'TOF',
                'insert_date' => date('Y-m-d H:i:s')
            ])->execute();

            return $this->redirect(['index-ch']);

        endif;
    }

    public function actionGetReloadCase()
    {
        if($_POST['id']):
            $id = $_POST['id'];
        else:
            $id = NULL;
        endif;
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $model = Cases::findOne($id);
        $last_update = ListProgressCases::find()->where("cases='$model->id'")->orderBy("id DESC")->all();
        $model->login=$user->username;
        if($model->status_owner == "New"):
            $model->status_owner = "TO";
        endif;
        
        if($model->save(false)):
            $model = Cases::findOne($id); 
            $side = "A";

            $nonew = Cases::find()->where("status_owner='New' AND login='$user->username' || status_owner='TO' AND login='$user->username'")->orderBy("login ASC")->one();
            $report = new DatesearchForm;

            return $this->renderAjax('update-case',[
                'user' => $user,
                'case_new' => $case_new,
                'case_onprogress' => $case_onprogress,
                'model' => $model,
                'side' => $side,
                'last_update' => $last_update,
                'report' => $report,
                'nonew' => $nonew
            ]);
        endif;
    }

    public function actionGetCase()
    {
        if($_POST['id']):
            $id = $_POST['id'];
        else:
            $id = NULL;
        endif;
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        // if($user->divisi=="MONITORING"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket='' OR status_owner='TO' AND no_tiket IS NULL AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // elseif($user->divisi=="SOLVER"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // endif;
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $model = Cases::findOne($id);
        $last_update = ListProgressCases::find()->where("cases='$model->id'")->orderBy("id DESC")->all();
        if($model->login != "" || $model->login != NULL):
            return $this->redirect(['index']);
        endif;
        $model->login=$user->username;
        if($model->status_owner == "New"):
            $model->status_owner = "TO";
        endif;
        
        if($model->save(false)):
            $model = Cases::findOne($model->id); 
            $side = "A";

            $connection->createCommand()->insert('list_progress_cases', [
                'login' => $user->username,
                'cases' => $model->id,
                'status' => 'TO',
                'insert_date' => date('Y-m-d H:i:s')
            ])->execute();

            $nonew = Cases::find()->where("status_owner='New' AND login='$user->username' || status_owner='TO' AND login='$user->username'")->orderBy("login ASC")->one();
            $report = new DatesearchForm;

            return $this->renderAjax('update-case',[
                'user' => $user,
                'case_new' => $case_new,
                'case_onprogress' => $case_onprogress,
                'model' => $model,
                'side' => $side,
                'last_update' => $last_update,
                'report' => $report,
                'nonew' => $nonew
            ]);
        endif;
    }

    public function actionGetSubkategori($id_kategori)
    {
        $models = SubKategori::find()->where("id_kategori='$id_kategori'")->all();
        if($models){
            foreach($models as $m => $model):
                echo "<option value='".$model->id."'>".$model->sub_kategori."</option>";
            endforeach;
        }else{
            echo "<option>-</option>";
        }
    }

    public function actionGetbackend()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            // print_r($parents[0]);
                if ($parents != null){
                    $cat_id = $parents[0];
                    $out = SubKategori::getBackendList($cat_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    // echo "";
                    return;
                }
        }
    }

    public function actionRefreshNewcase()
    {
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $case_new = Cases::find()->where("status='New' OR status='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // $case_onprogress = Cases::find()->where("status='On Progress'")->orderBy("DATE(tanggal_masuk) ASC")->all();
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();
        $model = Cases::find()->where("status='TO' AND login='$user->username'")->orderBy("rand()")->one();

        $side = "A";
        return $this->render('index', [
            'user' => $user,
            'case_new' => $case_new,
            'case_onprogress' => $case_onprogress,
            'model' => $model,
            'side' => $side,
        ]);

    }

    public function actionIndexCh()
    {
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        
        $user = User::findOne(Yii::$app->user->id);
        
        $model = Cases::find()->where("status_owner='TO' AND login='$user->username' || status_owner='On Progress' AND login='$user->username'")->orderBy("login ASC")->one();
        $case_new = Cases::find()->where("status_owner='New'  OR status_owner='TO' ")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // if($user->divisi=="MONITORING"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket='' OR status_owner='TO' AND no_tiket IS NULL AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // elseif($user->divisi=="SOLVER"):
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // endif;
        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();

        $side = "A";

        $nonew = Cases::find()->where("status_owner='New' AND login='$user->username' || status_owner='TO' AND login='$user->username'")->orderBy("login ASC")->one();
        $report = new DatesearchForm;
        return $this->render('index', [
            'user' => $user,
            'case_new' => $case_new,
            'case_onprogress' => $case_onprogress,
            'model' => $model,
            'side' => $side,
            'report' => $report,
            'nonew' => $nonew
        ]);
    }

    public function actionIndex()
    {
        $connection = \Yii::$app->db;
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);

        $model = Cases::find()->where("status_owner='TO' AND login='$user->username' || status_owner='On Progress' AND login='$user->username'")->all();
        if($model == NULL):
            // if($user->divisi=="MONITORING"):
            //     $model0 = Cases::find()->where("status_owner='New' AND no_tiket IS NULL AND no_tiket not like 'IN%'")->orderBy("rand()")->one();
            //     if($model0 != NULL):
            //         $model0->status_owner='TO';
            //         $model0->login=$user->username;
            //         $model0->save(false);
                    
            //         $connection->createCommand()->insert('list_progress_cases', [
            //             'cases' => $model0->id,
            //             'login' => $user->username,
            //             'status' => $model0->status_owner,
            //             'insert_date' => date('Y-m-d H:i:s')
            //         ])->execute();
            //     endif;
            // elseif($user->divisi=="SOLVER"):
            //     $model0 = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket like '%IN%'")->orderBy("rand()")->one();
            //     if($model0 != NULL):
            //         $model0->status_owner='TO';
            //         $model0->login=$user->username;
            //         $model0->save(false);
                    
            //         $connection->createCommand()->insert('list_progress_cases', [
            //             'cases' => $model0->id,
            //             'login' => $user->username,
            //             'status' => $model0->status_owner,
            //             'insert_date' => date('Y-m-d H:i:s')
            //         ])->execute();
            //     endif;
            // endif;
            $model0 = Cases::find()->where("status_owner='New'")->orderBy("rand()")->one();
            if($model0 != NULL):
                $model0->status_owner='TO';
                $model0->login=$user->username;
                $model0->save(false);
                
                $connection->createCommand()->insert('list_progress_cases', [
                    'cases' => $model0->id,
                    'login' => $user->username,
                    'status' => $model0->status_owner,
                    'insert_date' => date('Y-m-d H:i:s')
                ])->execute();
            endif;
        endif;
        // if($user->divisi=="MONITORING"):
        //     $model = Cases::find()->where("status_owner='TO' AND login='$user->username' AND no_tiket IS NULL AND no_tiket=''")->orderBy("login ASC")->one();
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NULL OR status_owner='TO' AND no_tiket IS NULL OR status_owner='New' AND no_tiket='' OR status_owner='TO' AND no_tiket=''")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // elseif($user->divisi=="SOLVER"):
        //     $model = Cases::find()->where("status_owner='TO' AND login='$user->username' AND no_tiket IS NOT NULL AND no_tiket<>''")->orderBy("login ASC")->one();
        //     $case_new = Cases::find()->where("status_owner='New' AND no_tiket IS NOT NULL AND no_tiket<>'' OR status_owner='TO' AND no_tiket<>'' AND no_tiket IS NOT NULL")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();
        // endif;
        $model = Cases::find()->where("status_owner='TO' AND login='$user->username' || status_owner='On Progress' AND login='$user->username'")->orderBy("login ASC")->one();
        $case_new = Cases::find()->where("status_owner='New' OR status_owner='TO' OR status_owner='New' OR status_owner='TO'")->orderBy("status_owner ASC, DATE(tanggal_masuk) ASC")->all();

        $case_onprogress = Cases::find()->where("status_owner='On Progress' AND login='$user->username'")->orderBy("DATE(tanggal_masuk) ASC")->all();
        $nonew = Cases::find()->where("status_owner='New' AND login='$user->username' || status_owner='TO' AND login='$user->username'")->orderBy("login ASC")->one();

        $side = "A";
        $report = new DatesearchForm;
        return $this->render('index', [
            'user' => $user,
            'case_new' => $case_new,
            'case_onprogress' => $case_onprogress,
            'model' => $model,
            'side' => $side,
            'report' => $report,
            'nonew' => $nonew
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
