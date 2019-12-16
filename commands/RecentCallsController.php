<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\RecentCalls;
use yii\console\Controller;


class RecentCallsController extends Controller
{

    public function actionClear($message = 'hello world')
    {
        RecentCalls::deleteAll();
    }
}
