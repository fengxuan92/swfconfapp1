<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RepoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Repos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Repo', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'project_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
