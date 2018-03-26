<?php

namespace app\controllers;

use Yii;
use app\models\ModuleBranches;
use app\models\ModuleBranchesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModuleBranchesController implements the CRUD actions for ModuleBranches model.
 */
class ModuleBranchesController extends Controller
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
     * Lists all ModuleBranches models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModuleBranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModuleBranches model.
     * @param integer $module_id
     * @param integer $branch_id
     * @return mixed
     */
    public function actionView($module_id, $branch_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($module_id, $branch_id),
        ]);
    }

    /**
     * Creates a new ModuleBranches model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModuleBranches();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'module_id' => $model->module_id, 'branch_id' => $model->branch_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ModuleBranches model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $module_id
     * @param integer $branch_id
     * @return mixed
     */
    public function actionUpdate($module_id, $branch_id)
    {
        $model = $this->findModel($module_id, $branch_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'module_id' => $model->module_id, 'branch_id' => $model->branch_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ModuleBranches model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $module_id
     * @param integer $branch_id
     * @return mixed
     */
    public function actionDelete($module_id, $branch_id)
    {
        $this->findModel($module_id, $branch_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ModuleBranches model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $module_id
     * @param integer $branch_id
     * @return ModuleBranches the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($module_id, $branch_id)
    {
        if (($model = ModuleBranches::findOne(['module_id' => $module_id, 'branch_id' => $branch_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
