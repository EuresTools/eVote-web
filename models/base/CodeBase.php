<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Member;
use app\models\Poll;
use app\models\Vote;

/**
* This is the model class for table "code".
*
* @property integer $id
* @property string $token
* @property integer $poll_id
* @property integer $member_id
* @property integer $code_status
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property User $updatedBy
    * @property User $createdBy
    * @property Member $member
    * @property Poll $poll
    * @property Vote $votes
    */
class CodeBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%code}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Codes} =1{Code} other{Codes}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['token', 'poll_id', 'member_id'], 'required'],
            [['poll_id', 'member_id', 'code_status', 'created_by', 'updated_by'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique']
        ];
    }

    public function scenarios() {
        return [
            'default' => ['token', 'code_status', '!poll_id', '!member_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'token' => Yii::t('app', 'Token'),
            'poll_id' => Yii::t('app', 'Poll ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'code_status' => Yii::t('app', 'Is Valid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVote()
    {
        //return $this->hasMany(Vote::className(), ['code_id' => 'id']);
        return $this->hasOne(Vote::className(), ['code_id' => 'id']);
    }
}
