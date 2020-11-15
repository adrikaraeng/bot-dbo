<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "urgensi_status".
 *
 * @property int $id
 * @property string|null $urgensi_status
 */
class UrgensiStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'urgensi_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urgensi_status'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'urgensi_status' => Yii::t('app', 'Urgensi Status'),
        ];
    }
}
