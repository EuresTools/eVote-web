<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "poll".
 *
 * @property integer $id
 * @property string $question
 * @property integer $select_min
 * @property integer $select_max
 * @property string $start_time
 * @property string $end_time
 * @property integer $organizer_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Code[] $codes
 * @property Member[] $members
 * @property Option[] $options
 * @property Organizer $organizer
 */
class Poll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'start_time', 'end_time', 'organizer_id'], 'required'],
            [['question'], 'string'],
            [['select_min', 'select_max', 'organizer_id'], 'integer'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['start_time', 'end_time'], 'date'],
            // Right now these are just compared as strings...
            //['start_time', 'compare', 'compareAttribute' => 'end_time', 'operator' => '<'],
            //['end_time', 'compare', 'compareAttribute' => 'start_time', 'operator' => '>'],
            ['select_min', 'compare', 'compareValue' => 0, 'operator' => '>='],
            ['select_max', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['select_max', 'compare', 'compareAttribute' => 'select_min', 'operator' => '>='],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'select_min' => 'Minimum Selections',
            'select_max' => 'Maximum Selections',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'organizer_id' => 'Organizer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->organizer_id = $user->identity->getOrganizer()->one()->id;
                $this->created_at = new \yii\db\Expression('NOW()');
            }
            $this->updated_at = new \yii\db\Expression('NOW()');
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodes()
    {
        return $this->hasMany(Code::className(), ['poll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['poll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Option::className(), ['poll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'organizer_id']);
    }
}
