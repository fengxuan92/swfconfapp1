<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Projectsetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectsetting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model,'project_id',array('value'=>$project_id)) ?>

    <?= $form->field($model, 'settingkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'settingval')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save',['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
