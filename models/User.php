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
    private $oldAttributes = array();

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
            'password_hash' => 'Password',
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


    public function afterFind() {
        if (parent::afterFind()) {
            $this->oldAttributes = $this->attributes;
        }
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this->created_at = new \yii\db\Expression('NOW()');
            }
            // Hash the password.
            if ($this->oldAttributes->password_hash !== $this->password_hash) {
                $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
            }
            $this->updated_at = new \yii\db\Expression('NOW()');
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

    public function isAdmin() {
        return $this->is_admin;
    }
}
