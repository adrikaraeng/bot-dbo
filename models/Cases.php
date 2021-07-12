<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cases".
 *
 * @property int $id
 * @property string|null $nama
 * @property string|null $email
 * @property string|null $hp
 * @property string|null $tiket
 * @property string|null $pstn
 * @property string|null $inet
 * @property string|null $no_tiket
 * @property string|null $app_version
 * @property string|null $keluhan
 * @property string|null $tanggal_masuk
 * @property string $status_owner
 * @property string|null $gambar
 * @property string|null $feedback
 * @property int|null $login
 * @property string|null $source
 * @property string|null $source_email
 * @property int|null $kategori
 * @property int|null $sub_kategori
 * @property int|null $backend
 * @property string|null $urgensi_status
 * @property int|null $channel
 * @property int|null $sub_channel
 * @property string|null $feedback_gambard
 */
class Cases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keluhan', 'status_owner', 'feedback', 'kategori', 'nama', 'email', 'hp', 'app_version', 'telegram_id', 'sub_kategori', 'kategori', 'sub_channel', 'channel', 'backend'], 'required', 'message'=>''],
            [['keluhan', 'status_owner', 'gambar', 'gambar_blob', 'feedback_gambar_blob', 'feedback'], 'string'],
            [['feedback_gambar'],
                'file',
                'extensions' => 'jpg, jpeg, png', 
                'skipOnEmpty' => true,
                'maxSize' => 1024*1024*5,
                'tooBig' => 'File tidak boleh lebih dari 5 Mb',
            ],
            [['feedback_gambar'], 'unique'],
            [['tanggal_masuk', 'tanggal_closed'], 'safe'],
            [['login', 'kategori', 'sub_kategori', 'backend', 'channel', 'sub_channel', 'closed_by', 'follow_up'], 'integer'],
            [['nama', 'source_email'], 'string', 'max' => 150],
            [['email', 'source'], 'string', 'max' => 100],
            [['hp'], 'string', 'max' => 15],
            [['tiket', 'pstn', 'inet', 'app_version'], 'string', 'max' => 20],
            [['no_tiket'], 'string', 'max' => 35],
            [['urgensi_status'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama'),
            'email' => Yii::t('app', 'Email'),
            'hp' => Yii::t('app', 'HP'),
            'tiket' => Yii::t('app', 'Tiket'),
            'pstn' => Yii::t('app', 'PSTN'),
            'inet' => Yii::t('app', 'INET'),
            'no_tiket' => Yii::t('app', 'No Tiket'),
            'app_version' => Yii::t('app', 'App Version'),
            'keluhan' => Yii::t('app', 'Keluhan'),
            'tanggal_masuk' => Yii::t('app', 'Tanggal Masuk'),
            'status_owner' => Yii::t('app', 'Status'),
            'gambar' => Yii::t('app', 'Gambar'),
            'feedback' => Yii::t('app', 'Feedback'),
            'login' => Yii::t('app', 'Login'),
            'source' => Yii::t('app', 'Source'),
            'source_email' => Yii::t('app', 'Source Email'),
            'kategori' => Yii::t('app', 'Kategori'),
            'sub_kategori' => Yii::t('app', 'Sub Kategori'),
            'backend' => Yii::t('app', 'Backend'),
            'urgensi_status' => Yii::t('app', 'Urgensi Status'),
            'channel' => Yii::t('app', 'Channel'),
            'sub_channel' => Yii::t('app', 'Sub Channel'),
            'feedback_gambar' => Yii::t('app', 'Feedback Gambar'),
            'telegram_id' => Yii::t('app', 'ID Telegram'),
            'tanggal_closed' => Yii::t('app', 'Tanggal Closed'),
            'closed_by' => Yii::t('app', 'Closed By'),
            'follow_up' => Yii::t('app', 'FU'),
        ];
    }
}
