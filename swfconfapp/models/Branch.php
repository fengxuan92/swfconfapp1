<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * This is the model class for table "branch".
 *
 * @property int $id
 * @property string $name
 * @property int $project_id branch name in one project is unique, one branch maybe only available to some of modules
 * @property string $limit_fix_versions
 * @property string $limit_jira_ids
 * @property string $lockState L-project level locked S-special open for some modules M-locked by some module O-open  priority order from highest to lowest
 * @property int $lockLogId
 * @property string $allow_user
 * @property string $owner
 *
 * @property Log $lockLog
 * @property Project $project
 */
class Branch extends \yii\db\ActiveRecord
{
    const LOCKED    = "L";
    const FORCEOPEN = "S";
    const MLOCK     = "M";
    const OPEN      = "O";

    private $_allowedUserArr;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    public $somparam;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'project_id'], 'required'],
            [['project_id', 'lockLogId'], 'integer'],
            [['name', 'owner'], 'string', 'max' => 100],
            [['allow_user'], 'string', 'max' => 8000],
            ['allow_user_array','validate_allow_user_array', 'params'=>['max'=>8000]],
            [['limit_fix_versions'], 'string', 'max' => 500],
            [['limit_jira_ids'], 'string', 'max' => 4000],
            [['lockState'], 'string', 'max' => 1],
            [['name', 'project_id'], 'unique', 'targetAttribute' => ['name', 'project_id']],
            [['lockLogId'], 'exist', 'skipOnError' => true, 'targetClass' => Log::className(), 'targetAttribute' => ['lockLogId' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Branch name'),
            'project_id' => Yii::t('app', 'project id'),
            'limit_fix_versions' => Yii::t('app', 'Limit FixVersion(s)'),
            'limit_jira_ids' => Yii::t('app', 'Limit Jira ID(s)'),
            'lockState' => Yii::t('app', 'Lock Status'),
            'lockLogId' => Yii::t('app', 'Lock Log ID'),
            'allow_user' => Yii::t('app', 'Allowed User(s)'),
            'allow_user_array' => Yii::t('app', 'Allowed User(s)'),
            'owner' => Yii::t('app', 'Branch Owner'),
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     * @param \yii\validators\InlineValidator related InlineValidator instance.
     * This parameter is available since version 2.0.11.
     */
    public function validate_allow_user_array($attribute, $params, $validator)
    {
      $len=0;
      if(!isset($params['max'])) return;
      foreach ($this->allow_user_array as $user){
        $len += 1+sizeof($user);
      }
      if($len > $params['max']){
        $this->addError($attribute,"The selected users must not exceed {$params['max']} characters.");
      }
    }

    /**
     * helper for array access to allow_user
     * @return array
     */
    public function getAllow_user_array()
    {
      if(!isset($this->_allowedUserArr)){
        $this->_allowedUserArr = ($this->allow_user===";") ? array() : explode(";",$this->allow_user);
      }
      return $this->_allowedUserArr;
    }

    /**
     * helper for array access to allow_user
     * @return array
     */
    public function setAllow_user_array($array)
    {
      if(!is_array($array)) $array=null;
      $this->_allowedUserArr=$array;
      if($array){
        $this->allow_user = implode(";", $array);
      }else {
        $this->allow_user = null;
      }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLockLog()
    {
        return $this->hasOne(Log::className(), ['id' => 'lockLogId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchModules()
    {
  #      $aQuery = ModuleBranches::find()->with('module')->select("module.id as module_id, module_branches.branch_id, module_branches.lockState,module_branches.lockLogId, module.*")
  #      ->rightJoin("module", "module.id=module_branches.module_id and module_branches.branch_id={$this->id}" );
 #   	$aQuery = ModuleBranches::find()->where(['branch_id' => $this->id])->with('module')->select("module.id as module_id, module_branches.branch_id, module_branches.lockState,module_branches.lockLogId, module.*")
 #   	->rightJoin("module", "module.id=module_branches.module_id");
    	#$ids = ModuleBranches::find('module_id')->where(['branch_id'=>$this->id])->asArray()->all();
    	#$aQuery = Module::find()->where(['id'=> $ids]);
    	$bb = $this->id;
    	$aQuery = ModuleBranches::find()->with('module')->where(['module_branches.branch_id' =>$this->id])->select("module.id as module_id, module_branches.branch_id, module_branches.lockState,module_branches.lockLogId, module.*")
    	->rightJoin("module", "module.id=module_branches.module_id" );
    	$aa = $aQuery->createCommand()->getRawSql();
        return $aQuery;
    }

    /**
     * Open branch if one branch is in locked state
     * @return boolean
     */
    public function UpdateBranchLockStatus()
    {
        // need check modules, will check modules
        $modulebranch=$this->getBranchModules()->all();
        $rcheck=\yii\helpers\ArrayHelper::map($modulebranch,'lockState','module_id');

        if(!isset($rcheck[self::MLOCK])){
            $this->lockState=self::OPEN;
        }
        elseif(isset($rcheck[self::FORCEOPEN])){
            $this->lockState=self::FORCEOPEN;
        }
        else{
            $this->lockState=self::MLOCK;
        }
        if($this->allow_user==";"){
          $this->allow_user=null;
          $this->modifyBitbucketBranchUserPermission();
        }
        return true;
    }
    /**
     * Force Close branch if one branch is in open state
     * @return boolean
     */
    public function ForceCloseBranch()
    {
        $this->lockState = self::LOCKED;
        $this->allow_user= ";"; //this is a special value to denie all permision from bitbucket
        return true;
    }
    /**
     * @inheritdoc
     * @return BranchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BranchQuery(get_called_class());
    }

    static function JiraLink($JIRAIDs)
    {
        $result='';
        if(is_array($JIRAIDs)){
            foreach ($JIRAIDs as $jiraID ){
                $result .= ($result?", ":"").Html::a($jiraID, "http://jira.calix.local/browse/$jiraID");
            }
        } else {
            $result = Html::a($JIRAIDs, "http://jira.calix.local/browse/$JIRAIDs");
        }
        return $result;
    }

    static function RemoveDupComma($text)
    {
        $text = preg_replace(["/[ ,.]*;[ ;,.]*/","/,[ ,]+/"], ["; ",", "], $text);
        return $text;
    }

    static function AutoLinkJiraID($text)
    {
        $text = preg_replace_callback('/<a.*>\s*\S+<\/a>|\b[A-Z]{2,8}-[0-9]{1,7}\b/',
                function ($matches){
                    return $matches[0][0]=='<' ? $matches[0] : self::JiraLink($matches[0]);
                },
                $text);
        return $text;
    }

    public function lockUnlockReason($moredetail=false)
    {
        $lockLog = $this->lockLog;
        $reason = "";
        if($this->lockState === self::LOCKED){
            if( !isset($lockLog) || empty($lockLog->action_reason)){
                return "this branch is just <b>locked</b>, no special reason.";
            }
            $reason = "Branch is <b>locked</b> by reason of ".$lockLog->action_reason;
            if(isset($lockLog->end_time)) $reason .= ", it has an planned OPEN date at " . $lockLog->end_time;
            if($moredetail&&isset($lockLog)) $reason .= ", is locked by " . $lockLog->user_name . " at ". $lockLog->log_time;
            return $reason;
        }
        if($this->lockState === self::OPEN ){
            $reason .= "Branch is <b>open</b>";
            if( isset($lockLog) && !empty($lockLog->action_reason)){
                $reason .= " by reason of ". $lockLog->action_reason;
                if(isset($lockLog->end_time)) $reason .= ", it has an planned LOCK date at " . $lockLog->end_time;
            }
            if($moredetail&&isset($lockLog)) $reason .= ", is unlocked by " . $lockLog->user_name . " at ". $lockLog->log_time;
            $reason .= ";";
        } else {
            // for MLOCK or FORCEOPEN, need inspect detail modules
            $allLockedModules=$this->getBranchModules()->all();
            $allSopens=array();
            $allMlocks=array();
            foreach ($allLockedModules as $lMod){
                if($lMod->lockState === self::FORCEOPEN){
                    $allSopens[]=$lMod;
                }elseif($lMod->lockState === self::MLOCK){
                    $allMlocks[]=$lMod;
                }
            }
            if( count($allSopens) > 0 ){
                $reason .= "Branch is <b>partially-open</b> for modules: ";
                foreach ($allSopens as $lMod){
                    $reason .= " " .$lMod->module->name;
                }
                $reason .= "; In detail, ";
                $reasonArr = ArrayHelper::index($allSopens, "lockLogId");
                foreach($reasonArr as $lMod){
                    $reason .= $lMod->lockUnlockReason($moredetail);
                }
                if(substr($reason, -1) !== ";") $reason .= ";";
            }

            if( count($allMlocks) > 0 ){
                if(empty($reason)){
                    $reason .= "Branch is <b>partially-locked</b> for modules: ";
                    foreach ($allMlocks as $lMod){
                        $reason .= " " .$lMod->module->name;
                    }
                }
                $reason .= "; ";
                $reasonArr = ArrayHelper::index($allMlocks, "lockLogId");
                foreach($reasonArr as $lMod){
                    $reason .= ", " .$lMod->lockUnlockReason($moredetail);
                }
                if(substr($reason, -1) !== ";")$reason .= ";";
            }
        }
        if($this->limit_jira_ids){
            $reason .= "<BR/>Only these tickets are allowed to deliver: " . self::JiraLink( preg_split('/[,;.]/',$this->limit_jira_ids)). ".";
        } else if ($this->limit_fix_versions) {
            $reason .= "<BR/>Only tickets with fix-version(s) {$this->limit_fix_versions} are allowed to deliver.";
        }
        if(isset($this->allow_user) && !empty($this->allow_user)){
            $reason .= "<BR/>Branch white list: ";
            if($this->allow_user === ';'){
              $reason .= "NONE in white list, everyone denied.";
            } else {
              foreach (preg_split("/[;,]/", $this->allow_user) as $one_user){
                $user = CwdUser::findOne(['user_name'=>$one_user]);
                if(is_object($user)){
                    $reason .= $user->display_name . ", ";
                } else {
                    $reason .= $one_user . ", ";
                }
              }
            }
            if(substr($reason, -2)==", ") $reason = substr($reason, 0, -2);
        }
        return self::AutoLinkJiraID( self::RemoveDupComma($reason) );
    }

    /**
     * return contacted white list of users
     * @param object $oRet
     * @return NULL|string, NULL means all users allowed, ";" means none users allowed
     */
    private static function namelist_of_allowed_users($oRet)
    {
      $sRet=null;
      if(is_object($oRet)){
        foreach ($oRet->values as $oPerm){
          if($oPerm->type == "read-only"){
            $sRet=implode(';', ArrayHelper::getColumn($oPerm->users, 'name'));
            if($sRet==="") $sRet=";";
          }
        }
      }
      return $sRet;
    }

    public static function syncBitbucket($project){
      $allDbBranches = static::find()->join('INNER JOIN','project','project.id=branch.project_id')->where(['project.project_key'=>$project])->orderBy('branch.ID')->all();
      $crowdsvr = Yii::createObject([
          'class' => AtlassianRestApi::className(),
      ]);
      $allBitbucketBranches = $crowdsvr->getAllBranches($project);
      if($allBitbucketBranches === false) return false;
      asort($allBitbucketBranches);
      if(count($allDbBranches)==0){
        $pObj = Project::find()->where(['project_key'=>$project])->one();
        if($pObj){
          $project_id = $pObj->id;
        } else {
          return false;
        }
      } else {
        $project_id= $allDbBranches[0]->project_id;
      }
      $transaction=Branch::getDb()->beginTransaction();
      try{
        foreach ($allDbBranches as  $oneDbBranch){
          if( array_key_exists( $oneDbBranch->name, $allBitbucketBranches) ){
            // find this project, update it and remove from current check list
            $bktBranch = $allBitbucketBranches[$oneDbBranch->name];
            $oneDbBranch->attributes = [
                'name' => $bktBranch->displayId,
                'project_id' => $project_id,
                'allow_user' => self::namelist_of_allowed_users($crowdsvr->getBranchPermission($project,$bktBranch->displayId)),
            ];
            echo "Branch for {$oneDbBranch->name} sync start!\n";
            unset($allBitbucketBranches[$oneDbBranch->name]);
          } else {
            if( preg_match("/^IB-|^FB-|^MB-|^PB-|^RB-/", $oneDbBranch->name ) ){
              // this branch is not exist any more, report it but not remove it
              echo "Branch {$oneDbBranch->name} is not in bitbucket anymore, please double check!\n";
            } else {
              echo "Removing branch {$oneDbBranch->name}!\n";
              $oneDbBranch->delete();
              $oneDbBranch->name=null;
            }
          }
        }
        foreach ($allBitbucketBranches as $name => $bktBranch) {
          $newBranch=new Branch();
          $newBranch->loadDefaultValues();
          $newBranch->attributes = [
              'name' => $bktBranch->displayId,
              'project_id' => $project_id,
              'allow_user' => self::namelist_of_allowed_users($crowdsvr->getBranchPermission($project,$bktBranch->displayId)),
              'lockState' => "O",
          ];
          $allDbBranches[] = $newBranch;
          echo "Adding branch {$newBranch->name}!\n";
        }
        foreach ($allDbBranches as $oneDbBranch){
          if($oneDbBranch->name === null) continue;
          else{
          	$oneDbBranch->save(false);
          }

        }
        $transaction->commit();
        echo "Sync branches for project $project done!\n";
      }catch (\Exception $e){
        $transaction->rollback();
        throw $e;
      }catch (\Throwable $e){
        $transaction->rollback();
        throw $e;
      }
    }

    public function modifyBitbucketBranchUserPermission(){
        $bitbucketsvr = Yii::createObject([
                'class' => AtlassianRestApi::className(),
        ]);
        $result = $bitbucketsvr->grantBranchPermission($this->project->project_key, $this->name, $this->allow_user);
        return $result;
    }

}
