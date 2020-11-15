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
use app\models\ListProgressCases;
use app\models\Cases2;
use app\models\TempCases;
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
        $cek_used_system = $connection->createCommand("SELECT * FROM temp_active_id WHERE chat_id IS NOT NULL")->queryOne();

        $session_db = $connection->createCommand("SELECT * FROM session_bot WHERE my_session IS NOT NULL")->queryOne();

        if($data == NULL):
            return $this->redirect(['login']);
        endif;
        
        if($cek_used_system != NULL):
            if($cek_used_system['max_active_date'] <= date('Y-m-d H:i:s')):
                Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();
            endif;
        endif;
        if(empty($data['message']['photo'])):
            $chat_id = $data['message']['chat']['id'];
            $user_tele = $data['message']['from']['id'];
            $nama_depan = $data['message']['chat']['first_name'];
            // $nama_belakang = $data['message']['chat']['last_name'];
            $username = $data['message']['chat']['username'];
            $text = $data['message']['text'];

            $cek_user_actived = $connection->createCommand("SELECT * FROM user_telegram WHERE telegram_id='$user_tele' AND status='on'")->queryOne();
            if(empty($cek_user_actived)):
                return $this->render('get_not_register',[
                    'chat_id' => $chat_id,
                    'nama_depan' => $nama_depan
                ]);
            else:
                if($cek_used_system == NULL || $cek_used_system['chat_id'] == $chat_id):
                    switch(true):
                        case ($text == 'Skip >>' && $session_db['my_session'] == "6"):
                            $update_case = $connection->createCommand("UPDATE cases SET source='$cek_source[source]',source_email='$cek_email[email]',kategori='$kategori[id]' WHERE id='$cek_case[id]'")->execute();
                            
                            Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                            Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();
                
                            return $this->render('get_end',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan,
                                'tiket' => $cek_case['tiket']
                            ]);
                            break;
                        case (strpos($text, 'ase#') >= 0 && $session_db['my_session'] == '5'):
                            // Nama
                            // Email
                            // no_hp
                            // pstn
                            // inet
                            // no_tiket
                            // detail keluhan
                            $text = str_replace("\n","",$text);
                            
                            $char = ord("#");
                            // $datas = explode("#", $text);

                            // $datas = preg_split('@#@', $text, '', PREG_SPLIT_NO_EMPTY);
                            // $datas = preg_split('@#@', $text, '', PREG_SPLIT_NO_EMPTY);
                            // Yii::$app->telegram->sendMessage(substr_count($text, "#"), $chat_id);

                            if(substr_count($text, "#") == '7'):
                                $datas = explode(chr($char), $text);

                                $nama = $datas[1];
                                $email = $datas[2];
                                $hp = $datas[3];
                                $pstn = $datas[4];
                                $inet = $datas[5];
                                $no_tiket = $datas[6];
                                $keluhan = $datas[7];
                                $version = $cek_app_version['version'];
                                $now = date("Y-m-d H:i:s");
                            
                                if($nama == '' || $email == '' || $hp == '' || $keluhan == ''):
                                    return $this->render('get_case',[
                                        'chat_id' => $chat_id,
                                        'nama_depan' => $nama_depan
                                    ]);
                                endif;
                
                                $value = mt_rand(10000000, 99999999);
                                $cek_tiket_db = $connection->createCommand("SELECT * FROM cases WHERE tiket='$value'")->queryOne();
                                if($cek_tiket_db):
                                    $value = mt_rand(10000000, 99999999);
                                endif;
                            
                                $connection->createCommand("UPDATE session_bot SET my_session='6' WHERE my_session='$session_db[my_session]'")->execute();

                                $case = new Cases2;
                                $case->tiket = $value;
                                $case->nama = $nama;
                                $case->email = $email;
                                $case->hp = $hp;
                                $case->pstn = $pstn;
                                $case->inet = $inet;
                                $case->no_tiket = $no_tiket;
                                $case->keluhan = $keluhan;
                                $case->channel = '2';
                                $case->tanggal_masuk = $now;
                                $case->app_version = $version;
                                $case->status_owner = 'New';
                                $case->urgensi_status = 'Normal';
                                $case->telegram_id = $user_tele;
                                $case->save(false);
                                
                                return $this->render('get_pictures',[
                                    'chat_id' => $chat_id,
                                    'nama_depan' => $nama_depan
                                ]);
                            else:
                                return $this->render('get_case',[
                                    'chat_id' => $chat_id,
                                    'nama_depan' => $nama_depan
                                ]);
                            endif;
                            break;
                        case ($text == "3.85" && $session_db['my_session'] == '4' || $text == "3.81" && $session_db['my_session'] == '4' || $text == "3.80" && $session_db['my_session'] == '4' || $text == "3.70" && $session_db['my_session'] == '4' || $text == "3.10" && $session_db['my_session'] == '4' || $text == "3.00" && $session_db['my_session'] == '4' || $text == "PARTNER" && $session_db['my_session'] == '4'):
                            
                            $sql = $connection->createCommand()->insert('temp_app_version',[
                                'version' => $text
                            ])->execute();
                            

                            $connection->createCommand("UPDATE session_bot SET my_session='5' WHERE my_session='$session_db[my_session]'")->execute();

                            return $this->render('get_case',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            break;
                        case (strpos($text, "@") > 0 && $session_db['my_session'] == '2'):
                            if(empty($cek_email)):
                                $sql = $connection->createCommand()->insert('temp_email',[
                                    'email' => $text
                                ])->execute();
                            endif;

                            $connection->createCommand("UPDATE session_bot SET my_session='3' WHERE my_session='$session_db[my_session]'")->execute();

                            return $this->render('get_categories',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            break;
                        case ($text == 'Sign Up' && $session_db['my_session'] == "3" || $text == "Log In" && $session_db['my_session'] == "3" || $text == "Reset Akun" && $session_db['my_session'] == "3" || $text == "Tambah No.Layanan (Mapping)" && $session_db['my_session'] == "3" || $text == "Tagihan" && $session_db['my_session'] == "3" || $text == "Transaksi Add On" && $session_db['my_session'] == "3" || $text == "Unsubscribe" && $session_db['my_session'] == "3" || $text == "Lapor Gangguan" && $session_db['my_session'] == "3" || $text == "POIN" && $session_db['my_session'] == "3" || $text == "Order PSB" && $session_db['my_session'] == "3" || $text == "Top Up Kuota" && $session_db['my_session'] == "3" || $text == "Dompet myIndiHome" && $session_db['my_session'] == "3" || $text == "FUP" && $session_db['my_session'] == "3"):
                        
                            if($cek_kategori == NULL):
                                $sql = $connection->createCommand()->insert('temp_kategori',[
                                    'kategori' => $text
                                ])->execute();
                            endif;
                            
                            $connection->createCommand("UPDATE session_bot SET my_session='4' WHERE my_session='$session_db[my_session]'")->execute();

                            return $this->render('get_app_version',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            // return $this->render('get_case',[
                            //     'chat_id' => $chat_id,
                            //     'nama_depan' => $nama_depan
                            // ]);
                            break;
                        case ($text == 'Create Ticket' && $session_db == NULL):
                            if(empty($session_db)):
                                $connection->createCommand()->insert('session_bot',[
                                    'my_session' => '1',
                                ])->execute();
                            endif;

                            return $this->render('get_source',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            break;
                        case ($text == '147' && $session_db['my_session'] == '1' || $text == 'C4' && $session_db['my_session'] == '1' || $text == 'Social Media' && $session_db['my_session'] == '1' || $text == 'Plasa Telkom' && $session_db['my_session'] == '1' || $text == 'Sales' && $session_db['my_session'] == '1' || $text == 'Lainnya' && $session_db['my_session'] == '1'):
                            if(empty($cek_source)):
                                $sql = $connection->createCommand()->insert('temp_source',[
                                    'source' => $text,
                                ])->execute();
                            endif;

                            $connection->createCommand("UPDATE session_bot SET my_session='2' WHERE my_session='$session_db[my_session]'")->execute();
                            
                            return $this->render('get_source_email',[
                                'chat_id' => $chat_id,
                            ]);
                            break;
                        case (is_numeric($text) && $cek_used_check_ticket != NULL && strlen($text) == '8'):
                            
                            // $sql_tiket = $connection->createCommand("SELECT * FROM cases WHERE tiket='$text'")->queryOne();
                            $sql_tiket = Cases::find()->where("tiket='$text'")->one();
                            
                            // Yii::$app->telegram->sendMessage("Hi $text", $chat_id);
                            if(!empty($sql_tiket->tiket)):
                                $progress = ListProgressCases::find()->where(['cases'=>$sql_tiket->id])->orderBy('id DESC')->one();
                                if(!empty($progress)):
                                    if($progress->status == "Closed"):
                                        $status = "Closed";
                                    else:
                                        $status = "On Progress";
                                    endif;

                                    if($progress->feedback == NULL || $progress->feedback == ''):
                                        $feedback = "Masih dalam tahap pengecekan, silahkan di cek kembali 5 menit kedepan. Terima kasih";
                                    else:
                                        $feedback = $progress->feedback;
                                    endif;
                                    // Yii::$app->telegram->sendMessage($sql_tiket['status'], $chat_id);

                                    Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                                    Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();

                                    return $this->render('get_status_ticket',[
                                        'chat_id' => $chat_id,
                                        'tiket' => $sql_tiket->tiket,
                                        'nama_pelanggan' => $sql_tiket->nama,
                                        'gambar' => $progress->feedback_gambar,
                                        'keluhan' => $sql_tiket->keluhan,
                                        'status' => $status,
                                        'feedback' => $feedback
                                    ]);
                                else:
                                    Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                                    Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
    
                                    return $this->render('get_status_ticket',[
                                        'chat_id' => $chat_id,
                                        'tiket' => $sql_tiket->tiket,
                                        'nama_pelanggan' => $sql_tiket->nama,
                                        'gambar' => NULL,
                                        'keluhan' => $sql_tiket->keluhan,
                                        'status' => "On Progress",
                                        'feedback' => "Masih dalam tahap proses pengecekan. Silahkan di cek kembali 5 menit kedepan"
                                    ]);
                                endif;
                            else:
                                Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                                Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                                return $this->render('get_notfound_ticket',[
                                    'chat_id' => $chat_id,
                                    'nama_depan' => $nama_depan,
                                    'tiket' => $text
                                ]);
                            endif;
                            break;
                        case ($text == 'Check Ticket' && $cek_used_check_ticket == NULL):
                
                            $sql = $connection->createCommand()->insert('temp_cek_ticket', [
                                'cek_ticket' => '1'
                            ])->execute();

                            return $this->render('get_ticket',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            break;
                        case (is_numeric($text) && strlen($text) != '8' && $cek_used_check_ticket != NULL):
                            Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                            return $this->render('get_notfound_ticket',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan,
                                'tiket' => $text
                            ]);
                            break;
                        // case ($cek_used_check_ticket != NULL):
                        //     return $this->render('get_used_cek_ticket',[
                        //         'chat_id' => $chat_id,
                        //         'tiket' => $text
                        //     ]);
                        //     break;
                        case ($text == 'Back'):
                            Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                            Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();

                            $date = date('Y-m-d H:i:s');
                            $currentDate = strtotime($date);
                            $futureDate = $currentDate+(60*7);
                            $max_date = date("Y-m-d H:i:s", $futureDate);

                            $connection->createCommand()->insert('temp_active_id', [
                                'chat_id' => $chat_id,
                                'username' => $username,
                                'first_name' => $nama_depan,
                                'active_date' => $date,
                                'max_active_date' => $max_date
                            ])->execute();

                            return $this->render('get_start',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan,
                                'start_date' => $date
                            ]);
                            break;
                        case ($text == "Home" || $text == "Start"):
                            Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                            Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();

                            $date = date('Y-m-d H:i:s');
                            $currentDate = strtotime($date);
                            $futureDate = $currentDate+(60*7);
                            $max_date = date("Y-m-d H:i:s", $futureDate);

                            $connection->createCommand()->insert('temp_active_id', [
                                'chat_id' => $chat_id,
                                'username' => $username,
                                'first_name' => $nama_depan,
                                'active_date' => $date,
                                'max_active_date' => $max_date
                            ])->execute();

                            // $agg = json_encode($data, JSON_PRETTY_PRINT);
                            // Yii::$app->telegram->sendMessage($agg, $chat_id);

                            return $this->render('get_start',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan,
                                'start_date' => $date
                            ]);
                            break;
                        case ($text == "Exit" || $text == "Close" || $text == "Cancel"):
                            Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                            Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();

                            // $connection->createCommand()->insert('temp_active_id', [
                            //     'chat_id' => $chat_id,
                            //     'username' => $username,
                            //     'first_name' => $nama_depan,
                            //     'last_name' => $nama_belakang
                            // ])->execute();
                            // $agg = json_encode($data, JSON_PRETTY_PRINT);
                            // Yii::$app->telegram->sendMessage($agg, $chat_id);
                            return $this->render('get_start0',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                            break;
                        default:
                            Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                            Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                            Yii::$app->db->createCommand()->truncateTable('temp_app_version')->execute();
                            // Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();

                            // $connection->createCommand()->insert('temp_active_id', [
                            //     'chat_id' => $chat_id,
                            //     'username' => $username,
                            //     'first_name' => $nama_depan,
                            //     'last_name' => $nama_belakang
                            // ])->execute();

                            return $this->render('get_start0',[
                                'chat_id' => $chat_id,
                                'nama_depan' => $nama_depan
                            ]);
                    endswitch;
                else:
                    return $this->render('get_used_system',[
                        'chat_id' => $chat_id,
                        'nama_depan' => $nama_depan
                    ]);
                endif;
            endif;
        elseif(!empty($data['message']['photo'])):
            $chat_id = $data['message']['chat']['id'];
            $nama_depan = $data['message']['chat']['first_name'];
            $photo = $data['message']['photo'];

            switch(true):
                case ($cek_used_system == NULL && $session_db['my_session'] == '6' || $cek_used_system['chat_id'] == $chat_id && $session_db['my_session'] == '6'):

                    $get_file = Yii::$app->telegram->getFile([
                        'file_id' => $data['message']['photo']['2']['file_id'],
                    ]);
                    // $chat_id = $data['message']
                    // $agg = json_encode($get_file->result->file_path, JSON_PRETTY_PRINT);
                    // Yii::$app->telegram->sendMessage($agg, $chat_id);
    
                    $url = "https://api.telegram.org/file/bot1248348390:AAGXMfWmHAzfKoEEihR1VGu_036LTSwRHnc/".$get_file->result->file_path;
                    // $ch = curl_init($url);
                    
                    $filename = basename($url);
    
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $update_image = $cek_case['id'].'.'.$ext;
                    
                    // $update_image = move_uploaded_file($filename, $update_image);
    
                    // $fp = fopen(Yii::getAlias("@webroot/images/".$update_image), 'w+');
                    // curl_setopt($ch, CURLOPT_FILE, $fp);
                    // curl_setopt($ch, CURLOPT_HEADER, 0);
                    // curl_exec($ch);
                    // curl_close($ch);
                    // fclose($fp);
                    file_put_contents(
                        Yii::getAlias("@webroot/images/".$update_image),
                        file_get_contents($url)
                    );
                    
                    $kategori = $connection->createCommand("SELECT * FROM kategori WHERE nama_kategori='$cek_kategori[kategori]'")->queryOne();
                    
                    $update_case = $connection->createCommand("UPDATE cases SET source='$cek_source[source]',source_email='$cek_email[email]',kategori='$kategori[id]', gambar='$update_image' WHERE id='$cek_case[id]'")->execute();
                    
                    Yii::$app->db->createCommand()->truncateTable('session_bot')->execute();
                    Yii::$app->db->createCommand()->truncateTable('temp_source')->execute();
                    Yii::$app->db->createCommand()->truncateTable('temp_kategori')->execute();
                    Yii::$app->db->createCommand()->truncateTable('temp_email')->execute();
                    Yii::$app->db->createCommand()->truncateTable('temp_cek_ticket')->execute();
                    Yii::$app->db->createCommand()->truncateTable('temp_active_id')->execute();
    
                    return $this->render('get_end',[
                        'chat_id' => $chat_id,
                        'nama_depan' => $nama_depan,
                        'tiket' => $cek_case['tiket']
                    ]);
                    break;
            endswitch;
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
