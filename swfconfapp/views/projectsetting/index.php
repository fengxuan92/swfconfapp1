<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectsettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$projectName = Yii::$app->request->get ( 'project' );
if (! isset ( $projectName )) {
	$projectName = Project::findOne ( [ 'id' => $project_id ] );
	$projectName = $projectName->name;
}
$this->title = 'Projectsettings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectsetting-index">

<ul class="nav nav-tabs">  
   <li><a href=<?php echo Url::to(['branch/index','project_id'=>$project_id]) ?> >Branch Lock</a></li>
   <li><a href=<?php echo Url::to(['task/index','project_id'=>$project_id]) ?>>Tasks</a></li>
   <li><a href="#Branch Diagram">Branch Diagram</a></li>
   <li><a href="#Module Define">Module Define</a></li> 
   <li><a href=<?php echo Url::to(['projectsetting/index','project_id'=>$project_id]) ?>>ProjectSetting</a></li>
 </ul>
 
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Projectsetting', ['create','project_id'=>$project_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         //  ['class' => 'yii\grid\SerialColumn'],

          //  'project_id',
            'settingkey',
            'settingval',

        	['class' => 'yii\grid\ActionColumn','template' => '{update},{delete}'],
        ],
    ]); ?>
</div>
