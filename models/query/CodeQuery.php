<?php

namespace app\models\query;

use yii\db\ActiveQuery;

/**
* This is the query class for class  "Code".
*
*/
class CodeQuery extends ActiveQuery
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

    public function project_searchOptions($project_searchOptions = [])
    {
        if (is_array($project_searchOptions) && sizeof($project_searchOptions)) {
            $this->andWhere($project_searchOptions);
        }
        return $this;
    }

    public function p_id($p_id = null)
    {
        if ($p_id) {
            $this->andWhere(['p_id' => $p_id]);
        }
        return $this;
    }
}
