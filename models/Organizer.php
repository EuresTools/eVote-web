<?php

namespace app\models;

use \app\models\query\OrganizerQuery;

class Organizer extends \app\models\base\OrganizerBase
{
    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        return ['name'];
    }

    /**
     * @inheritdoc
     * @return OrganizerQuery
     */
    public static function find()
    {
        return new OrganizerQuery(get_called_class());
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
