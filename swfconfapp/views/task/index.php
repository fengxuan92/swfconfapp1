<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Project;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$projectName = Yii::$app->request->get ( 'project' );
if (! isset ( $projectName )) {
	$projectName = Project::findOne ( [ 'id' => $project_id ] );
	$projectName = $projectName->name;
}

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;

?>
  <ul class="nav nav-tabs">  
   <li><a href=<?php echo Url::to(['branch/index','project_id'=>$project_id]) ?> >Branch Lock</a></li>
   <li><a href=<?php echo Url::to(['task/index','project_id'=>$project_id]) ?>>Tasks</a></li>
   <li><a href="#Branch Diagram">Branch Diagram</a></li>
   <li><a href="#Module Define">Module Define</a></li> 
   <li><a href=<?php echo Url::to(['projectsetting/index','project_id'=>$project_id]) ?>>ProjectSetting</a></li>
  </ul>

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'template_type',
            'Validation_type',
            //'init_param',
            //'trigger_url:url',
            'check_url:url',
            //'result_code',
            //'conseq_template',
            //'prereq_tasks',
            //'manual_close',
            //'manual_reason',
            //'due_time',
            //'user_name',
            //'mandatory',
            //'project_id',
            //'frombranch_id',
            //'tobranch_id',

            ['class' => 'yii\grid\ActionColumn',
            		'template' => '{view}'
        ],
       ],
    ]); ?>
</div>
