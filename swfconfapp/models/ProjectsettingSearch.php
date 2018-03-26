<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projectsetting;

/**
 * ProjectsettingSearch represents the model behind the search form of `app\models\Projectsetting`.
 */
class ProjectsettingSearch extends Projectsetting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
            [['settingkey', 'settingval'], 'safe'],
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
        $query = Projectsetting::find();

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
            'project_id' => $this->project_id,
        ]);

        $query->andFilterWhere(['like', 'settingkey', $this->settingkey])
            ->andFilterWhere(['like', 'settingval', $this->settingval]);

        return $dataProvider;
    }
}
