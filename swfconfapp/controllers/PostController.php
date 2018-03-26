<?php

namespace app\controllers;

use app\models\Branch;
use app\models\Module;
use app\models\ModuleBranches;
use app\models\ModuleRepos;
use app\models\Repo;
use Yii;

class PostController extends \yii\web\Controller {

	public function actionBranchOfProject() {
		$id = Yii::$app->request->post('id');
		$rows = Branch::find()->where(['project_id' => $id])->all();
		echo "<option>Select Branch</option>";
		if(count($rows)>0){
			foreach($rows as $row){
				echo "<option value='$row->id'>$row->name</option>";
			}
		}
	}

	public function actionModuleOfProject(){
		$project_id = Yii::$app->request->post('project');
		$branch_id = Yii::$app->request->post('branch');
		$records = [];
		if ($project_id  && $branch_id ){
			if ($project_id != 'Select Project' && $branch_id != 'Select Branch'){
				$rows = Module::find()->where(['project_id' => $project_id])->all();
				if(count($rows)>0){
					\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return $rows;
				}
			}
		}
	}

	/*module of branch*/
	public function actionModuleOfBranch(){
		$branch_id = Yii::$app->request->post('branch');
		$records = [];
		if ( $branch_id ){
			$rows = ModuleBranches::find()->where(['branch_id' => $branch_id])->all();
			if(count($rows)>0){
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return $rows;
			}

		}
	}
	/*module create: when you select a project, next to write a module name. We need to add id, name, project_id to table 'module' */
	public function actionModuleaddOfProject(){
		$project_id  = Yii::$app->request->post('id');
		//$records = [];
		/* $rows = Repo::find()->where(['project_id' => $project_id])->all();
		$reponame=array();
		foreach ($rows as $repo){
			$reponame[$repo->id]=$repo->name;
		} */
		if ($project_id){
			/* $rows = Project::find()->where(['project_id' => $project_id])->all();
			if(count($rows)>0){
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return $rows;
			} */
			/* return $this->render('module_form',[
					'project_id'=>project_id,
					'reponame'=>$reponame,
			]); */
			return $project_id;
		}
	}

	public function actionModulerepoOfProject(){
		$project_id  = Yii::$app->request->post('id');
		$repos=new Repo;
		$reponame=$repos->getRepos();
		$rows = Repo::find()->where(['project_id' => $project_id])->all();
		$reponame=array();
		foreach ($rows as $repo){
			$reponame[$repo->id]=$repo->name;
		}
		if ($project_id){
			/* $rows = Project::find()->where(['project_id' => $project_id])->all();
			 if(count($rows)>0){
			 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			 return $rows;
			 } */
			/* return $this->render('module_form',[
					'project_id'=>project_id,
					'reponame'=>$reponame,
			]); */
			return $reponame;
		}
	}
	/*moshow*/
	public function actionModuleOfShow(){
		$projectid = Yii::$app->request->post('id');
		$rows = Module::find()->where(['project_id' => $projectid])->all();
		$modules=array();
		if($projectid){
			foreach ($rows as $row){
				$modules[$row->id]=$row->name;
			}
			if(count($modules)){

				return $modules;
			}
		}

		//echo "<input type='submit' value='submit'>";
		//echo Html::a('Show all Module', ['module/show', 'id' => $projectmodel->id], ['class' => 'btn btn-primary']);
		//echo "<option>Select Branch</option>";
		/* if(count($rows)>0){
			foreach($rows as $row){
				echo "<option value='$row->id'>$row->name</option>";
			}
		} */
	}
	/*_form*/
	public function actionRepoOfProject() {
		$proid = Yii::$app->request->post('id');
		$string='';
		$repotrop=array();
		$module=new Module;
		if($proid){

			$rows = Repo::find()->where(['project_id' => $proid])->all();

			 if(count($rows)>0){
				foreach($rows as $row){
					//$string=$string . ' '.$row->name;

					$repotrop[$row->id]=$row->name;

					$str=$row->name;
					echo "<option value='$row->id'> $str </option>";
				}
			}
		}
	}
	/*repo save*/
	public function actionRepoSave(){
		$repoid=Yii::$app->request->post('id');

	}
	/*_form mredrop*/
	public function actionModuleOfRepo() {
		$reponame= Yii::$app->request->post('mre');
		$repoArr=array();
		$mourepo=new ModuleRepos();
		$repotrop=new Repo();
		if($reponame){
			foreach ($reponame as $repo){
				$repoArr[]=$repotrop->getRepos();

			}
			//$rows = Repo::find()->where(['name' => $])->all();

			if(count($rows)>0){
				foreach($rows as $row){
					$repotrop[$row->id]=$row->name;

					$str=(string)$row->name;
					echo "<option value='$row->id'> $str </option>";
				}
				return $this->render(['_form',[
						'id'=>$id,
						'repos'=>$repotrop,
				]

				]);
			}
		}
	}
	public function actionShowStatus(){
		$project_id = Yii::$app->request->post('project_id');
		$branch_id = Yii::$app->request->post('branch_id');
		$result = array();
		$project_name = Yii::$app->db->createCommand("SELECT name FROM project WHERE id=$project_id")->queryScalar();
		$branch_name = Yii::$app->db->createCommand("SELECT name FROM branch WHERE id=$branch_id")->queryScalar();
		$result['project']=$project_name;
		$result['branch']=$branch_name;
		$modules = array();
		$rows = Module::find()->where(['project_id' => $project_id])->all();
		foreach($rows as $row){
			$module = array();
			$module['module_name'] = $row->name;
			$repos = array();
			#$row2s = Yii::$app->db->createCommand("SELECT * FROM repo WHERE project_id=$project_id AND id IN (SELECT repo_id FROM module_repos WHERE module_id=$row->id)")->queryAll();
			$repo_ids = Yii::$app->db->createCommand("SELECT repo_id FROM module_repos WHERE module_id=$row->id")->queryAll();
			$ids = array();
			foreach($repo_ids as $repo_id){
				$ids[]=$repo_id['repo_id'];
			}
			#$repo_ids = ModuleRepos::find('repo_id')->where(['module_id' => $row->id])->all();
			$row2s = Repo::find()->where(['id' => $ids])->all();
			foreach($row2s as $row2){
				$repo = array();
				$repo['repo_name'] = $row2->name;
#��ѯrepo��״̬
				$repo['repo_status'] = 'L';
				$repos[]=$repo;
			}
			$module['module_repos'] = $repos;
			#$module['ids'] = $repo_ids;
			$modules[] = $module;
		}
		$result['modules'] = $modules;
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $result;
	}
}
