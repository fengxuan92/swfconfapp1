<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $jira_keys
 *
 * @property Branch[] $branches
 * @property Module[] $modules
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name','project_key'], 'string', 'max' => 100],
            [['jira_keys'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Project ID',
            'name' => 'Project Name',
        	'project_key' => 'Project Key',
        	'jira_keys' => 'Jira Keys',
            'description' =>'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['project_id' => 'id']);
    }


    public function getProjectList()
    {
    	$returnArr = $this->find('id','name')->all();
    	return $returnArr;
    	#return CHtml::listData($returnArr, 'id', 'name');
    }

    public static function syncBitbucket(){
   		$allDbProjects = static::find()->orderBy('ID')->all();
		$crowdsvr = Yii::createObject([
				'class' => AtlassianRestApi::className(),
		]);
		foreach ( $crowdsvr->getAllProjects() as $project){
			$allBitbucketProjects [strtolower($project->key)] = $project;
		}
		asort($allBitbucketProjects);
		$transaction=Project::getDb()->beginTransaction();
		try{
			foreach ($allDbProjects as $oneDbProject){
				if( array_key_exists( strtolower($oneDbProject->project_key), $allBitbucketProjects) ){
					// find this project, update it and remove from current check list
					echo "Project {$oneDbProject->name} sync start!\n";
					$bktproject = $allBitbucketProjects[strtolower($oneDbProject->project_key)];
					$oneDbProject->attributes = [
						'name' => $bktproject->name,
						'project_key' => $bktproject->key,
					    'description' => isset($bktproject->{"description"})?$bktproject->{"description"}:null,

						];
					unset($allBitbucketProjects[strtolower($oneDbProject->project_key)]);
				} else {
				    // this project is not exist any more, mark it in active
					echo "Removing project {$oneDbProject->name} start!\n";
					$oneDbProject->delete();
					$oneDbProject->name=null;
				}
		}
		foreach ($allBitbucketProjects as $name => $bktproject) {
			$newProject=new Project();
			$newProject->loadDefaultValues();
			$newProject->attributes = [
					'name' => $bktproject->name,
					'project_key' => $bktproject->key,
					'description' => $bktproject->{"description"},
					];
			$allDbProjects[] = $newProject;
			echo "Adding project {$newProject->name}!\n";
		}
		  foreach ($allDbProjects as $oneDbProject){
		  	if($oneDbProject->name === null) continue;
			$oneDbProject->save(false);
		  }
		  $transaction->commit();
		  echo "Project sync done!\n";
		  foreach ($allDbProjects as $oneDbProject){
		  	if($oneDbProject->name === null) continue;

		  	\app\models\Repo::syncBitbucket($oneDbProject->project_key);
		  	echo "Sync project {$oneDbProject->name} repos done!\n";

		  }
		}catch (\Exception $e){
			$transaction->rollback();
			throw $e;
		}catch (\Throwable $e){
			$transaction->rollback();
			throw $e;
		}

    }

}
