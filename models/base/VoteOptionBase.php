<?php

namespace app\models\base;

use Yii;
use app\models\Option;
use app\models\Vote;

/**
* This is the model class for table "vote_option".
*
* @property integer $id
* @property integer $vote_id
* @property integer $option_id
*
    * @property Option $option
    * @property Vote $vote
    */
class VoteOptionBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vote_option}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Vote Options} =1{Vote Option} other{Vote Options}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['vote_id', 'option_id'], 'required'],
            [['vote_id', 'option_id'], 'integer']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vote_id' => Yii::t('app', 'Vote ID'),
            'option_id' => Yii::t('app', 'Option ID'),
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVote()
    {
        return $this->hasOne(Vote::className(), ['id' => 'vote_id']);
    }
}
