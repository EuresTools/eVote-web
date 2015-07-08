<?php

namespace app\models;

use \app\models\query\CodeQuery;

class Code extends \app\models\base\CodeBase
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
     * @return CodeQuery
     */
    public static function find()
    {
        return new CodeQuery(get_called_class());
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
