<?php

namespace app\controllers;

use Yii;
use app\models\Projectsetting;
use app\models\ProjectsettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectsettingController implements the CRUD actions for Projectsetting model.
 */
class ProjectsettingController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Projectsetting models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$request = Yii::$app->request;
    	$session = Yii::$app->session;
    	$session->open();
    	if( $request->isGet ){
    		$project_id = $request->get('project_id', "-1");
    		$project = $request->get('project', "NA");
    		$session['current.project_id']=$project_id;
    		$session['current.project']=$project;
    	} else {
    		$project_id = $session['current.project_id'];
    		$project = $session['current.project'];
    	}
    	$session->close();
    	$searchModel = new ProjectsettingSearch([ 'project_id' => $project_id ]);
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        		'project_id' => $project_id,
        		'project' => $project,
        ]);
    }

    /**
     * Displays a single Projectsetting model.
     * @param integer $project_id
     * @param string $settingkey
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($project_id, $settingkey)
    {
        return $this->render('view', [
            'model' => $this->findModel($project_id, $settingkey),
        	'project_id'=>$project_id,
        ]);
    }

    /**
     * Creates a new Projectsetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        $model = new Projectsetting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'project_id' => $model->project_id, 'settingkey' => $model->settingkey]);
        }

        return $this->render('create', [
            'model' => $model,
        	'project_id'=>$project_id,
        ]);
    }

    /**
     * Updates an existing Projectsetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $project_id
     * @param string $settingkey
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($project_id, $settingkey)
    {
    	$project_id=$project_id;
        $model = $this->findModel($project_id, $settingkey);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'project_id' => $model->project_id, 'settingkey' => $model->settingkey]);
        }

        return $this->render('update', [
            'model' => $model,
        	'project_id'=>$project_id,
        ]);
    }

    /**
     * Deletes an existing Projectsetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $project_id
     * @param string $settingkey
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($project_id, $settingkey)
    {
        $this->findModel($project_id, $settingkey)->delete();

        return $this->redirect(['index','project_id'=>$project_id]);
    }

    /**
     * Finds the Projectsetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $project_id
     * @param string $settingkey
     * @return Projectsetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($project_id, $settingkey)
    {
        if (($model = Projectsetting::findOne(['project_id' => $project_id, 'settingkey' => $settingkey])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
