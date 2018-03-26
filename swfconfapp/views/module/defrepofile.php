<?php

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'defrepofile' . $formmodel->name;
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index', 'id' => $modulemodel->project_id]];

$this->params['breadcrumbs'][] = 'Defrepofile';
?>
<div class="module-form">
	<?php $form = ActiveForm::begin([
			'id' => 'module-form',
			'options' => ['class' => 'form-horizontal','data' => ['pjax' => true]],
			'enableAjaxValidation' => true]);
	?>

	<?=$form->field($projectmodel, 'name')->textInput(['maxlength' => true, 'readonly' => true]); ?>
    <?= $form->field($modulemodel, 'name')->textInput(['maxlength' => true, 'readonly' => true]) ?>
	<?php
		echo $form->field($formmodel, 'repos')->widget(Select2::classname(), [
			'data' => $repos,
				'options' => ['id'=>'repodrop','placeholder'=>'Select the repo','value'=>$defmodel->repo_id],
		]);
	?>
    <?= $form -> field($defmodel, 'filepath')->textInput(['maxlength' => true,'id'=>'pathid'])?>

  	<?php
  		$flags=array('D'=>'D','F'=>'F');
  		echo $form->field($defmodel, 'flag')->widget(Select2::classname(), [
			//'value'=> $formmodel->repos,
			'data' => $flags,
  				'options' => ['id'=>'flagdrop', 'placeholder'=>'Select it is Directory or File', 'value'=>$defmodel->flag],
		]);
  	?>

  	<?php //echo $form->field($defmodel, 'flag')->textInput(['type'=>'radiolist',])->radioList(['0'=>'D','1'=>'F'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline','id'=>'defrepofiles-flag']]]);?>


   	<?php ActiveForm::end(); ?>

	<?php
	if ($defmodel->id){
		echo Html::Button('Update',
				[
						'class' => 'btn btn-primary',
						'onclick' => '$.ajax({url: "' . Url::to(["module/addef",'file_id'=>$defmodel->id]) . '",type: "POST",data: {"module_id":'.$modulemodel->id.',"repo":$("#repodrop").val(),"filepath":$("#pathid").val(),"flag":$("#flagdrop").val(),}})',
				]);
	}
	else{
		echo Html::Button('Save',
				[
						'class' => 'btn btn-primary',
						'onclick' => '$.ajax({url: "' . Url::to(["module/addef",'id'=>$modulemodel->id]) . '",type: "POST",data: {"module_id":'.$modulemodel->id.',"repo":$("#repodrop").val(),"filepath":$("#pathid").val(),"flag":$("#flagdrop").val(),}})',
				]);
	}
	?>

	<?php
	if ($defmodel->id){
		$button = Html::Button('Delete',['class' => 'btn btn-primary']);
		echo Html::a("$button", Url::to(['module/deletedef','file_id'=>$defmodel->id]));
	}
	?>


</div>
