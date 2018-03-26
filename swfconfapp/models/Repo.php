<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "repo".
 *
 * @property int $id
 * @property string $name
 * @property int $project_id
 *
 * @property DefRepofiles[] $defRepofiles
 * @property DefRepofiles[] $defRepofiles0
 * @property ModuleRepos[] $moduleRepos
 * @property Module[] $modules
 * @property Repo $project
 * @property Repo[] $repos
 */
class Repo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'repo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['project_id'], 'integer'],
            [['name','slug_name'], 'string', 'max' => 100],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Repo::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        	'slug_name' => 'Slug Name',
        	'project_id' => 'Project ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefRepofiles()
    {
        return $this->hasMany(DefRepofiles::className(), ['module_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefRepofiles0()
    {
        return $this->hasMany(DefRepofiles::className(), ['repo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModuleRepos()
    {
        return $this->hasMany(ModuleRepos::className(), ['repo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['id' => 'module_id'])->viaTable('module_repos', ['repo_id' => 'id']);
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
    public function getRepos()
    {
        return $this->hasMany(Repo::className(), ['project_id' => 'id']);
    }

    public static function syncBitbucket($project){

    	$allDbRepos = static::find()->join('INNER JOIN','project','project.id=repo.project_id')->where(['project.project_key'=>$project])->orderBy('repo.ID')->all();

    	$crowdsvr = Yii::createObject([
    			'class' => AtlassianRestApi::className(),
    	]);
    	$allBitbucketRepos = $crowdsvr->getAllRepos($project);

    	if($allBitbucketRepos === false) return false;
    	echo '-----';
    	asort($allBitbucketRepos);
    	if(count($allDbRepos)==0){
    		$pObj = Project::find()->where(['project_key'=>$project])->one();
    		if($pObj){
    			$project_id = $pObj->id;
    		} else {
    		  return false;
    		}
    	} else {
    		$project_id= $allDbRepos[0]->project_id;
    	}
    	$transaction=self::getDb()->beginTransaction();
    	try{

    		foreach ($allDbRepos as $oneDbRepo){
    			if( array_key_exists( $oneDbRepo->name, $allBitbucketRepos) ){
    				// find this project, update it and remove from current check list
    				$bktRepo = $allBitbucketRepos[$oneDbRepo->name];
    				$oneDbRepo->attributes = [
    						'name' => $bktRepo->name,
    						'slug_name' => $bktRepo->slug,
    						'project_id' => $project_id,
    				];
    				unset($allBitbucketRepos[$oneDbRepo->name]);
    			} else {
    				// this user is not exist any more, mark it in active
    				$oneDbRepo->delete();
    				$oneDbRepo->name=null;
    			}
    		}
    		foreach ($allBitbucketRepos as $name => $bktRepo) {
    			$newRepo=new Repo();
    			$newRepo->loadDefaultValues();
    			$newRepo->attributes = [
    					'name' => $bktRepo->name,
    					'slug_name' => $bktRepo->slug,
    					'project_id' => $project_id,
    			];
    			$allDbRepos[] = $newRepo;
    			echo "Adding repo {$newRepo->name}!\n";
    		}
    		foreach ($allDbRepos as $oneDbRepo){
    			if($oneDbRepo->name === null) continue;
    			$oneDbRepo->save(false);

    		}
    		$transaction->commit();
    		echo "Repo sync done!\n";
    	}catch (\Exception $e){
    		$transaction->rollback();
    		throw $e;
    	}catch (\Throwable $e){
    		$transaction->rollback();
    		throw $e;
    	}

    }

}
