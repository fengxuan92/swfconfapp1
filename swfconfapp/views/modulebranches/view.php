<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ModuleBranches */

$this->title = $model->module_id;
$this->params['breadcrumbs'][] = ['label' => 'Module Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-branches-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'module_id' => $model->module_id, 'branch_id' => $model->branch_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'module_id' => $model->module_id, 'branch_id' => $model->branch_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'module_id',
            'branch_id',
            'lockState',
            'lockLogId',
        ],
    ]) ?>

</div>
