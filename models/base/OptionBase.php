<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Poll;
use app\models\VoteOption;

/**
* This is the model class for table "option".
*
* @property integer $id
* @property string $text
* @property integer $poll_id
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property User $updatedBy
    * @property User $createdBy
    * @property Poll $poll
    * @property VoteOption[] $voteOptions
    */
class OptionBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%option}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Options} =1{Option} other{Options}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            //poll_id removed from 'required'],
            [['text'], 'required'],
            [['poll_id', 'created_by', 'updated_by'], 'integer'],
            [['text'], 'string', 'max' => 255]
        ];
    }

    public function scenarios() {
        return [
            'default' => ['text', '!poll_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'poll_id' => Yii::t('app', 'Poll ID'),
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
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVoteOptions()
    {
        return $this->hasMany(VoteOption::className(), ['option_id' => 'id']);
    }
}
