<?php


namespace app\controllers;


use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class ReportController extends Controller
{
    public function beforeAction($action)
    {
        $this->checkAdmin();
        return parent::beforeAction($action);
    }

    public function actionAdv()
    {
        $rows = (new \yii\db\Query())
            ->from('calls')
            ->select(['tel_from', 'tel_to', 'date', 'refs'])
            ->where(['is not', 'refs', null])
            ->orderBy('date DESC')
            ->all();

        foreach ($rows as &$row) {
            $ref = Json::decode($row['refs']);
            $row['campaign'] = isset($ref['campaign_name']) ? $ref['campaign_name'] : '';
            $row['search_engine'] = isset($ref['search_engine']) ? $ref['search_engine'] : '';
            $row['search_query'] = isset($ref['search_query']) ? $ref['search_query'] : '';
        }

        $dataProvider = new ArrayDataProvider(['allModels' => $rows]);

        return $this->render('adv', compact('dataProvider'));
    }

}