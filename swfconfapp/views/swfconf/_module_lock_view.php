<?php
use app\models\Branch;
use app\models\LockStatusFormatter;
use app\models\Log;
use yii\helpers\Html;

/* @var $this yii\web\View */
$fmt = new LockStatusFormatter();
//$form = $widget->stack[0];
?>
<td><?= $index + 1 ?></td>
<td><a target='_blank' href='index.php?r=module%2Fview&id=<?= $model->module->id ?>'><?= $model->module->name ?></a></td>
<td><?= $fmt->asLockStatus( $model->lockState ) ?></td>
<td><?php
if( isset($model->lockLogId)) {
  $log = Log::findOne(['id'=>$model->lockLogId]);
  if(is_object($log)) echo $log->action_reason;
} ?></td>
<td>
<?php
if($model->lockState != Branch::OPEN){
	echo Html::button("Open",[ 'class' =>'btn '.($model->lockState === Branch::FORCEOPEN?'btn-default': 'btn-primary'),
			'data-toggle'    => "modal",
			'data-target'    => "#reasonInputModal",
			'data-moduleid'  => $model->module_id,
			'data-titletext' => "Open Module",
			'data-submitBtnText'=>"Open",
			'data-initialtext'=>"",
			'data-initialdate'=>"",]).' ';
}
if($model->lockState != Branch::MLOCK){
	echo Html::button("Lock",[ 'class' =>'btn btn-primary',
			'data-toggle'    => "modal",
			'data-target'    => "#reasonInputModal",
			'data-moduleid'  => $model->module_id,
			'data-titletext' => "Lock Module",
			'data-submitBtnText'=>"Lock",
			'data-initialtext'=>"Lock because ....",
			'data-initialdate'=>"",]).' ';
}
if($model->lockState != Branch::FORCEOPEN){
	echo Html::button("Force Open",[ 'class' =>'btn btn-default',
			'data-toggle'    => "modal",
			'data-target'    => "#reasonInputModal",
			'data-moduleid'  => $model->module_id,
			'data-titletext' => "Force Open Module",
			'data-submitBtnText'=>"Force Open",
			'data-initialtext'=>"Force open because ....",
			'data-initialdate'=>"",]);
}
?>
</td>
