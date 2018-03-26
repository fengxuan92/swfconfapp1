<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tasktemplate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tasktemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasktemplate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'name',
            'template_type',
            'Validation_type',
            'init_param',
            'trigger_url:url',
            'check_url:url',
            'result_code',
        	'prereq_tasks',
            'conseq_template',           
            'manual_close',
            'manual_reason',
            'due_time',
            'user_name',
            'mandatory',
            'project_id',
            'frombranch_id',
            'tobranch_id',
        ],
    ]) ?>

</div>
