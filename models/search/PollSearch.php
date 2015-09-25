<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\behaviors\RememberFiltersBehavior;
use app\models\Poll;

/**
 * PollSearch represents the model behind the search form about `app\models\Poll`.
 */
class PollSearch extends Poll
{
    public function rules()
    {
        return [
            [['id', 'select_min', 'select_max', 'organizer_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'question', 'start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        //return Model::scenarios();
        if (\Yii::$app->user->isAdmin()) {
            return ['default' => ['id', 'select_min', 'select_max', 'start_time', 'end_time', 'organizer_id', 'title', 'question']];
        } else {
            return ['default' => ['id', 'select_min', 'select_max', 'start_time', 'end_time', 'title', 'question']];
        }
    }

    public function behaviors()
    {
        return [
           RememberFiltersBehavior::className(),
        ];
    }

    public function search($params)
    {
        $query = Poll::find();

        if (\Yii::$app->user->identity) {
            if (!\Yii::$app->user->identity->isAdmin()) {
                $query->organizer_id(Yii::$app->user->identity->organizer_id);
            }
        }

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
            'select_min' => $this->select_min,
            'select_max' => $this->select_max,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'organizer_id' => $this->organizer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'question', $this->question]);

        return $dataProvider;
    }
}
