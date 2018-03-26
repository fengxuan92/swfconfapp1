<?php
use app\models\Project;
use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Calix Bitbucket Branch Management Application';
?>

<div class="site-index">

    <div class="jumbotron">
        <h2>Active Projects in cdc-stash</h2>
    </div>

    <div class="body-content">

        <div class="row">
        <?php
        $projs = Project::find()->asArray()->all();
        foreach( $projs as $project ){
        	echo "<div class=\"col-lg-4\"><h2>";
        	echo Html::a($project['name']." &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;&nbsp;",
        			[ 'branch/index', 'project'=>$project['name'], 'project_id'=>$project['id']],
        			[
        					'class' => "btn btn-lg btn-primary",

        			]);
        	echo "</h2>";
        	echo Html::tag("p", "project description: (from stash)<br/>". $project['description']);
        	echo '</div>';
        }
        ?>
<!--             <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
 -->
         </div>

    </div>
</div>
