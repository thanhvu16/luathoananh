<?php

namespace cms\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\PredictExpert;

/**
 * PredictExpertSearch represents the model behind the search form about `cms\models\PredictExpert`.
 */
class PredictExpertSearch extends PredictExpert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'count_match', 'count_win', 'count_draw', 'count_lose'], 'integer'],
            [['name', 'datas', 'updated_time'], 'safe'],
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
        $query = PredictExpert::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'count_match' => $this->count_match,
            'count_win' => $this->count_win,
            'count_draw' => $this->count_draw,
            'count_lose' => $this->count_lose,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'datas', $this->datas]);

        return $dataProvider;
    }
}
