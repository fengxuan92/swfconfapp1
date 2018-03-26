<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TasktemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasktemplate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'template_type') ?>

    <?= $form->field($model, 'Validation_type') ?>

    <?= $form->field($model, 'init_param') ?>

    <?php // echo $form->field($model, 'trigger_url') ?>

    <?php // echo $form->field($model, 'check_url') ?>

    <?php // echo $form->field($model, 'result_code') ?>

    <?php // echo $form->field($model, 'conseq_template') ?>

    <?php // echo $form->field($model, 'prereq_tasks') ?>

    <?php // echo $form->field($model, 'manual_close') ?>

    <?php // echo $form->field($model, 'manual_reason') ?>

    <?php // echo $form->field($model, 'due_time') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'mandatory') ?>

    <?php // echo $form->field($model, 'project_id') ?>

    <?php // echo $form->field($model, 'frombranch_id') ?>

    <?php // echo $form->field($model, 'tobranch_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
