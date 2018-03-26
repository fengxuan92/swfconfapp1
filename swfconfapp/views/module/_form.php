<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Module */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">
<?php
/*$this->registerJs( <<<EOT
$("button,.btn").on("click", function() {
var user_id = $("#user_id").html();
if (!user_id){
	alert("Please login first!");
	return false;
};
return true;
});
EOT
, View::POS_READY);*/
?>
	<?php $form = ActiveForm::begin([
			'id' => 'module-form',
			'options' => ['class' => 'form-horizontal','data' => ['pjax' => true]],
			'enableAjaxValidation' => true]);
	?>

    <?= $form->field($modulemodel, 'name')->textInput(['maxlength' => true,'id'=>'module']) ?>

	<?php //$url=Url::to(['post/moduleadd-of-project','id'=>]);?>

	<?=$form->field($projectmodel, 'name')->textInput(['maxlength' => true,'id'=>'pid', 'readonly' => true])?>

	<?php

	?>
	<?= $form->field($formmodel, 'repos')->widget(Select2::classname(), [
		'data' => $repos,
		'options' => ['id'=>'repodrop', 'placeholder' => $formmodel->repos,'multiple'=>true],

		/* 'onchange' => '$.post("'.Yii::$app->urlManager->createUrl('post/repo-save').'", {id:$(this).val()},function(data){
								$("#reposave").html(data);});', */
	]);

	?>

    <?php ActiveForm::end(); ?>
    <?= Html::Button('Save',
			[
					'class' => 'btn btn-success',
					'onclick' => '$.ajax({url: "' . Url::to(["module/addmodule"]) . '",type: "POST",data: {"module":$("#module").val(),"project":$("#pid").val(),"repos":$("#repodrop").val()}})',
			]);
	?>
</div>
