<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;


class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $desc;
    
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password', 'nama_lengkap'], 'required','message'=>''],
            [['username','inisial'],'unique'],
            [['level','status','divisi'], 'string'],
            [['accessToken','authKey'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['nama_lengkap'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'User',
            'password' => 'Password',
            'nama_lengkap' => 'Name',
            'level' => 'Level',
            'status' => 'Status',
            'divisi' => 'Divisi',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
          return static::findOne(['access_token' => $token]);
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

}
