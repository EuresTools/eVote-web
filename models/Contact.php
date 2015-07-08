<?php

namespace app\models;

use \app\models\query\ContactQuery;

class Contact extends \app\models\base\ContactBase
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
     * @return ContactQuery
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
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
