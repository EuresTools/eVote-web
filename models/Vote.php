<?php

namespace app\models;

use \app\models\query\VoteQuery;

class Vote extends \app\models\base\VoteBase
{
    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        return null;
    }

    /**
     * @inheritdoc
     * @return VoteQuery
     */
    public static function find()
    {
        return new VoteQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }

    public function getOrganizerId()
    {
        $poll = $this->getPoll()->one();
        return isset($poll) ? $poll->organizer_id : null;
    }
}
