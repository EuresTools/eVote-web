<?php


namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Code;

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

    public function valid() {
        $this->andWhere(['in', 'code_status', [Code::CODE_STATUS_UNUSED, Code::CODE_STATUS_USED]]);
        return $this;
    }


    public function unused() {
        $this->andWhere(['code_status' => Code::CODE_STATUS_UNUSED]);
        return $this;
    }

    public function used() {
        $this->andWhere(['code_status' => Code::CODE_STATUS_USED]);
        return $this;
    }

    public function invalid() {
        $this->andWhere(['code_status' => Code::CODE_STATUS_INVALID]);
        return $this;
    }

    public function poll_searchOptions($poll_searchOptions = [])
    {
        if (is_array($poll_searchOptions) && sizeof($poll_searchOptions)) {
            $this->andWhere($poll_searchOptions);
        }
        return $this;
    }

    public function poll_id($poll_id = null)
    {
        if ($poll_id) {
            $this->andWhere(['poll_id' => $poll_id]);
        }
        return $this;
    }
}
