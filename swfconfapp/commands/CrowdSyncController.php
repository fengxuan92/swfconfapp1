<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\CwdUser;
use yii\console\Controller;

/**
 * This command sync users from crowd server.
 *
 * This command will be executed in cron tasks.
 *
 * @author Frank Ye<Frank.Ye@calix.com>
 * @since 2.0
 */
class CrowdSyncController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        CwdUser::syncCrowdUsers();
        return 0;
    }

}
