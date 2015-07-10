<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\behaviors\RememberFiltersBehavior;
use app\models\Code;

/**
 * CodeSearch represents the model behind the search form about `app\models\Code`.
 */
class CodeSearch extends Code
{
    public function rules()
    {
        return [
            [['id', 'poll_id', 'member_id', 'is_valid', 'created_by', 'updated_by'], 'integer'],
            [['token', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    // public function scenarios()
    // {
    //     // bypass scenarios() implementation in the parent class
    //     return Model::scenarios();
    // }

    public function behaviors()
    {
        return [
           RememberFiltersBehavior::className(),
        ];
    }

    public function search($params)
    {
        $query = Code::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'poll_id' => $this->poll_id,
            'member_id' => $this->member_id,
            'is_valid' => $this->is_valid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
