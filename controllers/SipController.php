<?php


namespace app\controllers;


use app\models\EmployeesAR;
use app\models\SipAR;

class SipController extends Controller
{
    public function actionCreate()
    {
        $this->checkAdmin();

        $users = EmployeesAR::notAdminsAsMap();

        $model = new SipAR();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/sip']);
        }

        return $this->render('create',
            [
                'model' => $model,
                'users' => $users
            ]
        );
    }

    public function actionUpdate($sipId)
    {
        $this->checkAdmin();

        $users = EmployeesAR::notAdminsAsMap();
        $model = SipAR::findOne($sipId);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/sip']);
        }

        return $this->render('update',
            [
                'model' => $model,
                'users' => $users
            ]
        );
    }

}