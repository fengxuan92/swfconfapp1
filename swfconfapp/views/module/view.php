<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'view Module';
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index', 'id'=>$model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    </p>

<?= DetailView::widget([
    	'model' =>$model,
        'attributes' => [
            'id',
            'name',
            'project_id',
        	'project.name',
        	'repos',
            'files:html',
        ],
    ]) ?>

</div>
