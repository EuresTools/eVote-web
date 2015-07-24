<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class FailedAttempt extends ActiveRecord {

    public static function tableName() {
        return '{{%failed_attempt}}';
    }

    public function rules() {
        return [
            [['ip_address'], 'required'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'time',
                'updatedAtAttribute' => 'time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

}
