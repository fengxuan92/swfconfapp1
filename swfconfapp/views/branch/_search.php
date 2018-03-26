<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BranchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'limit_fix_versions') ?>

    <?= $form->field($model, 'limit_jira_ids') ?>

    <?php // echo $form->field($model, 'lockState') ?>

    <?php // echo $form->field($model, 'lockLogId') ?>

    <?php // echo $form->field($model, 'allow_user') ?>

    <?php // echo $form->field($model, 'owner') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
