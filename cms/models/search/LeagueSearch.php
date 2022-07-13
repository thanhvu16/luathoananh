<?php

namespace cms\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\League;

/**
 * LeagueSearch represents the model behind the search form about `cms\models\League`.
 */
class LeagueSearch extends League
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['league_id', 'type', 'created_by', 'updated_by', 'totalRound', 'currentRound', 'areaId', 'isHot', 'sort_order'], 'integer'],
            [['name','slug', 'custom_name', 'custom_short_name', 'short_name', 'sub_league_name', 'status', 'color', 'logo', 'created_time', 'updated_time', 'currentSeason', 'countryId', 'country', 'countryLogo'], 'safe'],
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
        $query = League::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>[
                'isHot' => SORT_DESC,
                'sort_order' => SORT_ASC,
                'name' => SORT_ASC,
            ]]
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'league_id' => $this->league_id,
            'type' => $this->type,
            'created_time' => $this->created_time,
            'created_by' => $this->created_by,
            'updated_time' => $this->updated_time,
            'updated_by' => $this->updated_by,
            'totalRound' => $this->totalRound,
            'currentRound' => $this->currentRound,
            'areaId' => $this->areaId,
            'isHot' => $this->isHot,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'custom_name', $this->custom_name])
            ->andFilterWhere(['like', 'custom_short_name', $this->custom_short_name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'sub_league_name', $this->sub_league_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'currentSeason', $this->currentSeason])
            ->andFilterWhere(['like', 'countryId', $this->countryId])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'countryLogo', $this->countryLogo]);

        return $dataProvider;
    }
}
