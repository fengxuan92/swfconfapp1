<?php

namespace app\controllers;

use app\models\DefRepofiles;
use app\models\Module;
use app\models\ModuleForm;
use app\models\ModuleRepos;
use app\models\ModuleSearch;
use app\models\Project;
use app\models\Repo;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ModuleController implements the CRUD actions for Module model.
 */
class ModuleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Module models.
     * @return mixed
     */
    public function actionIndex($id)
    {
    	$projectid=$id;
    	$project = Project::findOne(['id'=>$id]);
        $searchModel = new ModuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $projectid);

        return $this->render('index', [
        	'pid' => $id,
        	'project_name' => $project->name,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionShow($id)
    {
    	$model=Module::find()
    	->where(['project_id'=>$id])
    	->all();

    	return $this->render('show', [
    			'model' => $model,
    	]);

    }

    /**
     * Displays a single Module model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$model=$this->findModelother($id);
    	$module = Module::findOne(['id'=> $id]);
    	$repos = $module->repos;
    	$str = "";
    	if ($repos){
        	foreach ($repos as $repo){
        		$str = $str . $repo->name . " ";
        	}
    	}
    	$model->repos=$str;

    	$str_file = '';
    	$files = $module->files;
    	$str_file = "";
    	if ($files){
        	foreach ($files as $file){
        	  $str_file = $str_file . $file->repo->name.': '.$file->filepath . "<br/>";
        	}
    	}
    	$model->files=$str_file;
    	return $this->render('view', [
    			'model'=>$model,
    	]);

    }

    /**
     * Creates a new Module model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $formmodel = new ModuleForm();
        $modulemodel=new Module();
        $repomodel=new Repo();
        $modulerepomodel=new ModuleRepos();
        /*load all projects*/
        $projectmodel= Project::findOne(['id'=>$id]);
        $repomodels=Repo::find()->where(['project_id'=>$id])->all();
    	$repos=array();
    	foreach($repomodels as $modulerepo){
    		$repos[$modulerepo->id]=$modulerepo->name;
    	}
    	$formmodel->repos=$repos;
    	//return $this->redirect('');
            return $this->render('create', [
                'projectmodel' => $projectmodel,
            	'modulemodel'=>$modulemodel,
            	'repomodels'=>$repomodels,
            	'repos'=>$repos,
            	'formmodel'=>$formmodel,
            ]);
    }
    public function actionAddmodule()
    {
    	$modulemodel=new Module();
    	$projectname=Yii::$app->request->post('project');
    	$modulename=Yii::$app->request->post('module');
    	$repoids=Yii::$app->request->post('repos');

    	$projectid=Project::findOne(['name'=>$projectname])->id;
    	$modulemodel->project_id=$projectid;
    	$modulemodel->name=$modulename;
    	$moduletemp=Module::findOne(['name'=>$modulename,'project_id'=>$projectid]);
    	if (Module::findOne(['name'=>$modulename,'project_id'=>$projectid])){

    		//return $this->redirect("index?r=module/create");
    	}else{
    		$modulemodel->save();

    		$moduletemp=Module::findOne(['name'=>$modulename]);

    		$count=count($repoids);
    		echo $count;
    		for($i=0;$i<$count;$i++){
    			$modulerepomodel=new ModuleRepos();

    			$modulerepomodel->module_id=$moduletemp->id;
    			$modulerepomodel->repo_id=$repoids[$i];
    			$modulerepomodel->save();
    		}
    		return $this->redirect(['module/index','id'=>$projectid]);
    		#return $this->redirect(['create','id'=>$projectid]);
    	}
    }
    /**
     * Updates an existing Module model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id is module id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$formmodel = new ModuleForm();
    	$modulemodel=new Module();
    	$repomodel=new Repo();
    	/*load all repos*/
    	$modulerepos=ModuleRepos::find()->where(['module_id'=>$id])->all();
    	$repos=array();
    	foreach($modulerepos as $modulerepo){
    		$onerepo=Repo::findOne(['id'=>$modulerepo->repo_id]);
    		$repos[$onerepo->id]=$onerepo->name;
    	}

    	$onemodule=Module::findOne(['id'=>$id]);
    	$projectid=$onemodule->project_id;
    	$oneproject=Project::findOne(['id'=>$projectid]);
    	$projectname=$oneproject->name;
    	$modulemodel->project_id=$oneproject->id;

    	//$projectmodel->name=$projectname;
    	$modulemodel->name=Module::findOne(['id'=>$id])->name;
    	$modulemodel->id=$id;

    	$repoArr=array();
    	$repomodels=Repo::find()->where(['project_id'=>$modulemodel->project_id])->all();
    	foreach($repomodels as $repo){
    		$repoArr[$repo->id]=$repo->name;
    	}
    	$formmodel->repos=$repos;


    	return $this->render('update', [
    		'projectmodel' => $oneproject,
    		'modulemodel'=>$modulemodel,
    		'formmodel'=>$formmodel,
    		'id'=>$id,
    		'repos' => $repos,
    		'repoArr'=>$repoArr,
    	]);
    }

	 public function actionUpdatemodule($id)
    {
    	$project_id=Yii::$app->request->post('project_id');
    	$mname=Yii::$app->request->post('module');
    	$repos=Yii::$app->request->post('repos');

    	#$pid=Project::findOne(['name'=>$pname])->id;
    	$modulemodel=Module::findOne(['id'=>$id]);
    	$modulemodel->name=$mname;
    	$modulemodel->project_id=$project_id;
    	$modulemodel->save();

    	$count=count($repos);
    	$repomodels=$this->findRepomodels($modulemodel->id);
	    foreach($repomodels as $repomodel){
	    	 $repomodel->delete();
	    }
	    if($repos){
	    	foreach($repos as $repo){
	    		$modulerepo=new ModuleRepos();
	    		$modulerepo->module_id=$modulemodel->id;
	    		$modulerepo->repo_id=$repo;
	    		$modulerepo->save();
	    	}
	    }

    	//return $this->redirect(['update','id'=>$id]);
	    return $this->redirect(['module/index','id'=>$project_id]);
    }
    /**
     * Deletes an existing Module model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$pid=Module::findOne(['id'=>$id])->project_id;
        $this->findModel($id)->delete();
       /* $repomodels=$this->findRepomodels($id);
        foreach($repomodels as $repomodel){
        	$repomodel->delete();
        }*/
        return $this->redirect(['index','id'=>$pid]);
    }

    /*defRepofiles add view*/
    public function actionDefrepofile($id='', $file_id='')
    {
    	$formmodel = new ModuleForm();
    	$modulemodel=new Module();
    	$repomodel=new Repo();
    	#$defmodel=new DefRepofiles();
    	if ($file_id){
    		$defmodel=DefRepofiles::findOne(['id'=>$file_id]);
    		$onemodule=Module::findOne(['id'=>$defmodel->module_id]);
    	}
    	else{
    		$onemodule=Module::findOne(['id'=>$id]);
    		$defmodel = new DefRepofiles();
    	}
    	/*load all repos*/
    	$modulerepos=ModuleRepos::find()->where(['module_id'=>$onemodule->id])->all();
    	$repos=array();
    	foreach($modulerepos as $modulerepo){
    		$onerepo=Repo::findOne(['id'=>$modulerepo->repo_id]);
    		$repos[$onerepo->id]=$onerepo->name;
    	}


    	$projectid=$onemodule->project_id;
    	$oneproject=Project::findOne(['id'=>$projectid]);
    	$projectname=$oneproject->name;


    	return $this->render('defrepofile', [
    			'projectmodel' => $oneproject,
    			'modulemodel'=>$onemodule,
    			'formmodel'=>$formmodel,

    			'repos' => $repos,
    			'defmodel'=>$defmodel,
    	]);
    }

    /*defRepofiles add*/
    public function actionAddef($id='',$file_id='')
    {
    	if($file_id){
    		$defmodel = DefRepofiles::findOne($file_id);
    	}
    	else{
    		$defmodel=new DefRepofiles();
    	}
    	$moduleid=Yii::$app->request->post('module_id');
    	$repoid=Yii::$app->request->post('repo');
    	$filepath=Yii::$app->request->post('filepath');
    	$flag=Yii::$app->request->post('flag');

    	//$defmodel=new DefRepofiles();
    	//$defmodel->load(Yii::$app->request->post());
    	$defmodel->module_id=$moduleid;
    	$defmodel->repo_id=$repoid;



    	$defmodel->filepath=$filepath;
    	$defmodel->flag=$flag;
    	$defmodel->save();
    	return $this->redirect(['view','id'=>$moduleid]);
    	//return $this->redirect(['module/defview','file_id'=>$defmodel->id]);
    }

    /*defRepofiles view after add*/
    public function actionDefview($file_id)
    {
    	$defmodel=DefRepofiles::findOne(['id'=>$file_id]);
    	return $this->render('defview', [
    			'defmodel'=>$defmodel,
    	]);
    }


    /*delete one def_repofiles*/
    public function actionDeletedef($file_id){
    	$defrepofile = DefRepofiles::findOne(['id'=>$file_id]);
    	$module_id = $defrepofile->module_id;
    	$defrepofile->delete();
    	return $this->redirect(['view','id'=>$module_id]);


    }


    /* editrepofile */

    /**
     * Finds the Module model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Module the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelother($id)
    {
    	if (($modelother = ModuleForm::findOne($id)) !== null) {

    		//$modulerepo=new Module();
    		//$repos=$modulerepo->getRepos();
    		//$modelother->repos=$repos;
    		return $modelother;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }

    protected function findRepoid($id){//module id
    	$repos = ModuleRepos::find()->where(['module_id'=>$id])->all();
    	$model=array();

    	foreach ($repos as $repo){
    		$model[]=$repo->repo_id;

    	}
    	if( ($model)!= null){
    		return $model;
    	}else{
    		throw new NotFoundHttpException('The requested page does not exit.');
    	}
    }
    /**/
    protected function findRepo($models){
    	$model=array();

    	foreach ($models as $m){
    		$temp=Repo::find()->where(['id'=>$m])->one();
    		$model[]=$temp->name;
    	}
    	if( ($model)!= null){
    		return $model;
    	}else{
    		throw new NotFoundHttpException('The requested page does not exit.');
    	}
    }
    protected function findRepomodels($id){
    	 if (($model = ModuleRepos::find()->where(['module_id'=>$id])->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
