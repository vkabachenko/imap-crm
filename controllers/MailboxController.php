<?php


namespace app\controllers;


use app\models\EmployeesAR;

class MailboxController extends Controller
{

    public function actionDefault($userId)
    {
        $this->checkAdmin();

        $model = EmployeesAR::findOne($userId);

        if ($model->load(\Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['site/employees']);
        }

        return $this->render('set-default',
            [
                'model' => $model,
            ]
        );
    }

}