<?php


namespace app\controllers;

use app\models\ReportAdvSearch;
use app\models\ReportManagerActivitySearch;

class ReportController extends Controller
{

    public function actionAdv()
    {
        $searchModel = new ReportAdvSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $dateEnd = date('d-m-Y', $searchModel->dateEnd);
        $dateBegin = date('d-m-Y', $searchModel->dateBegin);

        return $this->render('adv', compact('dataProvider', 'searchModel', 'dateBegin', 'dateEnd'));
    }

    public function actionStat()
    {
        $searchModel = new ReportManagerActivitySearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $dateEnd = date('d-m-Y', $searchModel->dateEnd);
        $dateBegin = date('d-m-Y', $searchModel->dateBegin);

        return $this->render('stat', compact('dataProvider', 'searchModel', 'dateBegin', 'dateEnd'));
    }

}