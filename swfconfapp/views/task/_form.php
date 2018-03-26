<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Branch;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'template_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Validation_type')->textInput(['maxlength' => true]) ?>
    
    <?php //Html::activeHiddenInput($model,'init_param') ?>
    
    <?= $form->field($model, 'init_param')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trigger_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'check_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'result_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conseq_template')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prereq_tasks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manual_close')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manual_reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'due_time')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mandatory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'frombranch_id')->textInput() ?>

    <?= $form->field($model, 'tobranch_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
