<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'Create Module';
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index','id'=>$projectmodel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    		'projectmodel' => $projectmodel,
            	 'projectmodel' => $projectmodel,
            	'modulemodel'=>$modulemodel,
            	'repomodels'=>$repomodels,      
            	'repos'=>$repos,     	
            	'formmodel'=>$formmodel,
    ]) ?>
</div>
