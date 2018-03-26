<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tasktemplate */

$this->title = 'Create Tasktemplate';
$this->params['breadcrumbs'][] = ['label' => 'Tasktemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasktemplate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
