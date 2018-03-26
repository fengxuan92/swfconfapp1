<?php

use app\models\Tasktemplate;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tasktemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasktemplate-form">

    <?php $form = ActiveForm::begin(
    		[
    		 'id' => 'login-form',
    		 'options' => ['class' => 'form-horizontal'],
    		 'fieldConfig' => [
    			'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
    			'labelOptions' => ['class' => 'col-lg-1'],
    			],
    		]); ?>

    <?= Html::activeHiddenInput($model,'id') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20,"style"=>"width:421px; height:30px;"]) ?>

    <?= $form->field($model, 'template_type')->dropDownList(['PULLREQUEST_CREATE' => 'PULLREQUEST_CREATE', 'CODE_PUSH' => 'CODE_PUSH',]); ?>

    <?= $form->field($model, 'Validation_type')->dropDownList(['MERGE_BEFORE' => 'MERGE_BEFORE', 'TIMER' => 'TIMER','NA' => 'NA']);?>

    <?= $form->field($model, 'init_param')->textArea(['rows' => '4']) ?>

    <?php /*= $form->field($model, 'due_time')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
         'autoclose' => true
        ]
      ]);*/?>
     <?= $form->field($model, 'due_time')->input('due_time',['type'=>'number','step'=>10])?>
     <?php //= $form->field($model, 'due_time')->textInput(['maxlength' => 400,"style"=>"width:585px; height:30px;"])?>    
    
    <?= $form->field($model, 'trigger_url')->textInput(['maxlength' => 400,"style"=>"width:585px; height:30px;"]) ?>

    <?= $form->field($model, 'check_url')->textInput(['maxlength' => 400,"style"=>"width:585px; height:30px;"]) ?>

    <?= Html::activeHiddenInput($model, 'result_code') ?>

    <?php
        $taskObjs = Tasktemplate::find()->all();
        $allTemplate = ArrayHelper::map($taskObjs, 'id', 'name');
    ?>

    <?=  $form->field($model, 'prereq_tasks_array',[
            'template' => '{label}<div class="row"><div class="col-sm-4">{input}{error}{hint}</div></div>',
               ])->widget(Select2::classname(), [
     		    'data' => $allTemplate,
      		    'options' => [
      		    'multiple'=>true,
      		     ],
     ]);
     ?>
     
     <?=  $form->field($model, 'conseq_template_array',[
            'template' => '{label}<div class="row"><div class="col-sm-4">{input}{error}{hint}</div></div>',
                ])->widget(Select2::classname(), [
     		      'data' => $allTemplate,
      		      'options' => [
      		      'multiple'=>true,
      		      ],
     ]);
     ?>

    <?= $form->field($model, 'manual_close')->radioList(['Y'=>'Y','N'=>'N'])?>

    <?= Html::activeHiddenInput($model,'manual_reason') ?>

    <?= Html::activeHiddenInput($model,'user_name') ?>

    <?= $form->field($model, 'mandatory')->radioList(['Y'=>'Y','N'=>'N'])?>

    <?= Html::activeHiddenInput($model, 'project_id') ?>

    <?= Html::activeHiddenInput($model, 'frombranch_id') ?>

    <?= Html::activeHiddenInput($model, 'tobranch_id') ?>

   <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>