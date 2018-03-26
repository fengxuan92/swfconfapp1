<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modules';
$this->params['breadcrumbs'][] = ['label' => $project_name, 'url' => ['branch/index', 'project'=>$project_name, 'project_id'=>$pid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo yii::$app->user->identity->username; ?>

    <p>
        <?=  Html::a('Create Module', ['create','id'=>$pid], ['class' => 'btn btn-primary']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'project_id',
        	[
        		'label'=>'Project Name',
        		'attribute' => 'project_name',
        		'value' => 'project.name',

    		],
        	[
        		'label'=>'repos',
        		'attribute' => 'repos',
    			'value' => function($model){
    			    $repos = $model->repos;
    			    $str = "";
    				foreach ($repos as $repo){
    					$str = $str . $repo->name . " ";
    					}
    				return $str;
        		},

        	],
        	[
        			'label'=>'files',
        			'attribute' => 'files',
        			'format' => 'html',
        			'value' => function($model){
	        			$files = $model->files;
	        			$str = "";
	        			foreach ($files as $file){
	        				//$str = $str . $file->repo->name.': '.$file->filepath . "<br/>";
	        				$str = $str . "<a href='index.php?r=module%2Fdefrepofile&file_id=$file->id'>". $file->repo->name.': '.$file->filepath . "</a><br/>";

	        			}
	        			return $str;
        		},

        	],
            //['class' => 'yii\grid\ActionColumn'],
            [ 'class' => 'yii\grid\ActionColumn',
            	'template' => '{view} {update} {delete} {add}',
            	'buttons'=>[
            		'add' => function ($url, $model) {
		            $url = Url::to(['module/defrepofile', 'id' => $model->id]);
		            return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
		            		'title' => Yii::t('yii', 'Add file'),
		            ]);

        		}
        		]

            	/*
            	'buttons' => [
            		'add' => function ($url, $model, $key) {
            		return  Html::a('<span class="glyphicon glyphicon-plus  btn btn"></span>', ['module/defrepofile','id'=>$model->id], ['title' => 'Add file'] ) ;
        			},
        		],


        		 'buttons'  => [
        				'update' => function($url,$model,$key){
        				return $model->id > 0 ? Html::a('EDIT',['swfconf/indexshow','id'=>$model->id],['class'=>'btn btn-default'] ):'';
        			},
        		], */
        	],
        ],
    ]);
    ?>
</div>
