<?php
//click branch link next to show "branch permission" include 3 parts


use app\models\LockStatusFormatter;
use app\models\Roles;
use kartik\datetime\DateTimePicker;
use kartik\dialog\Dialog;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this yii\web\View */

$this->title = 'Status of branches';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['branch/index', 'project'=>$branchModel->project->name, 'project_id'=>$branchModel->project->id]];
$this->params ['breadcrumbs'] [] = 'Branch Management';
echo Html::style('
  .short-string { min-width: 8pc }
  .long-string { min-width: 60pc }
  .help-block{ margin:0 }
  .form-group{ margin:0 0 1px }
'); // this CSS style will effect to field label

/**
 * To use modal window below:
 * trigger button with attributes:
 *       data-toggle="modal"
 *       data-target="#reasonInputModal"
 *       data-moduleid=<module id>
 *       data-titletext=<Dialog Title Text>
 *       data-submitbtntext=<Submit button Text>
 *       data-initaltext=<initial text input>
 *       data-initaldate=<initial date input>
 */
$this->registerJs( <<<EOT
$("button,.btn").on("click", function() {
var user_id = $("#user_id").html();
if (!user_id){
	alert("Please login first!");
	return false;
};
return true;
});

$("#LockCmd").on("click", function() {
	var now = new Date();
	var end = $("input[name='end_time']").val();
	if (end){
	    var end_time = new Date(end);
		if (end_time < now){
			alert("plan end time must be later than now time! OR it can be empty");
			return false;
		}
	}

});


$('#reasonInputModal').on('show.bs.modal', function(event){
    if(event.relatedTarget==null) return;
    var button=$(event.relatedTarget) // Button that triggered the modal
    var titletext=button.data('titletext')
    var submitBtnText=button.data('submitbtntext')
    var modal=$(this)
    modal.find('.modal-title').text(titletext)
    modal.find('#LockCmd').val(submitBtnText)
    modal.find('input[name=moduleid]').val(button.data('moduleid'))
    modal.find('#action_reason').val(button.data('initialtext'))
    modal.find('end_time').val(button.data('initialdate'))
})
EOT
, View::POS_READY);
$form = ActiveForm::begin ( [
    'action' => ['swfconf/lock_module_branch', 'branch_id'=>$branchModel->id, ],
    'options' => [
    'class' => 'form-horizontal',
    'data' => [    'pjax' => false ],
    ],
    'enableAjaxValidation' => false ] );
    ?>
    <!-- Modal -->
    <div class="modal fade" id="reasonInputModal" tabindex="-1" role="dialog" aria-labelledby="reasonInputModal">
     <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="reasonInputModal">title</h4>
        </div>
        <div class="modal-body">
           <table>
             <tr><td class="col-md-2"><label for="reason" class="control-label">Input reason:</label></td><td class="col-md-6"><input type="text" id="action_reason" name="action_reason" style="width:100%"/></td></tr>
             <tr><td class="col-md-2"><label for="end_time" class="control-label .col-md-3">Plan end date:</label></td><td class='col-md-6'><?= DateTimePicker::widget( [
                        'name'=>'end_time',
                        'options' => ['placeholder' => 'please enter a time point',],
                        'pluginOptions' => [
                                'autoclose' => true,
                        ]
                ]); ?></td></tr>
           </table>
            <input type="hidden" name="moduleid"/>
        </div>
        <div class="modal-footer">
         <input type="submit" class="btn btn-primary modal-submitbtn" id="LockCmd" name="LockCmd" value="Close"/>
        </div>
      </div>
     </div>
    </div>
    <!-- end of Modal -->


    <?php
    if(isset($errorReport['status']) && ! $errorReport['status'] ){
      echo Dialog::widget([
          'dialogDefaults' => [
              Dialog::DIALOG_ALERT => [
              'type' => Dialog::TYPE_DANGER,
              'title' => 'Error',
              ],
          ],
      ]);
      $this->registerJs( sprintf("krajeeDialog.alert('%s');", htmlentities($errorReport['message'])) );
    };
    $project_id = $branchModel->project_id;
	$project_role = Roles::find()->where(['project_id' => $project_id])->andWhere(['role' => 'admin'])->one();

	if (is_null($project_role)){

		$project_admins=array();
	}
	else{
		$admins_str = $project_role->user_ids;
		$project_admins = explode(";", $admins_str);
	}
	//var_dump($project_admins);
	?>


    <?php
ActiveForm::end();

// sample button: '<button data-titletext="Open Module" data-toggle="modal" data-target="#reasonInputModal" data-moduleid=122 data-submitBtnText="Open" data-initialtext="I close it" data-initialdate="2017-1-12">launchmodal</button> ';
echo "<h3>Branch name: &nbsp; &nbsp; {$branchModel->name}</h3>    <div class=\"form-group\">";

if( $viewPart == 1 ) {
    // view for branch in Locked state: 'L'
    $form = ActiveForm::begin ( [
            'id' => 'swfconf-form-1',
            'action' => ['swfconf/open_locked_branch','id'=>$branchModel->id],
            'options' => [ 'class' => 'form-horizontal',
                    'data' => [    'pjax' => true ],
            ],
            'enableAjaxValidation' => true ] );
    // some hidden fields to keep post form data
    // sample: echo Html::activeHiddenInput($branchModel, 'id');
?>

    <?php $lsf=new LockStatusFormatter();?>
      <h4>&nbsp;&nbsp;Currently this branch is in <?php echo $lsf->asLockStatus($branchModel->lockState)?> state</h4>
      <b>Reason</b> is: <?= $branchModel->lockUnlockReason(true) ?><br/>
      click button below to open this branch:<br/>
    <?php
    if (in_array(Yii::$app->user->id, $project_admins)){
	    	echo Html::submitButton('Open it', [
	        'id' => $branchModel->id,
	        'data-toggle' => 'modal',
	        'data-target' => '#forceclose-modal',
	        'class' => 'btn btn-primary',
    		]);
    	}?>
    <?php
    ActiveForm::end();
} elseif ( $viewPart == 2 ) {
    // view for branch in Open/HalfOpen state: 'M'/'O'
      $lsf=new LockStatusFormatter();
      ?>
      <h4>&nbsp;&nbsp;Currently this branch is in <?= $lsf->asLockStatus($branchModel->lockState)?> state</h4>
      <b>Reason</b> is: <?= $branchModel->lockUnlockReason(true) ?><br/>

    <?php $form=ActiveForm::begin([
            'id'=>'swfconf-form3',
            'action'=>['swfconf/save_branch_jira','branch_id'=>$branchModel->id],
            'layout'=>"horizontal",
            'fieldConfig'=>[
                    'horizontalCssClasses' => [
                            'label' => 'col-sm-2',
                            'offset' => 'col-sm-offset-2',
                    ],
            ],
    ]);?>
      <?php // $branchModel->owner=array_keys($owerArr);?>
      <?=
      $form->field($branchModel,'owner',[
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-2',
            ],
      ] )->widget(Select2::classname(), [
            'data' => $allowUsers,
            'options'=>['placeholder'=>'Select a branch Owner'],
            'pluginOptions' => [
                    'tags' => false,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 30,
            ],
    ]);?>
    <?= $form->field ( $branchModel, 'limit_jira_ids' )->textInput ( ['maxlength' => true, ] ) ?>
    <?= $form->field ( $branchModel, 'limit_fix_versions' )->textInput ( ['maxlength' => true,] ) ?>

    <?= $form->field($branchModel,'allow_user_array',[
            'template' => '{label}<div class="row"><div class="col-sm-9">{input}{error}{hint}</div></div>',
      ] )->widget(Select2::classname(), [
          'data' => $allowUsers,
          'options' => [
              'placeholder'=> $branchModel->allow_user === ";" ? '-- all users denied now --,  select allowed users ...' : '-- all users allowed now --,  select allowed users ...',
              'multiple'=>true,
          ],

     ]);?><div class="form-group "><div class='col-sm-2'></div>&nbsp;<div class='col-sm-6'>
    <?php
    if (in_array(Yii::$app->user->id, $project_admins)){
    	echo Button::widget(['label'=>'Save Limitation', 'options'=>['class' => 'btn btn-primary'],]);
    }
    ?>

    <?php ActiveForm::end(); ?>
    <?php
    if (in_array(Yii::$app->user->id, $project_admins)){
    	echo Button::widget(['label'=>"Force Close Branch",
            'tagName' => 'a',
            "options" => [
                    'class'=>"btn btn-primary",
                    'data-toggle'=>"modal", 'data-target'=>"#reasonInputModal",
                    'data-titletext'    =>"Force Close Whole Branch",
                    'data-submitBtnText'=>"Force Close Branch",
                    'data-initialtext'  =>"This branch is closed because ...",
                    'data-initialdate'  =>"2017-1-12",
                    ],
            ]);
		}
		?></div></div>
	<?php $bid=$branchModel->id;?>
	<?php
	$layout = "<tr><th>#</th><th>Module</th><th>Status</th><th>Reason</th><th>Action</th></tr>{items}";
	$itemview = '_module_lock_view_user';
	if (in_array(Yii::$app->user->id, $project_admins)){

		$lockall = Html::button("Lock All",[ 'class' =>'btn btn-primary',
				'data-toggle'    => "modal",
				'data-target'    => "#reasonInputModal",
				'data-titletext' => "Lock All Module",
				'data-submitBtnText'=>"Lock All",
				'data-initialtext'=>"Lock because ....",
				'data-initialdate'=>"",]).' ';

		$layout = "<tr><th>#</th><th>Module</th><th>Status</th><th>Reason</th><th>Action <a href='index.php?r=swfconf%2Fopenallmodules&id=$bid'><button class='btn btn-primary' type='button' >Open All</button></a> ".$lockall."</th></tr>{items}";





		$itemview = '_module_lock_view';
	}
	?>
    <?= ListView::widget([
            'dataProvider' => $modulesProvider,
    		'itemView' => $itemview,
            'options' => [
                    'tag' => 'table',
                    'class' => 'table table-striped table-bordered',
            ],
            'itemOptions' => [
                    'tag' => 'tr',
            ],
            'viewParams' => [
                    'form' => $form,
                    'logModel' => $logModel,
            ],

            //'layout' => "<tr><th>#</th><th>Module</th><th>Status</th><th>Reason</th><th>Action</th></tr>{items}",
    		//'layout' => "<tr><th>#</th><th>Module</th><th>Status</th><th>Reason</th><th>Action <button class='btn btn-primary' type='button' onclick='$.ajax({url: swfconf/openallmodules&id=$bid})'>Open All</button></th></tr>{items}",
    		//'layout' => "<tr><th>#</th><th>Module</th><th>Status</th><th>Reason</th><th>Action <a href='index.php?r=swfconf%2Findexshow&id=$bid'><button class='btn btn-primary' type='button' >Open All</button></a></th></tr>{items}",
    		'layout' => $layout,

    ]) ?>
    <?php
}
    ?>
    <div hidden="" id="user_id"><?= Yii::$app->user->id?></div>
</html>