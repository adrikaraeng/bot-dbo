<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_channel".
 *
 * @property int $id
 * @property string|null $nama_sub_channel
 * @property int|null $id_channel
 */
class SubChannel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_channel'], 'integer'],
            [['nama_sub_channel'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama_sub_channel' => Yii::t('app', 'Nama Sub Channel'),
            'id_channel' => Yii::t('app', 'Id Channel'),
        ];
    }
}
