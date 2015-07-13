<?php

namespace app\models;

use Yii;
use \app\models\query\UserQuery;

class User extends \app\models\base\UserBase implements \yii\web\IdentityInterface
{
    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        return ['username'];
    }

    /**
     * @inheritdoc
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /* IdentityInterface */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function isOrganizer()
    {
        return $this->getOrganizer()->exists();
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    protected function setNewPassword($new_password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($new_password);
    }
}
