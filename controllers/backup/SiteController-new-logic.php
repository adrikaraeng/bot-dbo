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
use app\models\User;
use app\models\TempCases;
use app\models\ListProgressCases;
use app\models\Cases2;
use app\models\UserTelegram;
use mirkhamidov\telegramBot\TelegramBot;
use yii\web\Session;
use yii\helpers\Url;

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
        $token = 'bot1750605224:AAGmgpPzTKvcv_-1FmWm5KxYVm_FzMHD7SY';
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
      $connection2 = \Yii::$app->db2;
      $data = Yii::$app->telegram->hook();
      
      $cek_email = $connection->createCommand("SELECT * FROM temp_email")->queryOne();
      $cek_source = $connection->createCommand("SELECT * FROM temp_source")->queryOne();
      $cek_kategori = $connection->createCommand("SELECT * FROM temp_kategori")->queryOne();
      $cek_app_version = $connection->createCommand("SELECT * FROM temp_app_version")->queryOne();

      $kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$cek_kategori[kategori]'")->queryOne();
      $cek_case = $connection->createCommand("SELECT * FROM cases WHERE gambar IS NULL ORDER BY id DESC")->queryOne();

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

          $cek_used_check_ticket = $connection->createCommand("SELECT * FROM temp_cek_ticket WHERE cek_ticket='1' AND telegram_id='$user_tele'")->queryOne();
          $session_db = $connection->createCommand("SELECT * FROM session_bot WHERE my_session IS NOT NULL AND telegram_id='$user_tele'")->queryOne();
          $cek_user_actived = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND `status`='on'")->queryOne();
          $cek_user_inactived = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND `status`='off'")->queryOne();
          $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
          $cek_search_val = $connection->createCommand("SELECT * FROM temp_search_case WHERE telegram_id='$user_tele'")->queryOne();

          if(empty($cek_user_actived)):
            // $cek_user_reg = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND status_reg > '0'")->queryOne();
            $session_reg = $connection->createCommand("SELECT * FROM session_reg WHERE telegram_id='$user_tele'")->queryOne();

            $cek_user_off = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND `status`='off'")->queryOne();

            if($text == 'Daftar' && empty($session_reg)):

              if(empty($cek_user_off)):
                $connection->createCommand()->insert('session_reg',[
                  'telegram_id' => $user_tele,
                  'tahap' => '1'
                ])->execute();

                return $this->render('get_format_reg',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
              else:
                return $this->render('get_exist_user',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
              endif;
            elseif($session_reg['tahap'] == '1' && substr_count($text, "#") != '7' && $text != 'Cancel' && $text != "Exit"):
              return $this->render('get_invalid_format_reg',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
              ]);
            elseif($session_reg['tahap'] == '1' && $text=='Cancel'):
              $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              return $this->render('get_cancel_register',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
              ]);
            elseif($session_reg['tahap'] == '1' && substr_count($text, "#") == '7' && $text != "Exit"):
              
              $data = explode("#", $text);

              $nama = $data[1];
              $email = $data[2];
              $hp = $data[3];
              $regional = $data[4];
              $witel = $data[5];
              $layanan = $data[6];
              $prener = $data[7];

              $rand_aktivasi = mt_rand(100000, 999999);

              $cek_data = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele'")->queryOne();
              if(empty($cek_data) && !empty($nama) && !empty($email) && !empty($hp) && !empty($regional) && !empty($witel) && !empty($layanan) && !empty($prener) && strlen($nama) >= '3' && strlen($email) >= '3' && strlen($hp) >= '6'){
                $sql_new = $connection->createCommand()->insert('user_telegram',[
                  'telegram_id' => $user_tele,
                  'nama_lengkap' => $nama,
                  'no_handphone' => $hp,
                  'email' => $email,
                  'regional' => $regional,
                  'witel' => $witel,
                  'layanan' => $layanan,
                  'prener' => $prener,
                  'aktivasi' => $rand_aktivasi,
                  'reg_date' => date('Y-m-d H:i:s'),
                  'status' => "off"
                ])->execute();
  
                $tomail = trim(preg_replace('/\s+/', ' ',$email));
                // $tomail = preg_replace('/\s+/', ' ', $email);

                \Yii::$app->mailer->compose()
                ->setFrom("tiketmyindihomebot@gmail.com")
                ->setTo($tomail)
                ->setSubject("Aktivasi Tiket myIndiHome bot")
                ->setTextBody("Plain text content")
                ->setHtmlBody("<b>Hai ".$nama."</b>,<br>Berikut kode OTP anda:<br><b>".$rand_aktivasi."</b> <br><br><b>Best Regard</b><br>#DBOmyIndiHome<br>Terima kasih")
                ->send();

                $connection->createCommand("UPDATE session_reg SET tahap='2' WHERE telegram_id='$user_tele'")->execute();
                // $connection->createCommand("UPDATE user_telegram SET status_reg='1' WHERE telegram_id='$user_tele'")->queryOne();

                return $this->render('get_kode_aktivasi',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
              }else{
                return $this->render('get_invalid_format_reg',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
              }
            elseif($text=="Resend" && $session_reg['tahap'] == '2'):

              $rand_aktivasi = mt_rand(100000, 999999);
              $connection->createCommand("UPDATE user_telegram SET aktivasi='$rand_aktivasi' WHERE telegram_id='$user_tele'")->execute();
              $model = UserTelegram::find()->where(['telegram_id' => $user_tele])->asArray()->one();

              $tomail = trim(preg_replace('/\s+/', ' ', $model['email']));
              
              \Yii::$app->mailer->compose()
              ->setFrom("tiketmyindihomebot@gmail.com")
              ->setTo($tomail)
              ->setSubject("Aktivasi Tiket myIndiHome bot")
              ->setTextBody("Plain text content")
              ->setHtmlBody("<b>Hai ".$model['nama_lengkap']."</b>,<br>Berikut kode OTP anda:<br><b>".$model['aktivasi']."</b> <br><br><b>Best Regard</b><br>#DBOmyIndiHome<br>Terima kasih")
              ->send();

              return $this->render('get_kode_aktivasi',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan,
                'model' => $model
              ]);
            elseif($cek_user_off && $session_reg['tahap'] == '2' && $text !="Exit"):
              $model = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele'")->queryOne();
            
              if($model['aktivasi'] == $text && strlen(preg_replace("/([^0-9]+)/","",$text)) == "6"):
                $connection->createCommand("UPDATE user_telegram SET `status`='on' WHERE telegram_id='$user_tele'")->execute();
                $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
                return $this->render('get_sukses_daftar',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
              else:
                return $this->render('get_invalid_aktivasi',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
                ]);
               endif;
            elseif($text=="Exit" && $session_reg['tahap'] == '1' || $text=="Exit" && $session_reg['tahap'] == '2' || $cek_user_off['telegram_id'] == $user_tele && $session_reg['tahap'] == '1' || $cek_user_off['telegram_id'] == $user_tele && $session_reg['tahap'] == '2'):
              $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM user_telegram WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              return $this->render('get_not_register',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
              ]);
            else:
              $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              return $this->render('get_not_register',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
              ]);
            endif;
            die();
          endif;

          if($cek_user_inactived):
            return $this->render('get_user_inactive',[
              'chat_id' => $chat_id,
              'nama_depan' => $nama_depan
            ]);
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
            $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();

            return $this->render('get_end_maxduration',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
            ]);
          endif;
          $cek_ticket = $connection->createCommand("SELECT * FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->queryOne();
          
          if($cek_user_actived):
            if($text == "Start" || $text == "Home"):
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();

              $date = date('Y-m-d H:i:s');
              $currentDate = strtotime($date);
              $futureDate = $currentDate+(60*15);
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

              $info = $connection2->createCommand("SELECT *,b.jenis_aplikasi AS bjenis_aplikasi FROM tb_logbook_data AS a
              INNER JOIN tb_logbook_jenis_aplikasi AS b ON b.id=a.jenis_aplikasi
              INNER JOIN tb_logbook_jenis_gangguan AS c ON c.id=a.modul_gangguan
              INNER JOIN tb_logbook_sub_gangguan AS d ON d.id=a.sub_gangguan
              WHERE a.status_gangguan='0'
              ORDER BY a.startdate ASC
              ")->queryAll();
              if($info){
                $info_data = [];
                foreach($info as $i => $in):
                  $info_data[] = "\xE2\x9A\xA0 ".$in['detail']."\nAplikasi : ".$in['bjenis_aplikasi']."\n";
                endforeach;
                $load_info="\nBerikut kami sampaikan, saat ini sedang terjadi gangguan pada:\n".implode("\n", $info_data)."\n<b>Best Regard</b>\n#DBOmyIndiHome\nTerima kasih.\n";
              }else{
                $load_info="";
              }

              return $this->render('get_start',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name'],
                'start_date' => $cek_used_system['active_date'],
                'max_date' => $cek_used_system['max_active_date'],
                'load_info' => $load_info
              ]);
              
            elseif($text == "Buat Tiket"):
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              
              $connection->createCommand()->insert('session_bot',[
                  'my_session' => '1',
                  'telegram_id' => $user_tele,
              ])->execute();
              
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_source',[
                  'chat_id' => $cek_used_system['chat_id'],
                  'nama_depan' => $cek_used_system['first_name']
              ]);
            elseif($text == "147" && $session_db['my_session'] == '1' || $text == "C4" &&  $session_db['my_session'] == '1' || $text == "Sosial Media" && $session_db['my_session'] == '1' || $text == "Plasa Telkom" && $session_db['my_session'] == '1' || $text == "Sales" && $session_db['my_session'] == '1' || $text == "Lainnya" &&  $session_db['my_session'] == '1'):
          
              $sql = $connection->createCommand()->insert('temp_source',[
                  'source' => $text,
                  'telegram_id' => $user_tele,
              ])->execute();

              $connection->createCommand("UPDATE session_bot SET my_session='2' WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
              
              return $this->render('get_source_email',[
                  'chat_id' => $cek_used_system['chat_id'],
              ]);
            elseif(strpos($text, "@") > 0 && $session_db['my_session'] == '2' && $text != "Exit"):
            
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
            elseif(strpos($text, "@") == 0 && $session_db['my_session'] == '2' && $text != "Exit"):
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();
              return $this->render('get_invalid_email',[
                'chat_id' => $cek_used_system['chat_id'],
              ]);
            elseif($text == 'Sign Up' && $session_db['my_session'] == "3" || $text == 'Log In' &&  $session_db['my_session'] == "3" || $text == 'Reset Akun' && $session_db['my_session'] == "3" || $text == 'Tambah No.Layanan (Mapping)' && $session_db['my_session'] == "3" || $text == 'Tagihan' && $session_db['my_session'] == "3" || $text == "Transaksi Add On" && $session_db['my_session'] == "3" || $text == 'Unsubscribe' && $session_db['my_session'] == "3" || $text == 'Lapor Gangguan' &&  $session_db['my_session'] == "3" || $text == 'POIN' && $session_db['my_session'] == "3" || $text == "Order PSB" && $session_db['my_session'] == "3" || $text == "Top Up Kuota" && $session_db['my_session'] == "3" || $text == "Dompet myIndiHome" && $session_db['my_session'] == "3" || $text == "FUP" && $session_db['my_session'] == "3"):
            
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
            elseif($text == "3.85" && $session_db['my_session'] == '4' || $text == "PARTNER" && $session_db['my_session'] == '4' || $text == "myIH X" && $session_db['my_session'] == '4'):
            
              if($text == "myIH X"):
                $text = "myIndiHome X";
              endif;
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
            elseif($session_db['my_session'] == '5' && $text != "Exit"):
              $text = str_replace("\n","",$text);
              $char = ord("#");
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              switch(true):
                case (substr_count($text, "#") != '7' && $cek_user_actived && $session_db['my_session'] == '5'):
                  return $this->render('get_invalid_case',[
                    'chat_id' => $cek_used_system['chat_id'],
                    'nama_depan' => $cek_used_system['first_name']
                  ]);
                case (substr_count($text, "#") == '7' && $cek_user_actived && $session_db['my_session'] == '5'):
                  
                  $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

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
                          
                  $value = strtotime($now);
                  $cek_tiket_db = $connection->createCommand("SELECT * FROM cases WHERE tiket='$value'")->queryOne();

                  if($cek_tiket_db):
                    return $this->render('get_case2',[
                      'chat_id' => $cek_used_system['chat_id'],
                      'nama_depan' => $cek_used_system['first_name']
                    ]);
                  else:
                    if($nama == '' || $email == '' || $hp == '' || $keluhan == ''):
                      return $this->render('get_invalid_case',[
                        'chat_id' => $cek_used_system['chat_id'],
                        'nama_depan' => $cek_used_system['first_name']
                      ]);
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
                  endif;
                  break;
                default:
                  return $this->render('get_case',[
                    'chat_id' => $cek_used_system['chat_id'],
                    'nama_depan' => $cek_used_system['first_name']
                  ]);
              endswitch;
            elseif($text == 'Skip >>' && $session_db['my_session'] == "6"):
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
            
            elseif($text == "Continue" && $session_db['my_session'] == '6'):
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
            elseif($text == "Cancel" || $text == "cancel"):
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();

              return $this->render('get_cancel_form',[
                'chat_id' => $chat_id,
                'nama_depan' => $nama_depan
              ]);

            elseif($text == 'Cek Tiket'):
              $sql = $connection->createCommand()->insert('temp_cek_ticket', [
                  'cek_ticket' => '1',
                  'telegram_id' => $user_tele
              ])->execute();

              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();

              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_ticket',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name']
              ]);
            elseif($text != "Cek Tiket" && strlen($text) != '10' && $cek_ticket && $text != "Exit"):
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_notfound_ticket',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name'],
                'tiket' => $text
              ]);
            elseif(strlen($text) == '10' && $cek_ticket || strlen($text) == '8' && $cek_ticket && $text != "Exit"):
              $text = preg_replace("/[^0-9]/", "", $text);
              
              $sql_tiket = Cases::find()->where("tiket='$text'")->one();
              if(!is_numeric($text) || empty($sql_tiket)){
                return $this->render('get_notfound_ticket',[
                    'chat_id' => $chat_id,
                    'nama_depan' => $nama_depan,
                    'tiket' => $text
                ]);
                die();
              }
              if(!empty($sql_tiket->tiket)):
                if($sql_tiket->status_owner != "Closed"):
                  $update = $connection->createCommand("UPDATE cases SET follow_up=follow_up+1 WHERE id='$sql_tiket->id'")->execute();
                endif;

                if($sql_tiket->feedback != '' || $sql_tiket->feedback != NULL):
                  if($sql_tiket->status_owner == "Closed"):
                    $status = "Closed";
                  else:
                    $status = "On Progress";
                  endif;

                  if($sql_tiket->feedback == '' || $sql_tiket->feedback == NULL):
                    $feedback = "Masih dalam tahap pengecekan, silahkan di cek kembali 5 menit kedepan. Terima kasih";
                  else:
                    $feedback = $sql_tiket->feedback;
                  endif;

                  return $this->render('get_status_ticket',[
                    'chat_id' => $cek_used_system['chat_id'],
                    'tiket' => $sql_tiket->tiket,
                    'nama_pelanggan' => $sql_tiket->nama,
                    'gambar' => $sql_tiket->feedback_gambar,
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
            elseif($text == "Search"):
            
              $sql = $connection->createCommand()->insert('temp_search_case', [
                  'search_permit' => '1',
                  'telegram_id' => $user_tele
              ])->execute();

              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_reg WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();

              $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE telegram_id='$user_tele'")->queryOne();

              return $this->render('get_insert_search',[
                'chat_id' => $cek_used_system['chat_id'],
                'nama_depan' => $cek_used_system['first_name']
              ]);
            
            elseif($text != "Search" && $cek_search_val['search_permit'] == '1' && $text != "Exit"):
              $text = preg_replace('/\s/', '', $text);
              $date1 = date('Y-m-d',strtotime("-1 days"));
              $date2 = date('Y-m-d',strtotime("-2 days"));
              $date3 = date('Y-m-d',strtotime("-3 days"));
              $today = date('Y-m-d');
              $sql = $connection->createCommand("SELECT * FROM cases WHERE
              email LIKE '%$text%' AND tanggal_masuk LIKE '%$date1%' OR
              hp='$text' AND tanggal_masuk LIKE '%$date1%' OR
              inet='$text' AND tanggal_masuk LIKE '%$date1%' OR
              pstn='$text' AND tanggal_masuk LIKE '%$date1%' OR
              email LIKE '%$text%' AND tanggal_masuk LIKE '%$date2%' OR
              hp='$text' AND tanggal_masuk LIKE '%$date2%' OR
              inet='$text' AND tanggal_masuk LIKE '%$date2%' OR
              pstn='$text' AND tanggal_masuk LIKE '%$date2%' OR
              email LIKE '%$text%' AND tanggal_masuk LIKE '%$date3%' OR
              hp='$text' AND tanggal_masuk LIKE '%$date3%' OR
              inet='$text' AND tanggal_masuk LIKE '%$date3%' OR
              pstn='$text' AND tanggal_masuk LIKE '%$date3%' OR
              email LIKE '%$text%' AND tanggal_masuk LIKE '%$today%' OR
              hp='$text' AND tanggal_masuk LIKE '%$today%' OR
              inet='$text' AND tanggal_masuk LIKE '%$today%' OR
              pstn='$text' AND tanggal_masuk LIKE '%$today%' OR
              email LIKE '%$text%' AND status_owner<>'Closed' OR
              hp='$text' AND status_owner<>'Closed' OR
              inet='$text' AND status_owner<>'Closed' OR
              pstn='$text' AND status_owner<>'Closed'
              ORDER BY tanggal_masuk ASC
              ")->queryAll();

              if($sql){
                $data = [];
                foreach($sql as $c => $cs):
                  if($cs['status_owner'] != "Closed"):
                    $status = "On Progress \xE2\x8C\x9B";
                  else:
                    $status = "Closed \xE2\x9C\x85";
                  endif;
                  $numPhone = substr($cs['hp'], 0, 4)."****".substr($cs['hp'], 8,10);
                  $data[] = "\xF0\x9F\x93\x8CTanggal Keluhan :<b>".date('d-m-Y H:i:s', strtotime($cs['tanggal_masuk']))."</b>\nSumber Tiket :<b>".$cs['source']."</b>\nTiket Bot :<b>".$cs['tiket']."</b>\nNama Customer :<b>".$cs['nama']."</b>\nEmail :<b>".$cs['email']."</b>\nCP :<b>".$numPhone."</b>\nNo.Internet :<b>".$cs['inet']."</b>\nPSTN :<b>".$cs['pstn']."</b>\nStatus :<b>".$status."</b>\nKeluhan :<b>".$cs['keluhan']."</b>\nPenanganan :<b>".$cs['feedback']."</b>";
                endforeach;
                
                $model = implode("\n\n", $data);
              }else{
                $model = "<b><i>Pencarian case tidak ditemukan</i></b> \xF0\x9F\x99\x8F";
              }
              return $this->render('get_search_case',[
                  'chat_id' => $chat_id,
                  'user_tele' => $user_tele,
                  'model' => $model
              ]);
            elseif($text == "Exit"):
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();

              return $this->render('get_start0',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
              ]);
            else:
              $connection->createCommand("DELETE FROM temp_source WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_kategori WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_email WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cek_ticket WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM session_bot WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_app_version WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_active_id WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_cases WHERE telegram_id='$user_tele'")->execute();
              $connection->createCommand("DELETE FROM temp_search_case WHERE telegram_id='$user_tele'")->execute();
  
              return $this->render('get_start0',[
                  'chat_id' => $chat_id,
                  'nama_depan' => $nama_depan
              ]);
            endif;
          endif;
          
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
            $url = "https://api.telegram.org/file/bot1750605224:AAGmgpPzTKvcv_-1FmWm5KxYVm_FzMHD7SY/".$get_file->result->file_path;
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

    public function actionSaveWebcam()
    {
      
      $this->layout = "login-layout";

      if(isset($_FILES['webcam'])){
        $user = User::findOne(Yii::$app->user->id);

        $filename =  $user->username.'-'.date('YmdHis').'.jpg';

        if(!empty($user->webcam) || $user->webcam != NULL):
          $oldFile = Yii::$app->basePath."/web/images/webcam/".$user->webcam;
          if(file_exists($oldFile)):unlink($oldFile);endif;
        endif;

        $filepath = Yii::getAlias('@webroot/images/webcam/');
        move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename);

        $user->webcam = $filename;
        $user->save(false);
      }
    }

    public function actionGotoUser()
    {
      return $this->redirect(['user/index']);
    }
    
    public function actionLogin()
    {
        $this->layout = "login-layout";

        if (!Yii::$app->user->isGuest) {
          Yii::$app->user->logout();
          return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
          return $this->redirect(['user/index']);
          // return json_encode("Yes");
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
