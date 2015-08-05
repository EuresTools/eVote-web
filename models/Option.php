<?php

namespace app\models;

use \app\models\query\OptionQuery;

class Option extends \app\models\base\OptionBase
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
     * @return OptionQuery
     */
    public static function find()
    {
        return new OptionQuery(get_called_class());
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
