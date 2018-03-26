<html>
<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Show Project';
$this->params['breadcrumbs'][] = $this->title;
?>

	<?php $form = ActiveForm::begin([
			'id' => 'ShowProject-form',
			'options' => ['class' => 'form-horizontal','data' => ['pjax' => true]],
			'enableAjaxValidation' => true]); ?>

	<?=$form->field($projectmodel, 'name')->widget(Select2::classname(), [
				'data' => $projectArr,
				'options' => ['prompt'=>'Select Project', 'class' => 'form-control','id' => 'projectdrop',
						'onchange' => '
							$.post("'.Yii::$app->urlManager->createUrl('post/module-of-show').'", {id:$(this).val()},function(data){
								$("#modulerow").html(data);});',
						]
				]);
	#echo $form->field($branchmodel, 'name')->dropdownList([],['id'=>'branchdrop', 'prompt'=>'Select Branch']);
		#$data = [2 => 'widget', 3 => 'dropDownList', 4 => 'yii3'];
		/* echo $form->field($modulemodel, 'name')->widget(Select2::classname(), [
				'data' => [],
				'options' => ['id'=>'moduledrop', 'placeholder' => 'Select module'],
		]); */


	echo Html::a('Show all Module', ['module/show','id' => 'projectdrop'], ['class' => 'btn btn-primary']);
	?>


	<?php ActiveForm::end() ?>

	<?php $form = ActiveForm::begin([
		'id' => 'ShowProject-form',
		'options' => ['class' => 'form-horizontal','data' => ['pjax' => true]],
		'enableAjaxValidation' => true]); ?>

<?=$form->field($projectmodel, 'name')->widget(Select2::classname(), [
			'data' => $projectArr,
			'options' => ['prompt'=>'Select Project', 'class' => 'form-control','id' => 'projectdrop',
					'onchange' => '
						$.post("'.Yii::$app->urlManager->createUrl('post/module-of-show').'", {id:$(this).val()},function(data){
							$("#modulerow").html(data);});',
					]
		]);
	#echo $form->field($branchmodel, 'name')->dropdownList([],['id'=>'branchdrop', 'prompt'=>'Select Branch']);
		#$data = [2 => 'widget', 3 => 'dropDownList', 4 => 'yii3'];
		/* echo $form->field($modulemodel, 'name')->widget(Select2::classname(), [
				'data' => [],
				'options' => ['id'=>'moduledrop', 'placeholder' => 'Select module'],
		]); */


echo Html::a('Show all Module', ['module/show','id' => 'projectdrop'], ['class' => 'btn btn-primary']);
?>


<?php ActiveForm::end() ?>


	<div id="status" style='margin-top:40px'>
	</div>
</html>


<script type="text/javascript" >




</script>
