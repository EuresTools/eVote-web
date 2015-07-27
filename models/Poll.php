<?php

namespace app\models;

use Yii;
use \app\models\query\PollQuery;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class Poll extends \app\models\base\PollBase
{
    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        return 'title';
    }

    /**
     * @inheritdoc
     * @return PollQuery
     */
    public static function find()
    {
        return new PollQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'organizer_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->getOrganizer()->one()->getPrimaryKey();
                },
            ],
         ]);
    }

    public function isOver() {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $endTime = new \DateTime($this->end_time, new \DateTimeZone('UTC'));
        return $now >= $endTime;
    }

    public function isOpen() {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $startTime = new \DateTime($this->start_time, new \DateTimeZone('UTC'));
        $endTime = new \DateTime($this->end_time, new \DateTimeZone('UTC'));
        return $now >= $startTime && $now < $endTime;
    }
}
