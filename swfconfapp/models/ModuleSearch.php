<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * ModuleSearch represents the model behind the search form of `app\models\Module`.
 */
class ModuleSearch extends Module
{
    /**
     * @inheritdoc
     */
	public $repos;
	public $project_name;

    public function rules()
    {
        return [

        		[['id', 'project_id'], 'integer'],
        	[['name','project_name', 'repos'],  'safe'],
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



    public function search($params, $projectid)
    {

    	$query = Module::find()->innerJoinWith('project', "project.id=module.project_id")->andWhere("module.project_id=$projectid")->groupBy(['module.id']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => ['attributes' => ['id', 'name', 'project_id', 'project_name'=>['asc'=>['project.name'=>SORT_ASC],'desc'=>['project.name'=>SORT_DESC]]]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'module.id' => $this->id,
        	'project.id' => $this->project_id,
            'project.name' => $this->project_name,
        	'repo.name'=>$this->repos,
        	#'repo.id'=>'module_repos.repo_id',
        ]);
        if ($this->repos){
        	$query->rightJoin('repo','repo.project_id=project.id');
        	$query->rightJoin('module_repos','module_repos.module_id=module.id');
        	$query->andWhere('module_repos.repo_id=repo.id');
        	$query->andFilterWhere([
        		'repo.name'=>$this->repos,
        	]);

        }
        //$query->andFilterWhere(['like', 'project.name', $this->project_name])->andFilterWhere(['like', 'module.name', $this->name]);
     	//print_r($dataProvider);
        return $dataProvider;
    }
}
