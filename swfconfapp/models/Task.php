<?php

namespace app\models;

use Yii;
/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $template_type
 * @property string $Validation_type
 * @property string $init_param
 * @property string $trigger_url
 * @property string $check_url
 * @property string $result_code S(succ),F(fail),W(waiting)
 * @property string $conseq_template template id list by comma
 * @property string $prereq_tasks
 * @property string $manual_close Y/N
 * @property string $manual_reason
 * @property string $due_time
 * @property string $user_name
 * @property string $mandatory Whether it is a mandated task,Y/N
 * @property int $project_id
 * @property int $frombranch_id
 * @property int $tobranch_id
 *
 * @property Branch $frombranch
 * @property Project $project
 * @property Branch $tobranch
 * @property TaskAssocBitbucket[] $taskAssocBitbuckets
 * @property TaskAssocData[] $taskAssocDatas
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'template_type', 'Validation_type', 'manual_close', 'mandatory', 'project_id', 'frombranch_id', 'tobranch_id'], 'required'],
            [['due_time'], 'safe'],
        	[['project_id', 'frombranch_id', 'tobranch_id','lockLogId'], 'integer'],
        	[['lockState'], 'string', 'max' => 1],
        	[['lockLogId'], 'exist', 'skipOnError' => true, 'targetClass' => Log::className(), 'targetAttribute' => ['lockLogId' => 'id']],
            [['name', 'trigger_url', 'check_url', 'conseq_template', 'prereq_tasks'], 'string', 'max' => 255],
            [['template_type', 'Validation_type'], 'string', 'max' => 20],
            [['init_param'], 'string', 'max' => 4000],
            [['result_code', 'manual_close', 'mandatory'], 'string', 'max' => 1],
            [['manual_reason'], 'string', 'max' => 400],
            [['user_name'], 'string', 'max' => 50],
            [['frombranch_id'], 'exist', 'skipOnError' => true,  'targetAttribute' => ['frombranch_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true,  'targetAttribute' => ['project_id' => 'id']],
            [['tobranch_id'], 'exist', 'skipOnError' => true,  'targetAttribute' => ['tobranch_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'template_type' => 'Template Type',
            'Validation_type' => 'Validation Type',
            'init_param' => 'Init Param',
            'trigger_url' => 'Trigger Url',
            'check_url' => 'Check Url',
            'result_code' => 'Result Code',
            'conseq_template' => 'Conseq Template',
            'prereq_tasks' => 'Prereq Tasks',
            'manual_close' => 'Manual Close',
            'manual_reason' => 'Manual Reason',
            'due_time' => 'Due Time',
            'user_name' => 'User Name',
            'mandatory' => 'Mandatory',
            'project_id' => 'Project ID',
            'frombranch_id' => 'Frombranch ID',
            'tobranch_id' => 'Tobranch ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrombranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'frombranch_id']);
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
    public function getTobranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'tobranch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAssocBitbuckets()
    {
        return $this->hasMany(TaskAssocBitbucket::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAssocDatas()
    {
        return $this->hasMany(TaskAssocData::className(), ['task_id' => 'id']);
    }
}
