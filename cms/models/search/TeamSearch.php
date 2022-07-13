<?php

namespace cms\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\Team;

/**
 * TeamSearch represents the model behind the search form about `cms\models\Team`.
 */
class TeamSearch extends Team
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teamId', 'leagueId'], 'integer'],
            [['custom_name', 'name', 'logo', 'foundingDate', 'address', 'area', 'venue', 'capacity', 'coach', 'website', 'created_time', 'updated_time'], 'safe'],
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
        $query = Team::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'teamId' => $this->teamId,
            'leagueId' => $this->leagueId,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'custom_name', $this->custom_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'foundingDate', $this->foundingDate])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'venue', $this->venue])
            ->andFilterWhere(['like', 'capacity', $this->capacity])
            ->andFilterWhere(['like', 'coach', $this->coach])
            ->andFilterWhere(['like', 'website', $this->website]);

        return $dataProvider;
    }
}
