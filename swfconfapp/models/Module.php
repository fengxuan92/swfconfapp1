<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property int $id
 * @property string $name
 * @property int $project_id
 *
 * @property Project $project
 * @property ModuleRepos[] $moduleRepos
 * @property Repo[] $repos
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module';
    }
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'project_id'], 'required'],
            [['project_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Module ID',
            'name' => 'Module Name',
            'project_id' => 'Project ID',
        ];
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
    public function getModuleRepos()
    {
        return $this->hasMany(ModuleRepos::className(), ['module_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepos()
    {
        return $this->hasMany(Repo::className(), ['id' => 'repo_id'])->viaTable('module_repos', ['module_id' => 'id']);
    }
    
    public function getFiles(){
    	return $this->hasMany(DefRepofiles::className(), ['module_id' => 'id']);
    }
    
}
