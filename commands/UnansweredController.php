<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;


class UnansweredController extends Controller
{

    public function actionIndex()
    {
        $connection = \Yii::$app->db;
        $rows = (new \yii\db\Query())
            ->from('calls')
            ->andWhere(['<>', 'file', ''])
            ->orderBy('date DESC')
            ->all();
        foreach ($rows as $row) {
            $ids = (new \yii\db\Query())
                ->select(['id'])
                ->from('calls')
                ->where(['file' => '', 'tel_from' => $row['tel_from']])
                ->andWhere(['<', 'date', $row['date']])
                ->column();
            echo $row['date'] . '  ' . $row['tel_from'] . '  ' . implode(', ', $ids) . "\n";

            if (!empty($ids)) {
                $connection->createCommand()->update('calls', ['file' => '*'], ['id' => $ids])->execute();
            }

        }
    }
}
