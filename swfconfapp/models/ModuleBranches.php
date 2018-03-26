<?php

namespace app\models;


/**
 * This is the model class for table "module_branches".
 *
 * @property int $module_id
 * @property int $branch_id
 * @property string $lockState S - special OPEN for this module M - locked for this module O - open
 */
class ModuleBranches extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module_branches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'branch_id'], 'required'],
            [['module_id', 'branch_id', 'lockLogId'], 'integer'],
            [['lockState'], 'string', 'max' => 1],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::className(), 'targetAttribute' => ['module_id' => 'id']],
        ];
    }
/*
S - special OPEN for this module
M - locked for this module
O - open
*/
    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'module_id' => 'Module ID',
            'branch_id' => 'Branch ID',
            'lockState' => 'Lock State',
            'lockLogId' => 'Lock Log ID',
        ];
    }

    /*get module*/

    public function getmodules()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('module_branches', ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Module::className(), ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLockLog()
    {
        if(!isset($this->lockLogId)) return null;
        return $this->hasOne(Log::className(),['id'=>'lockLogId']);
    }

    /**
     * Open branch if one branch is in locked state
     * @return boolean
     */
    public function ForceOpenLockedModule()
    {
        $this->lockState = Branch::FORCEOPEN;
        return true;
    }

    /**
     * Open one module if it is in locked state
     * @return boolean
     */
    public function OpenLockedModule()
    {
        $this->lockState = Branch::OPEN;
        return true;
    }

    /**
     * Lock one module if in Open/Force Open state
     * @return boolean
     */
    public function LockOpenedModule()
    {
        $this->lockState = Branch::MLOCK;
        return true;
    }

    public function lockUnlockReason($moredetail=false)
    {
        $reason = "";
        $lockLog = $this->lockLog;
        switch ($this->lockState){
            case Branch::LOCKED:
            case Branch::MLOCK:
                $reason .= $this->module->name . ' is <b>locked</b>';
                if(isset($lockLog->action_reason)&&!empty($lockLog->action_reason)) $reason .= ", by reason of " . $lockLog->action_reason;
                if(isset($lockLog->end_time)) $reason .= ", it has an planned Open Date at " . $lockLog->end_time;
                if($moredetail&&isset($lockLog)) $reason .= ", is locked by " . $lockLog->user_name . " at ". $lockLog->log_time;
                $reason .= ";";
                break;
            case Branch::FORCEOPEN:
                $reason = "module {$this->module->name} is <b>special opened</b>";
                if(isset($lockLog->action_reason)&&!empty($lockLog->action_reason)) $reason .= ", by reason of " . $lockLog->action_reason;
                if(isset($lockLog->end_time)) $reason .= ", it has an planned Lock Date at " . $lockLog->end_time;
                if($moredetail&&isset($lockLog)) $reason .= ", is unlocked by " . $lockLog->user_name . " at ". $lockLog->log_time;
                $reason .= ";";
                break;
            case Branch::OPEN:
                if($moredetail || isset($lockLog->end_time)&&!empty($lockLog->action_reason)){
                    $reason .= "module " . $this->module->name . ' is <b>open</b>';
                    if(isset($lockLog->action_reason)&&!empty($lockLog->action_reason)) $reason .= ", by reason of " . $lockLog->action_reason;
                    if(isset($lockLog->end_time)) $reason .= ", it has an planned Lock Date at " . $lockLog->end_time;
                }
                if($moredetail&&isset($lockLog)) $reason .= ", is unlocked by " . $lockLog->user_name . " at ". $lockLog->log_time;
                $reason .= ";";
                break;
        }
        return $reason;
    }
}
