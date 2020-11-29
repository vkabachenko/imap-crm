<?php

namespace app\controllers;

use app\models\EmployeeCorrespondence;
use app\models\EmployeesAR;
use app\services\employee\EmployeeCorrespondenceService;
use Mpdf\Tag\Em;
use yii\base\Model;

class CatalogController extends Controller
{
    public function actionEmployeeCorrespondence()
    {
        $service = new EmployeeCorrespondenceService();
        $items = $service->getInputEmployeesCorrespondence();
        $users = EmployeesAR::usersAsMap();

        if (\Yii::$app->request->isPost) {
            EmployeeCorrespondence::deleteAll();
            $count = count($items);
            $items = [];
            for ($i=0; $i < $count; $i++) {
                $items[] = new EmployeeCorrespondence();
            }
            if (Model::loadMultiple($items, \Yii::$app->request->post())) {
                foreach ($items as $item) {
                    if (!empty($item->employee_id)) {
                        $item->save();
                    }
                }
                \Yii::$app->session->setFlash('success', "Успешно сохранено");
                return $this->refresh();
            }
        }



        return $this->render('update-employee-correspondence', compact('items', 'users'));
    }

}