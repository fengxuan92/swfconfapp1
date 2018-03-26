<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Project;
/* @var $this yii\web\View */
/* @var $model app\models\Projectsetting */
$projectName = Yii::$app->request->get ( 'project' );
if (! isset ( $projectName )) {
	$projectName = Project::findOne ( [ 'id' => $project_id ] );
	$projectName = $projectName->name;
}
$this->title = $model->project_id;
$this->params['breadcrumbs'][] = ['label' => 'Projectsettings', 'url' => ['index','project_id'=>$project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectsetting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'project_id' => $model->project_id, 'settingkey' => $model->settingkey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'project_id' => $model->project_id, 'settingkey' => $model->settingkey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'project_id',
            'settingkey',
            'settingval',
        ],
    ]) ?>

</div>
