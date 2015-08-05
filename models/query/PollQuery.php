<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Poll;

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

    public function organizer_id($organizer_id = null)
    {
        if ($organizer_id) {
            $this->andWhere([Poll::tableName().'.organizer_id' => $organizer_id]);
        }
        return $this;
    }
}
