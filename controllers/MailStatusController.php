<?php

namespace app\controllers;


use app\models\EmailStatus;
use yii\data\ActiveDataProvider;

class MailStatusController extends Controller
{
    public function beforeAction($action)
    {
        $this->checkAdmin();
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $query = EmailStatus::find()->orderBy('status');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new EmailStatus();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = EmailStatus::findOne($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = EmailStatus::findOne($id);
        $model->delete();

        return $this->redirect(['index']);
    }

}