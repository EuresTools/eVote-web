<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\behaviors\RememberFiltersBehavior;
use app\models\Member;

/**
 * MemberSearch represents the model behind the search form about `app\models\Member`.
 */
class MemberSearch extends Member
{
    public function rules()
    {
        return [
            [['id', 'poll_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'group', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function behaviors()
    {
        return [
           //RememberFiltersBehavior::className(),
        ];
    }

    public function search($params)
    {
        $query = Member::find();
        print_pre($this->poll_id);
        $query->poll_id($this->poll_id);
        //$query->with('codes.vote');
        $query->joinWith(['codes.vote']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        //$this->validate();
        print_pre($params);
        print_pre($this->getScenario());

        $query->andFilterWhere([
            'id' => $this->id,
            Member::tableName().'.poll_id' => $this->poll_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'group', $this->group]);

        return $dataProvider;
    }
}
