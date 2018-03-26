<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_assoc_bitbucket".
 *
 * @property int $task_id
 * @property int $pullreq_id
 * @property int $bbtask_id
 * @property int $bbrepo_id
 *
 * @property Task $task
 */
class TaskAssocBitbucket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_assoc_bitbucket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'pullreq_id', 'bbtask_id', 'bbrepo_id'], 'required'],
            [['task_id', 'pullreq_id', 'bbtask_id', 'bbrepo_id'], 'integer'],
            [['task_id', 'pullreq_id', 'bbtask_id'], 'unique', 'targetAttribute' => ['task_id', 'pullreq_id', 'bbtask_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'pullreq_id' => 'Pullreq ID',
            'bbtask_id' => 'Bbtask ID',
            'bbrepo_id' => 'Bbrepo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @inheritdoc
     * @return TaskAssocBitbucketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskAssocBitbucketQuery(get_called_class());
    }
}
