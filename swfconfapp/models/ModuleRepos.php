<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module_repos".
 *
 * @property int $module_id
 * @property int $repo_id
 *
 * @property Module $module
 * @property Repo $repo
 */
class ModuleRepos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module_repos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'repo_id'], 'required'],
            [['module_id', 'repo_id'], 'integer'],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::className(), 'targetAttribute' => ['module_id' => 'id']],
            [['repo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Repo::className(), 'targetAttribute' => ['repo_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'module_id' => 'Module ID',
            'repo_id' => 'Repo ID',
        ];
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
    public function getRepo()
    {
        return $this->hasOne(Repo::className(), ['id' => 'repo_id']);
    }
}
