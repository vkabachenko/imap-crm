<?php


namespace app\commands;

use yii\console\Controller;


class OldCallsController extends Controller
{

    public function actionDrop($date = '2018-01-01')
    {
        $date = strtotime($date);
        $rows = (new \yii\db\Query())
            ->from('calls')
            ->where(['<', 'date', $date])
            ->orderBy('date ASC')
            ->all();

        $basePath = \Yii::getAlias('@webroot');

        foreach ($rows as $row) {
            $file = $row['file'];
            if (!empty($file) && $file !== '*') {
                if ($file[0] !== '/') {
                    $file = '/' . $file;
                }

                if (file_exists($basePath . $file)) {
                    echo $file . "\n";
                    @unlink($basePath . $file);
                }
            }

        }

        $connection = \Yii::$app->db;
        $connection->createCommand()->delete('calls',  ['<', 'date', $date])->execute();

        echo 'Done' . "\n";

    }
}
