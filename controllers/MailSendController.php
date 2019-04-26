<?php


namespace app\controllers;


use app\models\EmailReplySearch;
use app\models\Mails;

class MailSendController extends Controller
{

    public function actionIndex($mailboxId)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);

        $searchModel = new EmailReplySearch(['mailbox_id' => $mailboxId]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'mailbox' => $mailbox,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

}