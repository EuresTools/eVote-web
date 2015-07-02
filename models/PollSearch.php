<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Poll;

/**
 * PollSearch represents the model behind the search form about `app\models\Poll`.
 */
class PollSearch extends Poll
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'select_min', 'select_max', 'organizer_id'], 'integer'],
            [['title', 'question', 'start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Poll::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'select_min' => $this->select_min,
            'select_max' => $this->select_max,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'organizer_id' => $this->organizer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'question', $this->question]);

        return $dataProvider;
    }
}
