<?php
use app\models\LockStatusFormatter;
use app\models\Log;

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
</td>
