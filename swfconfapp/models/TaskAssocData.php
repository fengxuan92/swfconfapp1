<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_assoc_data".
 *
 * @property int $id
 * @property int $task_id
 * @property string $name
 * @property string $val
 *
 * @property Task $task
 */
class TaskAssocData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_assoc_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'name'], 'required'],
            [['task_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['val'], 'string', 'max' => 8000],
            [['task_id', 'name'], 'unique', 'targetAttribute' => ['task_id', 'name']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'name' => 'Name',
            'val' => 'Val',
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
     * @return TaskAssocDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskAssocDataQuery(get_called_class());
    }
}
