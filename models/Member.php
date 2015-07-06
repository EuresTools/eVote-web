<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $name
 * @property string $group
 * @property integer $poll_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Code[] $codes
 * @property Contact[] $contacts
 * @property Poll $poll
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'poll_id'], 'required'],
            [['poll_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'group'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'group' => 'Group',
            'poll_id' => 'Poll ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = new \yii\db\Expression('NOW()');
            }
            $this->updated_at = new \yii\db\Expression('NOW()');
            return true;
        }
        return false;
    }

    public function beforeDelete() {
        foreach($this->contacts as $contact) {
            if($contact->delete() === false) {
                return false;
            }
        }
        return parent::beforeDelete();
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }
}
