<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel".
 *
 * @property int $id_channel
 * @property string $nama_channel
 */
class Channel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_channel'], 'required'],
            [['nama_channel'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_channel' => Yii::t('app', 'Id Channel'),
            'nama_channel' => Yii::t('app', 'Nama Channel'),
        ];
    }
}
