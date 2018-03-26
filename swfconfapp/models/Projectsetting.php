<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_setting".
 *
 * @property int $project_id
 * @property string $settingkey
 * @property string $settingval
 *
 * @property Project $project
 */
class Projectsetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'settingkey', 'settingval'], 'required'],
            [['project_id'], 'integer'],
            [['settingkey'], 'string', 'max' => 100],
            [['settingval'], 'string', 'max' => 4000],
            [['project_id', 'settingkey'], 'unique', 'targetAttribute' => ['project_id', 'settingkey']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'settingkey' => 'Settingkey',
            'settingval' => 'Settingval',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
