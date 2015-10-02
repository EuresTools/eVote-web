<?php

namespace app\models\base;

use Yii;
use app\models\User;
use app\models\Poll;

/**
* This is the model class for table "organizer".
*
* @property integer $id
* @property string $name
* @property string $email
* @property string $created_at
* @property string $updated_at
* @property integer $created_by
* @property integer $updated_by
*
    * @property User $updatedBy
    * @property User $createdBy
    * @property Poll[] $polls
    * @property User[] $users
    */
class OrganizerBase extends \app\models\base\BaseModel
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%organizer}}';
    }

    public static function label($n = 1)
    {
        return \Yii::t('app', '{n, plural, =0{no Organizers} =1{Organizer} other{Organizers}}', ['n' => $n]);
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['email'], 'email'],
            [['created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['name', 'email'],
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
    public function getPolls()
    {
        return $this->hasMany(Poll::className(), ['organizer_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['organizer_id' => 'id']);
    }
}
