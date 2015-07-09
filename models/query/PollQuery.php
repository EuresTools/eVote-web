<?php

namespace app\models\query;

use yii\db\ActiveQuery;

/**
* This is the query class for class  "Poll".
*
*/
class PollQuery extends ActiveQuery
{
    public function primary_key($primary_key = null)
    {
        if (is_array($primary_key)) {
            $this->andWhere($primary_key);
        } else {
            if ($primary_key) {
                $this->andWhere(['id' => $primary_key]);
            }
        }
        return $this;
    }

}
