<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_telegram".
 *
 * @property int $id
 * @property string|null $telegram_id
 * @property string|null $layanan
 * @property string|null $no_handphone
 * @property string|null $nama_lengkap
 * @property string|null $status
 * @property string|null $email
 * @property string|null $regional
 * @property string|null $witel
 * @property string|null $prener
 * @property string $aktivasi
 * @property string|null $reg_date
 */
class UserTelegram extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_telegram';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'reg_date'], 'string'],
            [['aktivasi'], 'required'],
            [['telegram_id', 'no_handphone'], 'string', 'max' => 50],
            [['layanan'], 'string', 'max' => 100],
            [['nama_lengkap', 'email'], 'string', 'max' => 200],
            [['regional'], 'string', 'max' => 2],
            [['witel'], 'string', 'max' => 255],
            [['prener', 'aktivasi'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'telegram_id' => Yii::t('app', 'Telegram ID'),
            'layanan' => Yii::t('app', 'Layanan'),
            'no_handphone' => Yii::t('app', 'No Handphone'),
            'nama_lengkap' => Yii::t('app', 'Nama Lengkap'),
            'status' => Yii::t('app', 'Status'),
            'email' => Yii::t('app', 'Email'),
            'regional' => Yii::t('app', 'Regional'),
            'witel' => Yii::t('app', 'Witel'),
            'prener' => Yii::t('app', 'Prener'),
            'aktivasi' => Yii::t('app', 'Aktivasi'),
            'reg_date' => Yii::t('app', 'Reg Date'),
        ];
    }
}
