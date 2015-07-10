<?php

namespace app\models\base;

use Yii;
use app\models\Code;
use app\models\Contact;
use app\models\Member;
use app\models\Option;
use app\models\Organizer;
use app\models\Poll;
use app\models\User;
use app\models\Vote;

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
* @property integer $created_by
* @property integer $updated_by
*
    * @property Code[] $codes
    * @property Contact[] $contacts
    * @property Member[] $members
    * @property Option[] $options
    * @property Organizer[] $organizers
    * @property Poll[] $polls
    * @property User $updatedBy
    * @property User[] $users
    * @property User $createdBy
    * @property Organizer $organizer
    * @property Vote[] $votes
    */
class UserBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Users} =1{User} other{Users}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['is_admin', 'organizer_id', 'created_by', 'updated_by'], 'integer'],
            [['username', 'password_hash', 'auth_key'], 'string', 'max' => 255],
            [['username'], 'unique']
        ];
    }

    public function scenarios() {
        return [
            'default' => ['username', 'is_admin'],
            'login' => ['username', 'password_hash'],
            'register' => ['username', 'password_hash'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'is_admin' => Yii::t('app', 'Is Admin'),
            'organizer_id' => Yii::t('app', 'Organizer'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCodes()
    {
        return $this->hasMany(Code::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOptions()
    {
        return $this->hasMany(Option::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganizers()
    {
        return $this->hasMany(Organizer::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPolls()
    {
        return $this->hasMany(Poll::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['created_by' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'organizer_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['created_by' => 'id']);
    }
}
