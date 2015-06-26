<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $is_admin
 * @property integer $organizer_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Organizer $organizer
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['is_admin', 'organizer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'auth_key'], 'string', 'max' => 255],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'is_admin' => 'Is Admin',
            'organizer_id' => 'Organizer ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'organizer_id']);
    }



    // Generate auth keys for users for cookie based login.
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password) {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /* IdentityInterface */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type=null) {
        // Intentionally empty.
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public function isOrganizer() {
        return $this->getOrganizer()->exists();
    }
}
