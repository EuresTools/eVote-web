<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\behaviors\RememberFiltersBehavior;
use app\models\Member;
use app\models\Code;

/**
 * MemberSearch represents the model behind the search form about `app\models\Member`.
 */
class MemberSearch extends Member
{
    public function rules()
    {
        return [
            [['id', 'poll_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'group', 'created_at', 'updated_at', 'codes.code_status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        //return Model::scenarios();
        return ['default' => ['name', 'group', 'id', 'codes.code_status']];
    }

    public function behaviors()
    {
        return [
           RememberFiltersBehavior::className(),
        ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['codes.code_status']);
    }

    public function search($params)
    {
        $query = Member::find();
        $query->poll_id($this->poll_id);
        $query->distinct(); // also possible is to group by member.id to get only the unique member entries.
        //$query->addGroupBy($this->tableName().'.id');

        // $query->with('codes.vote'); // old join

        //$query->joinWith('codes', false, 'LEFT JOIN');
        //$query->joinWith('codes.vote', true, 'LEFT JOIN');
        // $query->joinWith(['relationname' => function ($query) {
        //     $query->from(['alias' => 'tablename']);
        // }]);
        // $query->joinWith(['codes' => function ($query) {
        //     $query->from(['codes' => 'code']);
        // }]);
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

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'group', $this->group]);

        //if code CODE_STATUS_INVALID_UNUSED is searched also search for CODE_STATUS_INVALID_USED
        if ($this->getAttribute('codes.code_status') == Code::CODE_STATUS_INVALID_UNUSED) {
            $query->andWhere([Code::tableName().'.code_status' => [Code::CODE_STATUS_INVALID_UNUSED, Code::CODE_STATUS_INVALID_USED]]);
        } else {
            $query->andFilterWhere(['=', Code::tableName().'.code_status', $this->getAttribute('codes.code_status')]);
        }
        return $dataProvider;
    }
}
