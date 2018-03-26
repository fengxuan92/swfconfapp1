<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TasktemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasktemplates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasktemplate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tasktemplate', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'template_type',
            'Validation_type',
            'init_param',
            //'trigger_url:url',
            //'check_url:url',
            //'result_code',
            //'conseq_template',
            //'prereq_tasks',
            //'manual_close',
            //'manual_reason',
            //'due_time',
            //'user_name',
            //'mandatory',
            //'project_id',
            //'frombranch_id',
            //'tobranch_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
