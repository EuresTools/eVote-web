<?php

namespace app\models;

use Yii;
use \app\models\query\PollQuery;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use app\models\Member;
use app\models\Contact;
use app\models\Code;
use app\models\Vote;

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

    public function isOver()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $endTime = new \DateTime($this->end_time, new \DateTimeZone('UTC'));
        return $now >= $endTime;
    }

    public function hasStarted()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $startTime = new \DateTime($this->start_time, new \DateTimeZone('UTC'));
        return $now >= $startTime;
    }

    public function isOpen()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $startTime = new \DateTime($this->start_time, new \DateTimeZone('UTC'));
        $endTime = new \DateTime($this->end_time, new \DateTimeZone('UTC'));
        return $now >= $startTime && $now < $endTime;
    }

    /*
    Deletes all Member Data of the poll including contacts, votes and codes
    */
    public function deleteMemberData()
    {
        // Delete all existing members.
        // get member ids to delete
        $memberIds=ArrayHelper::getColumn($this->members, 'id');
        if (sizeof($memberIds) > 0) {
            // without members there should be no contact etc.

            // delete contacts by member_id
            Contact::deleteAll(['member_id' => $memberIds]);

            /*
            $codes = app\models\Code::find()
                ->select('id')
                //->where(['poll_id' => $this->id, 'member_id' => $memberIds]) // member_id not required because i will delete all anyhow?
                ->where(['poll_id' => $this->id])
                ->asArray()
                ->all();
            $codeIds = ArrayHelper::getColumn($codes, 'id');
            */

            // alternative also over the codes relation depends on what is "faster"
            $codeIds=ArrayHelper::getColumn($this->codes, 'id');

            // delete the votes by the code_id
            Vote::deleteAll(['code_id'=> $codeIds]);

            // delete the Codes by poll_id and member_id
            //Code::deleteAll(['poll_id'=> $this->id, 'member_id' => $memberIds]);
            Code::deleteAll(['poll_id'=> $this->id]); // member_id not required because i will delete all anyhow?

            // also delete member entries by id
            Member::deleteAll(['id' => $memberIds]);
            return true;
        }
    }
}
