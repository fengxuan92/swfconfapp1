<html>
<body>

<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Project Lock Conditional';
$this->params['breadcrumbs'][] = ['label' => 'show project', 'url' => ['show']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
			//'id' => 'swfconf-form',
			'options' => ['class' => 'form-horizontal'],
			'enableAjaxValidation' => true]); ?>

	<?=$form->field($projectmodel, 'name')->widget(Select2::classname(), [
				'data' => $projectArr,
				'options' => ['prompt'=>'Select Project', 'class' => 'form-control','id' => 'projectdrop',
						'onchange' => '
							$.post("'.Yii::$app->urlManager->createUrl('swfconf/findjira').'", {id:$(this).val()},function(data){
								$("#projectjira").val(data);});',
						]
				]);
	?>

	<?php
 		echo $form->field($projectmodel, 'jira_keys')->textInput(['maxlength' => true,'id'=>'projectjira']);
 	?>
	<?php ActiveForm::end() ?>

    <?= Html::Button('Add Jira',
			[
					'class' => 'btn btn-primary',
					'onclick' => '$.ajax({url: "' . Url::to(["swfconf/addjira"]) . '",type: "POST",data: {"project":$("#projectdrop").val(),"jirakey":$("#projectjira").val()}})',
			]);
	?>


</html>



