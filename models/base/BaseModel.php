<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class BaseModel extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // 'createdAtAttribute' => 'create_time',
                // 'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
