<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ModuleBranchesSearch represents the model behind the search form of `app\models\ModuleBranches`.
 */
class ModuleBranchesSearch extends ModuleBranches
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'branch_id'], 'integer'],
            [['lockState'], 'safe'],
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
        $query = ModuleBranches::find();

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
            'module_id' => $this->module_id,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'lockState', $this->lockState]);

        return $dataProvider;
    }
}
