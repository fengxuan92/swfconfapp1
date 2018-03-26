<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "def_repofiles".
 *
 * @property int $id
 * @property int $module_id
 * @property int $repo_id
 * @property string $filepath
 * @property string $flag D - dir ( include sub dir/files ) F - file
 *
 * @property Repo $module
 * @property Repo $repo
 */
class DefRepofiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'def_repofiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'repo_id', 'filepath'], 'required'],
            [['module_id', 'repo_id'], 'integer'],
        	
            [['filepath'], 'string', 'max' => 4000],
            [['flag'], 'string', 'max' => 1],
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
            'id' => 'ID',
            'module_id' => 'Module ID',
            'repo_id' => 'Repo ID',
            'filepath' => 'Filepath',
            'flag' => 'Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Repo::className(), ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepo()
    {
        return $this->hasOne(Repo::className(), ['id' => 'repo_id']);
    }
}
