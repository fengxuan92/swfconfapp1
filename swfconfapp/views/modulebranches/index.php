<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleBranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Module Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-branches-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Module Branches', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'module_id',
            'branch_id',
            'lockState',
            'lockLogId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
