<?php

namespace app\models;

use \app\models\query\VoteOptionQuery;

class VoteOption extends \app\models\base\VoteOptionBase
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
     * @return VoteOptionQuery
     */
    public static function find()
    {
        return new VoteOptionQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }
}
