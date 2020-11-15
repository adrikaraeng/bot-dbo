<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_kategori".
 *
 * @property int $id
 * @property string $sub_kategori
 * @property int $id_kategori
 */
class SubKategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_kategori', 'id_kategori'], 'required'],
            [['id_kategori'], 'integer'],
            [['sub_kategori'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sub_kategori' => Yii::t('app', 'Sub Kategori'),
            'id_kategori' => Yii::t('app', 'Id Kategori'),
        ];
    }

    public static function getBackendList($cat_id)
    {
        $connection = \Yii::$app->db;

        $out = [];
        $sqlSub = $connection->createCommand("SELECT * FROM sub_kategori WHERE id='$cat_id'")->queryOne();
        $models = $connection->createCommand("SELECT * FROM backend WHERE id_sub_kategori='$sqlSub[id]'")->queryAll();

        foreach($models as $m => $model):
            $out[]=['id'=>$model['id'], 'name'=>$model['nama_backend']];
        endforeach;
        
        return $out;
    }
}
