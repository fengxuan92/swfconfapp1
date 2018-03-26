<?php

use app\models\Project;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'Update Module: ' . $formmodel->name;
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index', 'id' => $modulemodel->project_id]];
$this->params['breadcrumbs'][] = ['label' => $formmodel->name, 'url' => ['module/index', 'id' => $modulemodel->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="module-form">
	<?php $form = ActiveForm::begin([
			'id' => 'module-form',
			'options' => ['class' => 'form-horizontal','data' => ['pjax' => true]],
			'enableAjaxValidation' => true]);
	?>

	<?=
	$form->field($projectmodel,'name',[
	            'horizontalCssClasses' => ['wrapper' => 'col-sm-2',],
	      ] )->widget(Select2::classname(), [
	      		'data' => ArrayHelper::map(Project::find()->all(),'id','name'),
	      		'options'=>['id'=>'projectdrop', 'placeholder'=>'Select a project','value' => $projectmodel->id],
	            'pluginOptions' => [
	                    'tags' => true,
	                    'tokenSeparators' => [',', ' '],
	                    //'maximumInputLength' => 30,
	            ],
	    ]);
	 ?>




    <?= $form->field($modulemodel, 'name')->textInput(['maxlength' => true,'id'=>'mname']) ?>

	<?php
		//$modulemodel->project_id=$projectmodel->id;
		//echo $form->field($modulemodel, 'project_id')->textInput(['maxlength' => true]);
		/*->widget(Select2::classname(), [
			'data' =>$modulemodel->project_id,
			'options' => ['class' => 'form-control','id' => 'projectdrop',
			'onchange' => '$.post("'.Yii::$app->urlManager->createUrl('post/repo-of-project').'", {id:$(this).val()},function(data){
								$("#repodrop").html(data);});',
			]
		]);*/
	?>

	<?php
		$formmodel->repos=array_keys($repos);
		echo $form->field($formmodel, 'repos')->widget(Select2::classname(), [
			//'value'=> $formmodel->repos,
			'data' => $repoArr,
			'options' => ['id'=>'repodrop','multiple'=>true],
			'pluginOptions' => [
		        'tags' => true,
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		]);

	?>

    <?php ActiveForm::end(); ?>
    <?php $id=$modulemodel->id;?>

    <?= Html::Button('Save',
			[
					'class' => 'btn btn-primary',
					'onclick' => '$.ajax({url: "' . Url::to(["module/updatemodule",'id'=>$id]) . '",type: "POST",data: {"module":$("#mname").val(),"project_id":$("#projectdrop").val(),"repos":$("#repodrop").val()}})',
			]);
	?>
</div>
