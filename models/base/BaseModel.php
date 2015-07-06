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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_by',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
            [
                'class' => TimestampBehavior::className(),
                // 'createdAtAttribute' => 'create_time',
                // 'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
