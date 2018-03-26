<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * ModuleSearch represents the model behind the search form of `app\models\Module`.
 */
class ModuleForm extends Module
{
	/**
	 * @inheritdoc
	 */
	public $repos;
	public $files;
	public $project_name;

	public function rules()
	{
		return [
				[['name', 'project_id','repos'], 'required'],
				[['id', 'project_id'], 'integer'],
				[['name','project_name', 'repos', 'files'],  'safe'],
				// here add attributes rules from Ressource model
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



	public function form($params)
	{
		$query = Module::find()->innerJoinWith('project', true)->rightJoin('repo','project.id=repo.project_id');

		$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'sort' => ['attributes' => ['id', 'name', 'project_id', 'project_name'=>['asc'=>['project.name'=>SORT_ASC],'desc'=>['project.name'=>SORT_DESC]]]]
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
				'module.id' => $this->id,
				'project.id' => $this->project_id,
				//'project.name' => $this->project_name,
				'repo.name'=>$this->repos,
		]);

		$query->andFilterWhere(['like', 'project.name', $this->project_name])->andFilterWhere(['like', 'module.name', $this->name])
		->andFilterWhere(['like', 'repo.name', $this->repos]);

		//print_r($dataProvider);
		return $dataProvider;

	}
}
