<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "progress_case".
 *
 * @property int $id
 * @property int|null $case
 * @property string|null $feedback
 * @property string|null $feedback_gambar
 * @property int|null $login
 * @property string|null $update
 *
 * @property Cases $case0
 * @property User $login0
 */
class ProgressCase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'progress_case';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['case', 'login'], 'integer'],
            [['feedback', 'feedback_gambar'], 'string'],
            [['update'], 'safe'],
            [['case'], 'exist', 'skipOnError' => true, 'targetClass' => Cases::className(), 'targetAttribute' => ['case' => 'id']],
            [['login'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['login' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'case' => Yii::t('app', 'Case'),
            'feedback' => Yii::t('app', 'Feedback'),
            'feedback_gambar' => Yii::t('app', 'Feedback Gambar'),
            'login' => Yii::t('app', 'Login'),
            'update' => Yii::t('app', 'Update'),
        ];
    }

    /**
     * Gets query for [[Case0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCase0()
    {
        return $this->hasOne(Cases::className(), ['id' => 'case']);
    }

    /**
     * Gets query for [[Login0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogin0()
    {
        return $this->hasOne(User::className(), ['id' => 'login']);
    }
}
