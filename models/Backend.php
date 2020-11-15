<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backend".
 *
 * @property int $id
 * @property string $nama_backend
 * @property int|null $id_sub_kategori
 */
class Backend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backend';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_backend'], 'required'],
            [['id_sub_kategori'], 'integer'],
            [['nama_backend'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama_backend' => Yii::t('app', 'Nama Backend'),
            'id_sub_kategori' => Yii::t('app', 'Id Sub Kategori'),
        ];
    }
}
