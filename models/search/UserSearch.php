<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\behaviors\RememberFiltersBehavior;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $organizer;


    public function rules()
    {
        return [
            [['id', 'is_admin', 'organizer_id', 'created_by', 'updated_by'], 'integer'],
            [['username', 'password_hash', 'auth_key', 'created_at', 'updated_at'], 'safe'],
            [['organizer'], 'safe'], //relation attributes
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        //return Model::scenarios();
        return ['default' => ['username', 'is_admin', 'organizer_id']];
    }

    public function behaviors()
    {
        return [
           RememberFiltersBehavior::className(),
        ];
    }

    public function search($params)
    {
        $query = User::find()->indexBy('id');

        $query->joinWith(['organizer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $dataProvider->sort->attributes['organizer'] = [
            'asc' => ['organizer.name' => SORT_ASC],
            'desc' => ['organizer.name' => SORT_DESC],
        ];


        $query->andFilterWhere([
            'id' => $this->id,
            'is_admin' => $this->is_admin,
            'organizer_id' => $this->organizer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])

            ->andFilterWhere(['like', 'organizer.name', $this->organizer])
        ;




        return $dataProvider;
    }
}
