<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Code;
use app\models\Option;
use app\models\VoteOption;

/**
* This is the model class for table "vote".
*
* @property integer $id
* @property integer $code_id
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property User $updatedBy
    * @property Code $code
    * @property User $createdBy
    * @property VoteOption[] $voteOptions
    */
class VoteBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vote}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Votes} =1{Vote} other{Votes}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['code_id'], 'required'],
            [['code_id', 'created_by', 'updated_by'], 'integer'],
            [['code_id'], 'unique']
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['!code_id'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'ID'),
            'code_id' => Yii::t('app', 'Code ID'),
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
    public function getCode()
    {
        return $this->hasOne(Code::className(), ['id' => 'code_id']);
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
    //public function getVoteOptions()
    //{
        //return $this->hasMany(VoteOption::className(), ['vote_id' => 'id']);
    //}

    public function getOptions()
    {
        return $this->hasMany(Option::className(), ['id' => 'option_id'])->viaTable('vote_option', ['vote_id' => 'id']);
    }
}
