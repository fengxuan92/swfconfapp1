<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\models\Project;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CwdUser;


/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = 'Set Admin';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-form">
	<?php $form = ActiveForm::begin([
			'id' => 'setadmin-form',
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
	 <?php 
	 #var_dump($admins);
	 #var_dump($adminArr);?>
	 <?= 
	 //$usermodel->id = $admins;
	 $form->field($usermodel,'id',[
	 		'horizontalCssClasses' => ['wrapper' => 'col-sm-2',],
	      ] )->widget(Select2::classname(), [
	      'data' => ArrayHelper::map(CwdUser::find()->where(['active' => 'Y'])->indexBy('display_name')->all(),'id','display_name'),
	          'options' => [
	          	  'id' => 'adminsdrop',
	              'placeholder'=> 'Select admin of the project',
	              'multiple'=>true,
	              'value' => $admins,
	          ],
	          ])->label('Project Admins');
	 ?>
	 <?php ActiveForm::end(); ?>
	  
    <?= Html::Button('Save',
			[
					'class' => 'btn btn-primary',	
					'onclick' => '$.ajax({url: "' . Url::to(["project/set_admin",'id'=>$projectmodel->id]) . '",type: "POST",data: {"project_id":$("#projectdrop").val(),"admins":$("#adminsdrop").val()}})',
			]);			 
	?>
</div>
