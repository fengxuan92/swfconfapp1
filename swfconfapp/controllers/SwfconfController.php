<?php

namespace app\controllers;

use app\models\Branch;
use app\models\CwdUser;
use app\models\Log;
use app\models\Module;
use app\models\ModuleBranches;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;



class SwfconfController extends \yii\web\Controller {

    /*indexshow*/
    public function actionIndexshow($id)
    {
        $branchmodel=Branch::findOne(['id'=>$id]);
        $viewPart = 1;
        if($branchmodel->lockState == Branch::LOCKED){
            $viewPart = 1;
        } else {
            $viewPart = 2;
        }
        //load modules
        $modules=ModuleBranches::find()->where(['branch_id'=>$id])->all();
        $projectid=$branchmodel->project_id;
        $modulemodels=Module::find()->where(['project_id'=>$projectid])->all();
        $branchs=Branch::find()->where(['project_id'=>$projectid])->all(); //load all branches from one project
        if(is_null($modules)){
          foreach ($modulemodels as $modulemodel){
            foreach ($branchs as $branch){//add module to every branch
              $bid=$branch->id;
              $this->addNewMB($modulemodel->id,$bid);
            }
          }
        }else{
          foreach ($modulemodels as $modulemodel){
            foreach ($branchs as $branch){//add module to every branch
              $bid=$branch->id;
              $temp=ModuleBranches::findOne(['module_id'=>$modulemodel->id,'branch_id'=>$bid]);
              if(is_null($temp)){
                $this->addNewMB($modulemodel->id,$bid);
              }
            }
          }
        }

        //var_dump($project_admins);
        return $this->renderViewPart($branchmodel,$viewPart,'indexshow');
    }
	protected  function addNewMB($mid,$bid){
		$newM_B=new ModuleBranches();
		$newM_B->module_id=$mid;
		$newM_B->branch_id=$bid;
		$newM_B->lockState=Branch::OPEN;;
		$newM_B->save();
	}
	private function renderViewPart($branchModel,$viewPart,$next_rpath)
    {
        $logModel = new Log();

        if($viewPart==1){
            return $this->render($next_rpath, [
                    'branchModel' => $branchModel,
                    'viewPart' => $viewPart,
                    'logModel' => $logModel,
            ]);
        } else {
          $usermodels=CwdUser::find()->select("user_name,display_name")->where('active = "Y"')->indexBy('user_name')->all();
          $allowUsers=ArrayHelper::map($usermodels, "user_name", "display_name");
          $query = $branchModel->getBranchModules();
          $moduleLocks = $query->all();
          foreach ($moduleLocks as $modulelock){
            if(!isset($modulelock->branch_id)){
              $modulelock->setIsNewRecord(true);
              $modulelock->lockState = Branch::OPEN;
              $modulelock->branch_id = $branchModel->id;
              $modulelock->save();
            }
          }
          $moduleProvider = new ActiveDataProvider([
              'query' => $query,
              'pagination' => [
                  'pageSize' => 60,
              ],
          ] );
          $params = [
              'branchModel' => $branchModel,
              'modulesProvider' => $moduleProvider,
              'viewPart' => $viewPart,
              'logModel' => $logModel,
              'allowUsers'=>$allowUsers,
              'userModel' => $usermodels,
          ];
          if( isset($_REQUEST['errorReport']) ){
            $params['errorReport'] = $_REQUEST['errorReport'];
          }
          return $this->render ( $next_rpath, $params );
        }
    }

    /* showindex  (open branch from force state)*/
    public function actionOpen_locked_branch($id)
    {
        $branchmodel=Branch::findOne(['id'=>$id]);
        if($branchmodel->UpdateBranchLockStatus() && $branchmodel->save() ){
        	$branchmodel->modifyBitbucketBranchUserPermission();
            $viewPart = 2;

        }
        return $this->renderViewPart ( $branchmodel, $viewPart, 'indexshow' );
    }

    /*showindex  (force close state)*/
    public function actionForce_close_branch($id){
        $branchmodel=Branch::findOne(['id'=>$id]);
        if($branchmodel->ForceCloseBranch()){
             $user = CwdUser::findOne(['id'=>Yii::$app->user->id]);
             $log = new Log();
             $log->loadDefaultValues();
             $log->load($_POST,"");
             $log->attributes = [
                     'user_name' => $user->user_name,
                     'action' => "Force close branch : ".$branchmodel->name,
                     'log_time' => date('Y-m-d H:i:s'),
                     'project_name' => $branchmodel->project->name,
                     'branch_name' => $branchmodel->name,
                     'repo_name' => '',
             ];
             if( $log->save() ){
                 $branchmodel->lockLogId = $log->id;
                 $branchmodel->save();
                 $branchmodel->modifyBitbucketBranchUserPermission();
             }
             $viewPart = 1;
        }
        return $this->renderViewPart ( $branchmodel, $viewPart, 'indexshow' );
    }

    /**
     *  showindex  (lock/open/force open module for one branch)
     *  or force close one branch
     */
    public function actionLock_module_branch($branch_id)
    {
        $sRet = false;
        if(array_key_exists('moduleid', $_POST)){
            $module_id = $_POST['moduleid'];
            $moduleBranchmodel=null;
            if(isset($module_id)) $moduleBranchmodel=ModuleBranches::findOne(['module_id'=>$module_id, 'branch_id'=>$branch_id]);
            if (Yii::$app->request->isAjax) {
                return [];
            }
            if(array_key_exists('LockCmd', $_POST)){
	            switch($_POST['LockCmd'])
	            {
	                case "Open":
	                    $sActionTitle = "Open module";
	                    if($moduleBranchmodel){
	                        $sRet=$moduleBranchmodel->OpenLockedModule();
	                    }
	                    break;
	                case "Lock":
	                    $sActionTitle = "Lock module";
	                    if($moduleBranchmodel){
	                        $sRet=$moduleBranchmodel->LockOpenedModule();
	                    }
	                    break;
	                case "Force Open":
	                    $sActionTitle = "Force open module";
	                    if($moduleBranchmodel){
	                        $sRet=$moduleBranchmodel->ForceOpenLockedModule();
	                    }
	                    break;
	                case "Force Close Branch":
	                    return $this->actionForce_close_branch($branch_id);
	                default:
	                    break;
	            }
            }
            if($sRet){
                $user = CwdUser::findOne(['id'=>Yii::$app->user->id]);
                $branch = Branch::findOne(['id'=>$branch_id]);
                /*$repoids=ModuleBranches::find()->where(['module_id'=>$moduleBranchmodel->module->id])->all();
                $repos="";
                foreach($repoids as $repoid){
                	$repo=Repo::findOne(['id'=>$repoid])->name;
                	$repos=$repos . $repo . ";";
                }*/
                $log = new Log();
                $log->loadDefaultValues();
                $log->load($_POST,"");
                $log->attributes = [
                    'user_name' => $user->user_name,
                    'action' => $sActionTitle." : ".$moduleBranchmodel->module->name,
                    'log_time' => date('Y-m-d H:i:s'),
                    'project_name' => $branch->project->name,
                    'branch_name' => $branch->name,
                    'module_name' => $moduleBranchmodel->module->name,
                    'repo_name' => '',
                ];
                if( $log->save() ){
                    $moduleBranchmodel->lockLogId = $log->id;
                    $moduleBranchmodel->save();
                    $branch->UpdateBranchLockStatus() && $branch->save();

                }
            }
            //begin lock all modules
            if($_POST['LockCmd']=='Lock All'){

            	$sActionTitle = "Lock all module of branch";
            	$branchmodel=Branch::findOne(['id'=>$branch_id]);

            	$user = CwdUser::findOne(['id'=>Yii::$app->user->id]);
            	$log_id = '';
            	$log = new Log();
            	$log->loadDefaultValues();
            	$log->load($_POST,"");
            	$log->attributes = [
            			'user_name' => $user->user_name,
            			'action' => $sActionTitle." : ".$branchmodel->name,
            			'log_time' => date('Y-m-d H:i:s'),
            			'project_name' => $branchmodel->project->name,
            			'branch_name' => $branchmodel->name,
            			'module_name' => '',
            			'repo_name' => '',
            	];
            	if( $log->save() ){
            		$log_id = $log->id;
            	}

            	$moduleBranchmodels = ModuleBranches::find()->where(['branch_id'=>$branch_id])->all();
            	foreach ($moduleBranchmodels as $moduleBranchmodel){
            		$sRet=$moduleBranchmodel->LockOpenedModule();
            		if($sRet){
            			$moduleBranchmodel->lockLogId = $log_id;
            			$moduleBranchmodel->save();
            		}
            	}
            	$branchmodel->UpdateBranchLockStatus() && $branchmodel->save();
            }

            //end lock all modules

            $branchmodel = $moduleBranchmodel->branch;
        } else {
            $branchmodel=Branch::findOne(['id'=>$branch_id]);
        }
        $viewPart = 2;
        return $this->renderViewPart ( $branchmodel, $viewPart, 'indexshow' );
    }

    /* showindex  (force open module for one branch)*/
    public function actionCommit_module_lock($branch_id)
    {
        $moduleBranchmodel=ModuleBranches::findOne(['module_id'=>$module_id, 'branch_id'=>$branch_id]);
        if($moduleBranchmodel->LockOpenedModule() && $moduleBranchmodel->save() ){
            $viewPart = 2;
        }
        return $this->renderViewPart ( $moduleBranchmodel->branch, $viewPart, 'indexshow' );
    }

    public function actionSave_branch_jira($branch_id){
        $branchmodel=Branch::findOne(['id'=>$branch_id]);
        $branchmodel->load(Yii::$app->request->post());
        $result = $branchmodel->modifyBitbucketBranchUserPermission();
        if($result === false){
          $retArr = [
              'status' => false,
              'message' => 'Bitbucket server response Error, \n user list maybe not setup, please contact admin!',
          ];
        } elseif (isset($result->errors)){
          $retArr = [
              'status' => false,
              'message' => $result->errors[0]->message,
          ];
        } else {
          $retArr = [
              'status' => true
          ];
          if( !$branchmodel->save() ){
            throw new \Exception(implode(';', $branchmodel->firstErrors));
          }
        }
        return $this->redirect(['indexshow', 'id' => $branch_id, 'errorReport' => $retArr]);
    }

    /* open all modules and their branch*/
    public function actionOpenallmodules($id)
    {
      $branchmodel=Branch::findOne(['id'=>$id]);
      $modulemodels=ModuleBranches::find()->where(['branch_id'=>$id])->all();
      foreach ($modulemodels as $modulemodel){
        $modulemodel->lockState=Branch::OPEN;
        $modulemodel->save();
      }
      $branchmodel->lockState=Branch::OPEN;
      $branchmodel->save();
      return $this->redirect(['indexshow','id'=>$id]);
    }

}
