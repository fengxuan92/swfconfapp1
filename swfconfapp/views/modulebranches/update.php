<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ModuleBranches */

$this->title = 'Update Module Branches: ' . $model->module_id;
$this->params['breadcrumbs'][] = ['label' => 'Module Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->module_id, 'url' => ['view', 'module_id' => $model->module_id, 'branch_id' => $model->branch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="module-branches-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
