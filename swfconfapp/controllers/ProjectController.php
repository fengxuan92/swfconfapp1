<?php

namespace app\controllers;

use app\models\CwdUser;
use app\models\Module;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\Roles;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
	/*show Project*/
    public function actionShow() {
    	$projectmodel=new Project();
    	$modulemodel = new Module();
    	$projects = Project::find()->all();
    	$projectArr = array();
    	foreach($projects as $project){
    		$projectArr[$project->id] = $project->name;
    	}
    	#$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render ( 'show', [
    			'projectmodel' => $projectmodel,
    			'Modulemodel' => $modulemodel,
    			'projects' => $projects,
    			'projectArr' => $projectArr,

    	] );
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * set role admin of project ,to edit permission of the project
     **/
    public function actionSet_admin($id){
    	if ($_POST){
    		$project_id = $_POST['project_id'];
    		$admins = $_POST['admins'];

    		$project_role = Roles::find()->where(['project_id' => $project_id])->andWhere(['role' => 'admin'])->one();


    		if ($project_role){
    			$project_role->user_ids = implode(";", $admins);
    			$project_role->role = 'admin';
    			$project_role->save();
    		}
    		else{
    			$project_role = new Roles();
    			$project_role->project_id = $project_id;
    			$project_role->user_ids = implode(";", $admins);
    			$project_role->role = 'admin';
    			$project_role->save();
    		}
    		return $this->redirect(['view','id'=>$id]);
    	}
    	else{
	    	$oneproject=Project::findOne(['id'=>$id]);
	    	$usermodel = new CwdUser();
	    	$project_role = Roles::find()->where(['project_id' => $id])->andWhere(['role' => 'admin'])->one();
	    	if (is_null($project_role)){
	    		$admins = array();
	    	}
	    	else{
		    	$admins_str = $project_role->user_ids;
		    	$admins = explode(";", $admins_str);
	    	}

	    	return $this->render('setadmin',
	    			[
	    					'projectmodel' => $oneproject,
	    					'admins' => $admins,
	    					'usermodel' => $usermodel,
	    			]);
    	}
    }
}
