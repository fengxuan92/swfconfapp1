<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ModuleBranches */

$this->title = 'Create Module Branches';
$this->params['breadcrumbs'][] = ['label' => 'Module Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-branches-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
