<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'limit_fix_versions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'limit_jira_ids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lockState')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lockLogId')->textInput() ?>

    <?= $form->field($model, 'allow_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'owner')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
