<?php

namespace app\models;

use \app\models\query\MemberQuery;

class Member extends \app\models\base\MemberBase
{
    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        return name;
    }

    /**
     * @inheritdoc
     * @return MemberQuery
     */
    public static function find()
    {
        return new MemberQuery(get_called_class());
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
