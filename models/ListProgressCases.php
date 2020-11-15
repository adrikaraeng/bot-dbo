<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "list_progress_cases".
 *
 * @property int $id
 * @property int|null $cases
 * @property string|null $login
 * @property string|null $feedback
 * @property string|null $feedback_gambar
 * @property string|null $status
 * @property string|null $insert_date
 * @property int|null $backend
 *
 * @property Backend $backend0
 * @property Cases $cases0
 * @property User $login0
 */
class ListProgressCases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_progress_cases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['backend', 'status', 'feedback'], 'required', 'message' => ''],
            [['cases', 'backend'], 'integer'],
            [['feedback', 'status'], 'string'],
            [['feedback_gambar'],
                'file',
                'extensions' => 'jpg, jpeg, png', 
                'skipOnEmpty' => true,
                'maxSize' => 1024*1024*5,
                'tooBig' => 'File tidak boleh lebih dari 5 Mb',
            ],
            [['insert_date'], 'safe'],
            [['login'], 'string', 'max' => 20],
            [['backend'], 'exist', 'skipOnError' => true, 'targetClass' => Backend::className(), 'targetAttribute' => ['backend' => 'id']],
            [['cases'], 'exist', 'skipOnError' => true, 'targetClass' => Cases::className(), 'targetAttribute' => ['cases' => 'id']],
            [['login'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['login' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cases' => Yii::t('app', 'Cases'),
            'login' => Yii::t('app', 'Login'),
            'feedback' => Yii::t('app', 'Feedback'),
            'feedback_gambar' => Yii::t('app', 'Feedback Gambar'),
            'status' => Yii::t('app', 'Status'),
            'insert_date' => Yii::t('app', 'Insert Date'),
            'backend' => Yii::t('app', 'Backend'),
        ];
    }

    /**
     * Gets query for [[Backend0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBackend0()
    {
        return $this->hasOne(Backend::className(), ['id' => 'backend']);
    }

    /**
     * Gets query for [[Cases0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCases0()
    {
        return $this->hasOne(Cases::className(), ['id' => 'cases']);
    }

    /**
     * Gets query for [[Login0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogin0()
    {
        return $this->hasOne(User::className(), ['username' => 'login']);
    }
}
