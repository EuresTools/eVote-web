<?php

namespace app\models\base;

use Yii;
use app\models\Code;
use app\models\Member;
use app\models\Option;
use app\models\User;
use app\models\Organizer;

/**
* This is the model class for table "poll".
*
* @property integer $id
* @property string $title
* @property string $question
* @property string $info
* @property integer $select_min
* @property integer $select_max
* @property string $start_time
* @property string $end_time
* @property integer $organizer_id
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property Code[] $codes
    * @property Member[] $members
    * @property Option[] $options
    * @property User $updatedBy
    * @property User $createdBy
    * @property Organizer $organizer
    */
class PollBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%poll}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Polls} =1{Poll} other{Polls}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            // 'organizer_id' removed from required
            [['title', 'question', 'select_min', 'select_max', 'start_time', 'end_time'], 'required'],
            [['question', 'info'], 'string'],
            [['select_min', 'select_max', 'organizer_id', 'created_by', 'updated_by'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['start_time', 'end_time'], 'date', 'format'=>'yyyy-MM-dd kk:mm:ss'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        // admin accounts are allowed to set the organizer_id
        if (\Yii::$app->user->isAdmin()) {
            return [
                'default' => ['title', 'question', 'info', 'select_min', 'select_max', 'start_time', 'end_time', 'organizer_id'],
            ];
        } else {
            return [
                'default' => ['title', 'question', 'info', 'select_min', 'select_max', 'start_time', 'end_time', '!organizer_id'],
            ];
        }
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'question' => Yii::t('app', 'Question'),
            'info' => Yii::t('app', 'Additional Information'),
            'select_min' => Yii::t('app', 'Select Min'),
            'select_max' => Yii::t('app', 'Select Max'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'organizer_id' => Yii::t('app', 'Organizer ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            foreach ($this->codes as $code) {
                if ($code->delete() === false) {
                    return false;
                }
            }
            foreach ($this->members as $member) {
                if ($member->delete() === false) {
                    return false;
                }
            }
            Option::deleteAll('poll_id = :poll_id', [':poll_id' => $this->id]);
            //foreach ($this->options as $option) {
                //if ($option->delete() === false) {
                    //return false;
                //}
            //}
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

    public function getMembersCount()
    {
        return $this->getMembers()->count();
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
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'organizer_id']);
    }

    /*
    returns only used codes (only valid ones)
     */
    public function getUsedCodes()
    {
        return $this->getCodes()->used()->valid();
    }

    /*
    returns count of used codes (only valid ones)
    we could also say total votes count
     */
    public function getUsedCodesCount()
    {
        return $this->getUsedCodes()->count();
    }

    /*
    returns only valid codes
     */
    public function getValidCodes()
    {
        return $this->getCodes()->valid();
    }

    /*
    returns count of valid codes
    */
    public function getValidCodesCount()
    {
        return $this->getValidCodes()->count();
    }

    /*
    returns only unused codes (only valid ones)
     */
    public function getUnusedCodes()
    {
        return $this->getCodes()->unused()->valid();
    }

    /*
    returns count of valid codes (only valid ones)
    */
    public function getUnusedCodesCount()
    {
        return $this->getUnusedCodes()->count();
    }
}
