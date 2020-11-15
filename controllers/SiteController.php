<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Cases;
use app\models\TempCases;
use app\models\ListProgressCases;
use app\models\Cases2;
use mirkhamidov\telegramBot\TelegramBot;
use  yii\web\Session;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionGet_source()
    {
        $connection = \Yii::$app->db;
        $token = 'bot1248348390:AAGXMfWmHAzfKoEEihR1VGu_036LTSwRHnc';
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        
        $pesan = $data['message']['text'];
        $chat_id = $data['message']['chat']['id'];

        return $this->render('get_source',[
            'connection' => $connection,
            'data' => $data,
            'chat_id' => $chat_id,
            'pesan' => $pesan,
            'token' => $token
        ]);
    }
    public function actionCreate_ticket()
    {
        Yii::$app->telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'testing abcd',
        ]);
    }
    public function actionIndex()
    {
      $this->layout = "main-start";

      $connection = \Yii::$app->db;
      $data = Yii::$app->telegram->hook();
      
      $cek_email = $connection->createCommand("SELECT * FROM temp_email")->queryOne();
      $cek_source = $connection->createCommand("SELECT * FROM temp_source")->queryOne();
      $cek_kategori = $connection->createCommand("SELECT * FROM temp_kategori")->queryOne();
      $cek_app_version = $connection->createCommand("SELECT * FROM temp_app_version")->queryOne();

      $kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$cek_kategori[kategori]'")->queryOne();
      $cek_case = $connection->createCommand("SELECT * FROM cases WHERE gambar IS NULL ORDER BY id DESC")->queryOne();
      $cek_used_check_ticket = $connection->createCommand("SELECT * FROM temp_cek_ticket WHERE cek_ticket='1'")->queryOne();

      $user = $data['message']['from']['id'];
      $cek_available = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user' AND status='on'")->queryOne();

      if(empty($user) && $cek_available == false):
          return $this->redirect(['login']);
      endif;
      
      if(empty($data['message']['photo'])):
          $chat_id = $data['message']['chat']['id'];
          $user_tele = $data['message']['from']['id'];
          $nama_depan = $data['message']['chat']['first_name'];
          // $nama_belakang = $data['message']['chat']['last_name'];
          $username = $data['message']['chat']['username'];
          $text = $data['message']['text'];

          $session_db = $connection->createCommand("SELECT * FROM session_bot WHERE my_session IS NOT NULL AND telegram_id='$user_tele'")->queryOne();
          $cek_user_actived = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND `status`='on'")->queryOne();
          $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

          $cek_ticket = $connection->createCommand("SELECT * FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->queryOne();


          if(empty($cek_user_actived)):
            return $this->render('get_not_register',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
            ]);
            die();
          endif;

          if($cek_used_system && $cek_used_system['max_active_date'] <= date('Y-m-d H:i:s')):
            $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
            $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();

            return $this->render('get_end_maxduration',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
            ]);
          endif;

          switch(true):
            case (is_numeric($text) && $cek_used_check_ticket != NULL && strlen($text) == '8' && $cek_ticket):
              
              $sql_tiket = Cases::find()->where("tiket='$text'")->one();
              if(!empty($sql_tiket->tiket)):
                  $progress = ListProgressCases::find()->where(['cases'=>$sql_tiket->id])->orderBy('id DESC')->one();

                  if($sql_tiket->status_owner == "On Progress"):
                    $update = $connection->createCommand("UPDATE cases SET follow_up=follow_up+1 WHERE id='$sql_tiket->id'")->execute();
                  endif;
                  if(!empty($progress)):
                      if($progress->status == "Closed" && $sql_tiket->status_owner == "Closed"):
                        $status = "Closed";
                      elseif($progress->status == "On Progress" && $sql_tiket->status_owner != "Closed" || $progress->status == "On Progress" && $sql_tiket->status_owner != "New"):
                        $status = "On Progress";
                      elseif($progress->status == "On Progress" && $sql_tiket->status_owner != "New"):
                        $status = "Being Processed";
                      endif;

                      if($progress->feedback == NULL || $progress->feedback == ''):
                          $feedback = "Masih dalam tahap pengecekan, silahkan di cek kembali 5 menit kedepan. Terima kasih";
                      else:
                          $feedback = $progress->feedback;
                      endif;

                      return $this->render('get_status_ticket',[
                          'chat_id' => $cek_used_system['chat_id'],
                          'tiket' => $sql_tiket->tiket,
                          'nama_pelanggan' => $sql_tiket->nama,
                          'gambar' => $progress->feedback_gambar,
                          'keluhan' => $sql_tiket->keluhan,
                          'status' => $status,
                          'feedback' => $feedback,
                          'user_tele' => $cek_used_system['telegram_id']
                      ]);
                  else:
                    // $count_fu = is_numeric($sql_tiket->follow_up) +1;
                    $update = $connection->createCommand("UPDATE cases SET follow_up=follow_up+1 WHERE id='$sql_tiket->id'")->execute();

                      return $this->render('get_status_ticket',[
                          'chat_id' => $cek_used_system['chat_id'],
                          'tiket' => $sql_tiket->tiket,
                          'nama_pelanggan' => $sql_tiket->nama,
                          'gambar' => NULL,
                          'keluhan' => $sql_tiket->keluhan,
                          'status' => "Being Process",
                          'feedback' => "Masih dalam tahap proses pengecekan. Silahkan di cek kembali 5 menit kedepan",
                          'user_tele' => $cek_used_system['telegram_id']
                      ]);
                  endif;
              else:
                  return $this->render('get_notfound_ticket',[
                      'chat_id' => $chat_id,
                      'nama_depan' => $nama_depan,
                      'tiket' => $text
                  ]);
              endif;
              break;
            case (is_numeric($text) && strlen($text) != '8' && $cek_user_actived && $cek_ticket):
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_notfound_ticket',[
                  'chat_id' => $cek_used_system['chat_id'],
                  'nama_depan' => $cek_used_system['first_name'],
                  'tiket' => $text
              ]);
              break;
            case ($text == 'Check Ticket' && $cek_user_actived ):
                
              $sql = $connection->createCommand()->insert('temp_cek_ticket', [
                  'cek_ticket' => '1',
                  'telegram_id' => $user_tele
              ])->execute();

              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_ticket',[
                  'chat_id' => $cek_used_system['chat_id'],
                  'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case ($text == 'Skip >>' && $cek_user_actived && $session_db['my_session'] == "6"):
              $email = $connection->createCommand("SELECT * FROM temp_email WHERE telegram_id='$user_tele'")->queryOne();
              $source = $connection->createCommand("SELECT * FROM temp_source WHERE telegram_id='$user_tele'")->queryOne();
              $kategori = $connection->createCommand("SELECT * FROM temp_kategori WHERE telegram_id='$user_tele'")->queryOne();
              $cek_kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$kategori[kategori]'")->queryOne();
              $cek_case = $connection->createCommand("SELECT * FROM cases WHERE telegram_id='$user_tele' ORDER BY id DESC")->queryOne();

              $update_case = $connection->createCommand("UPDATE cases SET source='$source[source]',source_email='$email[email]',kategori='$cek_kategori[id]' WHERE id='$cek_case[id]' AND telegram_id='$user_tele'")->execute();
              
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
                
              return $this->render('get_end',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name'],
                'tiket' => $cek_case['tiket'],
                'user_tele' => $cek_used_system['telegram_id']
              ]);
              break;
            case ($cek_user_actived && $text == "Continue" && $session_db['my_session'] == '6'):
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              $get_case = $connection->createCommand("SELECT * FROM temp_cases WHERE telegram_id='$user_tele'")->queryOne();

              $now = date("Y-m-d H:i:s");

              $case = new Cases2;
              $case->tiket = $get_case['tiket'];
              $case->nama = $get_case['nama'];
              $case->email = $get_case['email'];
              $case->hp = $get_case['hp'];
              $case->pstn = $get_case['pstn'];
              $case->inet = $get_case['inet'];
              $case->no_tiket = $get_case['no_tiket'];
              $case->keluhan = $get_case['keluhan'];
              $case->app_version = $get_case['app_version'];
              $case->channel = '4';
              $case->sub_channel = $get_case['sub_channel'];
              $case->kategori = $get_case['kategori'];
              $case->tanggal_masuk = $now;
              $case->source = $get_case['source'];
              $case->source_email = $get_case['email'];
              $case->status_owner = 'New';
              $case->urgensi_status = 'Normal';
              $case->telegram_id = $user_tele;
              $case->follow_up = '0';
              $case->save(false);

              return $this->render('get_pictures',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case (/*strpos($text, 'ase#') >= 0 && */$cek_user_actived && $session_db['my_session'] == '5'):
              $text = str_replace("\n","",$text);
              $char = ord("#");
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

                switch(true):
                  case (substr_count($text, "#") == '7' && $cek_user_actived && $session_db['my_session'] == '5'):
                    $datas = explode(chr($char), $text);

                    $nama = $datas[1];
                    $email = $datas[2];
                    $hp = $datas[3];
                    $pstn = $datas[4];
                    $inet = $datas[5];
                    $no_tiket = $datas[6];
                    $keluhan = $datas[7];
                    $now = date("Y-m-d H:i:s");

                    // $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
                            
                    if($nama == '' || $email == '' || $hp == '' || $keluhan == ''):
                      return $this->render('get_case',[
                        'chat_id' => $cek_used_system['chat_id'],
                        'nama_depan' => $cek_used_system['first_name']
                      ]);
                    endif;
                
                    $value = mt_rand(10000000, 99999999);
                    $cek_tiket_db = $connection->createCommand("SELECT * FROM cases WHERE tiket='$value'")->queryOne();
                    if($cek_tiket_db):
                        $value = mt_rand(10000000, 99999999);
                    endif;
                
                    $connection->createCommand("UPDATE session_bot SET my_session='6' WHERE telegram_id='$user_tele'")->execute();

                    $source = $connection->createCommand("SELECT * FROM temp_source WHERE telegram_id='$user_tele'")->queryOne();
                    $sub_channel = $connection->createCommand("SELECT * FROM sub_channel WHERE nama_sub_channel='$source[source]' AND id_channel='4'")->queryOne();
                    
                    $kategori = $connection->createCommand("SELECT * FROM temp_kategori WHERE telegram_id='$user_tele'")->queryOne();
                    $cek_kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$kategori[kategori]'")->queryOne();
                    
                    $version = $connection->createCommand("SELECT * FROM temp_app_version WHERE telegram_id='$user_tele'")->queryOne();

                    $temp_email = $connection->createCommand("SELECT * FROM temp_email WHERE telegram_id='$user_tele'")->queryOne();

                    $case = new TempCases;
                    $case->tiket = $value;
                    $case->nama = $nama;
                    $case->email = $email;
                    $case->hp = $hp;
                    $case->pstn = $pstn;
                    $case->inet = $inet;
                    $case->no_tiket = $no_tiket;
                    $case->app_version = $version['version'];
                    $case->keluhan = $keluhan;
                    $case->channel = '4';
                    $case->sub_channel = $sub_channel['id_sub_channel'];
                    $case->kategori = $cek_kategori['id'];
                    $case->tanggal_masuk = $now;
                    $case->source = $source['source'];
                    $case->source_email = $temp_email['email'];
                    $case->status_owner = 'New';
                    $case->urgensi_status = 'Normal';
                    $case->telegram_id = $user_tele;
                    $case->save(false);
                    
                    $cek_exist_case = $connection->createCommand("SELECT * FROM cases WHERE nama='$nama' AND hp='$hp' AND email='$email' AND app_version='$version[version]' AND kategori='$cek_kategori[id]' AND status_owner!='Closed'")->queryOne();

                    if(!empty($cek_exist_case)){
                      return $this->render('get_choose_created_double',[
                        'chat_id' => $cek_used_system['chat_id'],
                        'nama_depan' => $cek_used_system['first_name'],
                        'tiket' => $cek_exist_case['tiket'],
                        'keluhan' => $cek_exist_case['keluhan'],
                      ]);
                    }else{
                      $get_case = $connection->createCommand("SELECT * FROM temp_cases WHERE telegram_id='$user_tele'")->queryOne();

                      $case = new Cases2;
                      $case->tiket = $get_case['tiket'];
                      $case->nama = $get_case['nama'];
                      $case->email = $get_case['email'];
                      $case->hp = $get_case['hp'];
                      $case->pstn = $get_case['pstn'];
                      $case->inet = $get_case['inet'];
                      $case->no_tiket = $get_case['no_tiket'];
                      $case->keluhan = $get_case['keluhan'];
                      $case->app_version = $get_case['app_version'];
                      $case->channel = '4';
                      $case->sub_channel = $get_case['sub_channel'];
                      $case->kategori = $get_case['kategori'];
                      $case->tanggal_masuk = $now;
                      $case->source = $get_case['source'];
                      $case->source_email = $get_case['email'];
                      $case->status_owner = 'New';
                      $case->urgensi_status = 'Normal';
                      $case->telegram_id = $user_tele;
                      $case->follow_up = '0';
                      $case->save(false);

                      return $this->render('get_pictures',[
                        'chat_id' => $cek_used_system['chat_id'],
                        'nama_depan' => $cek_used_system['first_name']
                      ]);
                    }
                    break;
                  default:
                    return $this->render('get_case',[
                      'chat_id' => $cek_used_system['chat_id'],
                      'nama_depan' => $cek_used_system['first_name']
                    ]);
                endswitch;
              break;
            case ($text == "3.85" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "3.81" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "3.80" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "3.70" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "3.10" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "3.00" && $cek_user_actived && $session_db['my_session'] == '4' || $text == "PARTNER" && $cek_user_actived && $session_db['my_session'] == '4'):
              
              $sql = $connection->createCommand()->insert('temp_app_version',[
                'version' => $text,
                'telegram_id' => $user_tele
              ])->execute();

              $connection->createCommand("UPDATE session_bot SET my_session='5' WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_case',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case ($text == 'Sign Up' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Log In' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Reset Akun' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Tambah No.Layanan (Mapping)' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Tagihan' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Transaksi Add On' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Unsubscribe' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'Lapor Gangguan' && $cek_user_actived && $session_db['my_session'] == "3" || $text == 'POIN' && $cek_user_actived && $session_db['my_session'] == "3" || $text == "Order PSB" && $cek_user_actived && $session_db['my_session'] == "3" || $text == "Top Up Kuota" && $cek_user_actived && $session_db['my_session'] == "3" || $text == "Dompet myIndiHome" && $cek_user_actived && $session_db['my_session'] == "3" || $text == "FUP" && $cek_user_actived && $session_db['my_session'] == "3"):
              
              $sql = $connection->createCommand()->insert('temp_kategori',[
                'kategori' => $text,
                'telegram_id' => $user_tele
              ])->execute();
              
              $connection->createCommand("UPDATE session_bot SET my_session='4' WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_app_version',[
                  'chat_id' => $cek_used_system['chat_id'],
                  'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case (strpos($text, "@") > 0 && $cek_user_actived && $session_db['my_session'] == '2'):
              
              $sql = $connection->createCommand()->insert('temp_email',[
                'email' => $text,
                'telegram_id' => $user_tele
              ])->execute();

              $connection->createCommand("UPDATE session_bot SET my_session='3' WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_categories',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case ($text == "147" && $cek_user_actived && $session_db['my_session'] == '1' || $text == "C4" && $cek_user_actived && $session_db['my_session'] == '1' || $text == "Social Media" && $cek_user_actived && $session_db['my_session'] == '1' || $text == "Plasa Telkom" && $cek_user_actived && $session_db['my_session'] == '1' || $text == "Sales" && $cek_user_actived && $session_db['my_session'] == '1' || $text == "Lainnya" && $cek_user_actived && $session_db['my_session'] == '1'):
            
              $sql = $connection->createCommand()->insert('temp_source',[
                  'source' => $text,
                  'telegram_id' => $user_tele,
              ])->execute();

              $connection->createCommand("UPDATE session_bot SET my_session='2' WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
              
              return $this->render('get_source_email',[
                  'chat_id' => $cek_used_system['chat_id'],
              ]);
              break;
            case($text == "Create Ticket" && $cek_user_actived):
              
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              
              $connection->createCommand()->insert('session_bot',[
                  'my_session' => '1',
                  'telegram_id' => $user_tele,
              ])->execute();
              
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_source',[
                  'chat_id' => $cek_used_system['chat_id'],
                  'nama_depan' => $cek_used_system['first_name']
              ]);
              break;
            case($text == "Start" && $cek_user_actived || $text == "Home" && $cek_user_actived || $text == "start" && $cek_user_actived):

              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();

              $date = date('Y-m-d H:i:s');
              $currentDate = strtotime($date);
              $futureDate = $currentDate+(60*10);
              $max_date = date("Y-m-d H:i:s", $futureDate);

              $connection->createCommand()->insert('temp_active_id', [
                'chat_id' => $chat_id,
                'username' => $username,
                'telegram_id' => $user_tele,
                'first_name' => $nama_depan,
                'active_date' => $date,
                'max_active_date' => $max_date
              ])->execute();

              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_start',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name'],
                'start_date' => $cek_used_system['active_date'],
                'max_date' => $cek_used_system['max_active_date']
              ]);
              break;
            case ($text == "Cancel" && $cek_user_actived || $text == "cancel" && $cek_user_actived):
              
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              return $this->render('get_cancel_form',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
              ]);
              break;
            case (empty($cek_user_actived)):
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              return $this->render('get_not_register',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
              ]);
              break;
            default:
                $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();

                return $this->render('get_start0',[
                    'chat_id' => $chat_id,
                    'nama_depan' => $nama_depan
                ]);
          endswitch;
      elseif(!empty($data['message']['photo'])):
        $chat_id = $data['message']['chat']['id'];
        $user_tele = $data['message']['from']['id'];
        $nama_depan = $data['message']['chat']['first_name'];
        $photo = $data['message']['photo'];

        $session_db = $connection->createCommand("SELECT * FROM session_bot WHERE my_session IS NOT NULL AND telegram_id='$user_tele'")->queryOne();
        $cek_user_actived = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND status='on'")->queryOne();
        $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

        if($cek_used_system && $cek_used_system['max_active_date'] <= date('Y-m-d H:i:s')):
          $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
          $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();

          return $this->render('get_end_maxduration',[
              'chat_id' => $chat_id,
              'nama_depan' => $nama_depan
          ]);
        endif;

        // $agg = json_encode($chat_id, JSON_PRETTY_PRINT);
        // Yii::$app->telegram->sendMessage($nama_depan, $chat_id);

        switch(true):
          case ($cek_user_actived && $session_db['my_session'] == "6"):
            $get_file = Yii::$app->telegram->getFile([
              'file_id' => $data['message']['photo']['1']['file_id'],
            ]);
            $url = "https://api.telegram.org/file/bot1248348390:AAGXMfWmHAzfKoEEihR1VGu_036LTSwRHnc/".$get_file->result->file_path;
            $filename = basename($url);
    
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $update_image = $cek_case['id'].'.'.$ext;
            $myFile=Yii::getAlias("@webroot/images/".$update_image);
            file_put_contents(
              $myFile,
              file_get_contents($url)
            );
            chmod($myFile, 0777);
            
            $email = $connection->createCommand("SELECT * FROM temp_email WHERE telegram_id='$user_tele'")->queryOne();
            $source = $connection->createCommand("SELECT * FROM temp_source WHERE telegram_id='$user_tele'")->queryOne();
            $kategori = $connection->createCommand("SELECT * FROM temp_kategori WHERE telegram_id='$user_tele'")->queryOne();
            $cek_kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$kategori[kategori]'")->queryOne();
            $cek_case = $connection->createCommand("SELECT * FROM cases WHERE telegram_id='$user_tele' ORDER BY id DESC")->queryOne();

            // $update_case = $connection->createCommand("UPDATE cases SET source='$source[source]',source_email='$email[email]',kategori='$cek_kategori[id]' WHERE id='$cek_case[id]' AND telegram_id='$user_tele'")->execute();
            $update_case = $connection->createCommand("UPDATE cases SET source='$source[source]',source_email='$email[email]',kategori='$cek_kategori[id]', gambar='$update_image' WHERE id='$cek_case[id]'")->execute();
              
            $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
              
            return $this->render('get_end',[
              'chat_id' => $cek_used_system['chat_id'],
              'nama_depan' => $cek_used_system['first_name'],
              'tiket' => $cek_case['tiket'],
              'user_tele' => $cek_used_system['telegram_id']
            ]);
          break;
        endswitch;
      else:
      endif;
    }

    public function actionLogin()
    {
        $this->layout = "login-layout";

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['user/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
