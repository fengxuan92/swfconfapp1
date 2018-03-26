<?php
use app\models\Project;
use app\models\Roles;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$projectName = Yii::$app->request->get ( 'project' );
if (! isset ( $projectName )) {
  $projectName = Project::findOne ( [ 'id' => $project_id ] );
  $projectName = $projectName->name;
}
$this->title = Yii::t ( 'app', "Status of $projectName branches" );
$this->params ['breadcrumbs'] [] = $this->title;
$myformatter = new app\models\LockStatusFormatter ();
?>

 <div class="branch-index">

  <ul class="nav nav-tabs">  
   <li><a href=<?php echo Url::to(['branch/index','project_id'=>$project_id]) ?> >Branch Lock</a></li>
   <li><a href=<?php echo Url::to(['task/index','project_id'=>$project_id]) ?>>Tasks</a></li>
   <li><a href="#Branch Diagram">Branch Diagram</a></li>
   <li><a href="#Module Define">Module Define</a></li> 
   <li><a href=<?php echo Url::to(['projectsetting/index','project_id'=>$project_id]) ?>>ProjectSetting</a></li>
  </ul>

  <h1><?= Html::encode($this->title) ?></h1>

 </div>
    <div>
    	<?php
    	$admin_ids = array("481","662");
    	if (in_array(Yii::$app->user->id, $admin_ids)){
    		echo Html::a(Yii::t('app', 'Set Roles'), ['project/set_admin','id'=>$project_id], ['target'=>'_blank','class' => 'btn btn-primary']);
    	}
    	?>
        <?php
        $project_role = Roles::find()->where(['project_id' => $project_id])->andWhere(['role' => 'admin'])->one();
        if (is_null($project_role)){
          $admins = array();
          $project_admins=array();
        } else {
          $admins_str = $project_role->user_ids;
          $project_admins = explode(";", $admins_str);
        }
        if (in_array(Yii::$app->user->id, $project_admins)){
          echo Html::a(Yii::t('app', 'Project modules'), ['module/index','id'=>$project_id], ['target'=>'_blank','class' => 'btn btn-primary pull-right']);
        }/*else{
				return false;
			}	*/
        ?>
    </div>
    <div>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => $myformatter,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' =>'lockState',
                'format'=>'LockStatus',
                'label'=>'Branch Status',
                'contentOptions' => [
                    'style' => 'padding:1px;text-align:center',
                ],
            ],
            [
                'attribute' => 'limit_fix_versions',
                'label'=>'Fix Version',
            ],
            [
                'attribute' => 'allow_user',
                'format'=>'AllowedUsers',
                'label'=>'Allowed User',
            ],
            [
                'attribute' => 'owner',
                'label'=>'Branch Owner',
            ],
            [
                'class' => 'app\models\BranchStatusColumn',
                'attribute' => 'lockState',
                'label' => 'Branch Detail',
            ],
            [ 'class' => 'yii\grid\ActionColumn',
              'template' => '{update}',
              'buttons'  => [
              'update' => function($url,$model,$key){
                   return $model->id > 0 ? Html::a('EDIT',['swfconf/indexshow','id'=>$model->id],['class'=>'btn btn-default'] ):'';
                   },
               ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
