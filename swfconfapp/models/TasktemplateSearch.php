<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TasktemplateSearch represents the model behind the search form of `app\models\Tasktemplate`.
 */
class TasktemplateSearch extends Tasktemplate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'frombranch_id', 'tobranch_id'], 'integer'],
            [['name', 'template_type', 'Validation_type', 'init_param', 'trigger_url', 'check_url', 'result_code', 'conseq_template', 'prereq_tasks', 'manual_close', 'manual_reason', 'due_time', 'user_name', 'mandatory'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Tasktemplate::find();

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
            'due_time' => $this->due_time,
            'project_id' => $this->project_id,
            'frombranch_id' => $this->frombranch_id,
            'tobranch_id' => $this->tobranch_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'template_type', $this->template_type])
            ->andFilterWhere(['like', 'Validation_type', $this->Validation_type])
            ->andFilterWhere(['like', 'init_param', $this->init_param])
            ->andFilterWhere(['like', 'trigger_url', $this->trigger_url])
            ->andFilterWhere(['like', 'check_url', $this->check_url])
            ->andFilterWhere(['like', 'result_code', $this->result_code])
            ->andFilterWhere(['like', 'conseq_template', $this->conseq_template])
            ->andFilterWhere(['like', 'prereq_tasks', $this->prereq_tasks])
            ->andFilterWhere(['like', 'manual_close', $this->manual_close])
            ->andFilterWhere(['like', 'manual_reason', $this->manual_reason])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'mandatory', $this->mandatory]);

        return $dataProvider;
    }
}
