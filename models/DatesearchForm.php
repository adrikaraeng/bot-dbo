<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class DatesearchForm extends Model
{
    public $tglMulai;
    public $tglAkhir;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['tglMulai', 'tglAkhir'], 'required', 'message'=>''],
        ];
    }
    public function attributeLabels()
    {
        return [
            'tglMulai' => Yii::t('app', 'Start Date'),
            'tglMulai' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status')
        ];
    }
}