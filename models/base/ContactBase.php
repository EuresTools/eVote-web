<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Member;

/**
* This is the model class for table "contact".
*
* @property integer $id
* @property string $name
* @property string $email
* @property integer $member_id
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property User $updatedBy
    * @property User $createdBy
    * @property Member $member
    */
class ContactBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Contacts} =1{Contact} other{Contacts}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['member_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'email'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['name', 'email', '!member_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'member_id' => Yii::t('app', 'Member ID'),
        ]);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
}
