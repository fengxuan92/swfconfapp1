<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BranchSearch represents the model behind the search form of `app\models\Branch`.
 */
class BranchSearch extends Branch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'lockLogId'], 'integer'],
            [['name', 'limit_fix_versions', 'limit_jira_ids', 'lockState', 'allow_user', 'ower'], 'safe'],
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
        $query = Branch::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'lockLogId' => $this->lockLogId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'limit_fix_versions', $this->limit_fix_versions])
            ->andFilterWhere(['like', 'limit_jira_ids', $this->limit_jira_ids])
            ->andFilterWhere(['like', 'lockState', $this->lockState])
            ->andFilterWhere(['like', 'allow_user', $this->allow_user])
            ->andFilterWhere(['like', 'owner', $this->owner]);

        return $dataProvider;
    }
}
