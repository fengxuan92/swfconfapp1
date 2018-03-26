<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $user_name
 * @property string $branch_name
 * @property string $repo_name
 * @property string $action
 * @property string $action_reason
 * @property string $parent_model
 *
 * @property Branch[] $branches
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'action', 'log_time', 'project_name', 'branch_name'], 'required'],
            [['id'], 'integer'],
            [['log_time', 'end_time'], 'safe'],
            [['action_reason'], 'string'],
            [['user_name'], 'string', 'max' => 50],
            [['action', 'project_name', 'module_name', 'branch_name', 'repo_name'], 'string', 'max' => 100],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'action' => 'Action',
            'log_time' => 'Log Time',
            'project_name' => 'Project Name',
            'model_name' => 'Model Name',
            'branch_name' => 'Branch Name',
            'repo_name' => 'Repo Name',
            'action_reason' => 'Action Reason',
            'end_time' => 'End Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['lockLogId' => 'id']);
    }
}
