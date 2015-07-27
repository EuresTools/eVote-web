<?php

namespace app\models\base;

use Yii;
use app\models\Code;
use app\models\Contact;
use app\models\User;
use app\models\Poll;

/**
* This is the model class for table "member".
*
* @property integer $id
* @property string $name
* @property string $group
* @property integer $poll_id
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property Code[] $codes
    * @property Contact[] $contacts
    * @property User $updatedBy
    * @property User $createdBy
    * @property Poll $poll
    */
class MemberBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%member}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Members} =1{Member} other{Members}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['name', 'poll_id'], 'required'],
            [['poll_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'group'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['name', 'group', '!poll_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'group' => Yii::t('app', 'Group'),
            'poll_id' => Yii::t('app', 'Poll ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {
            Code::generateCode($this->poll_id, $this->id)->save();
        }
    }

    public function beforeDelete()
    {
        if(parent::beforeDelete()) {
            Contact::deleteAll('member_id = :member_id', [':member_id' => $this->id]);
            foreach ($this->codes as $code) {
                if ($code->delete() === false) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCodes()
    {
        return $this->hasMany(Code::className(), ['member_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['member_id' => 'id']);
    }


    public function getContactsCount()
    {
        return $this->getContacts()->count();
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
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }
}
