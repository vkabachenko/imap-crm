<?php

namespace app\controllers;


use app\models\EmailStatus;
use app\models\MailboxStatus;
use yii\data\ActiveDataProvider;

class MailboxStatusController extends Controller
{
    public function beforeAction($action)
    {
        $this->checkAdmin();
        return parent::beforeAction($action);
    }

    public function actionIndex($mailboxId)
    {
        $query = MailboxStatus::find()
            ->where(['mailbox_id' => $mailboxId])
            ->orderBy('status');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'mailboxId' => $mailboxId
        ]);
    }

    public function actionCreate($mailboxId)
    {
        $model = new MailboxStatus(['mailbox_id' => $mailboxId]);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'mailboxId' => $mailboxId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id, $mailboxId)
    {
        $model = MailboxStatus::findOne($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'mailboxId' => $mailboxId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id, $mailboxId)
    {
        $model = MailboxStatus::findOne($id);
        $model->delete();

        return $this->redirect(['index', 'mailboxId' => $mailboxId]);
    }

    public function actionCopy($mailboxId)
    {
        $commonStatuses = EmailStatus::find()->select(['status'])->column();
        foreach ($commonStatuses as $commonStatus) {
            $model = new MailboxStatus(['status' => $commonStatus, 'mailbox_id' => $mailboxId]);
            $model->save();
        }
        return $this->redirect(['index', 'mailboxId' => $mailboxId]);
    }

}