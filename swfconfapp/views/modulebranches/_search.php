<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ModuleBranchesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-branches-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'module_id') ?>

    <?= $form->field($model, 'branch_id') ?>

    <?= $form->field($model, 'lockState') ?>
    
    <?= $form->field($model, 'lockLogId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
