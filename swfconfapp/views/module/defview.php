<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'view defRepofile';
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index', 'id' => $defmodel->module->project_id]];
$this->params['breadcrumbs'][] = ['label' => 'defrepofile', 'url' => ['defrepofile', 'id'=>$defmodel->module_id]];
 $this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-view">
<h1><?= Html::encode($this->title) ?></h1>
<?= DetailView::widget([
    'model' =>$defmodel,
    //'dataProvider'=>$dataProvider,
        'attributes' => [
            'id',
            'module_id',
            'repo_id',
        	'filepath',
        	'flag',
        ],
    ]) ?>

</div>
