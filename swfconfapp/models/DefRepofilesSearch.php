<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DefRepofilesSearch represents the model behind the search form of `app\models\DefRepofiles`.
 */
class DefRepofilesSearch extends DefRepofiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'module_id', 'repo_id'], 'integer'],
            [['filepath', 'flag'], 'safe'],
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
        $query = DefRepofiles::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'module_id' => $this->module_id,
            'repo_id' => $this->repo_id,
        ]);

        $query->andFilterWhere(['like', 'filepath', $this->filepath])
            ->andFilterWhere(['like', 'flag', $this->flag]);

        return $dataProvider;
    }
}
