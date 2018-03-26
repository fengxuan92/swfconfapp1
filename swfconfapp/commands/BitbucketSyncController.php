<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Branch;
use app\models\Project;
use yii\console\Controller;
/**
 * This command sync users from crowd server.
 *
 * This command will be executed in cron tasks.
 *
 * @author Frank Ye<Frank.Ye@calix.com>
 * @since 2.0
 */
class BitbucketSyncController extends Controller
{
    /**
     * This command sync projects from stash.
     */
    public function actionProject()
    {
        Project::syncBitbucket();
        return 0;
    }
    /**
     * This command sync all branches from stash.
     * @param string $project the only project to be sync.
     */
    public function actionBranches()
    {
    	$projects=Project::find()->all();
    	if($projects != null){
    		foreach ($projects as $project){
    			echo "project=$project->project_key \n";
    			Branch::syncBitbucket($project->project_key);
    		}
    	}else {
			echo 'no project';
    	}
    	return 0;
    }
}
