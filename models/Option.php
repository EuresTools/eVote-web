<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "option".
 *
 * @property integer $id
 * @property string $text
 * @property integer $poll_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Poll $poll
 * @property VoteOption[] $voteOptions
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'poll_id'], 'required'],
            [['poll_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'poll_id' => 'Poll ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
