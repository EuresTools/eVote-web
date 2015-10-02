<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Poll;
use app\models\Vote;
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

    public function scenarios()
    {
        return [
            'default' => ['text', '!poll_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Option'),
            'poll_id' => Yii::t('app', 'Poll ID'),
        ]);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // Delete all votes associated with the option.
            foreach ($this->votes as $vote) {
                if ($vote->delete() === false) {
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
    //public function getVoteOptions()
    //{
        //return $this->hasMany(VoteOption::className(), ['option_id' => 'id']);
    //}

    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['id' => 'vote_id'])->viaTable('vote_option', ['option_id' => 'id']);
    }

    public function getVotesCount()
    {
        return $this->getVotes()->count();
    }

    public function getValidVotes()
    {
        return $this->getVotes()->joinwith([
            'code' => function ($query) {
                $query->valid()->used();
            }
        ]);
    }

    public function getValidVotesCount()
    {
        return $this->getValidVotes()->count();
    }
}
