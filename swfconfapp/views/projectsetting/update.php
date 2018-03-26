<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Project;
/* @var $this yii\web\View */
/* @var $model app\models\Projectsetting */
$projectName = Yii::$app->request->get ( 'project' );
if (! isset ( $projectName )) {
	$projectName = Project::findOne ( [ 'id' => $project_id ] );
	$projectName = $projectName->name;
}
$this->title = 'Update Projectsetting';
$this->params['breadcrumbs'][] = ['label' => 'Projectsettings', 'url' => ['index','project_id'=>$project_id]];

$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projectsetting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'project_id'=>$project_id,
    ]) ?>

</div>
