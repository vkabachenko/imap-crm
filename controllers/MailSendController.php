<?php


namespace app\controllers;


use app\models\EmailReply;
use app\models\EmailReplySearch;
use app\models\Mails;

class MailSendController extends Controller
{

    public function actionIndex($mailboxId, $status = null)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);

        $searchModel = new EmailReplySearch(['mailbox_id' => $mailboxId, 'status' => $status]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'mailbox' => $mailbox,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'status' => $status
        ]);
    }

    public function actionDelete($id)
    {
        $email = EmailReply::findOne($id);
        $this->checkAccessToMail($email->reply_to_id);

        $status = $email->status;
        switch ($status) {
            case 'draft':
                $email->delete();
                \Yii::$app->session->setFlash('success', 'Черновик успешно удален');
                break;
            case 'deleted':
                $email->status = null;
                $email->save();
                \Yii::$app->session->setFlash('success', 'Письмо успешно восстановлено');
                break;
            default:
                $email->status = 'deleted';
                $email->save();
                \Yii::$app->session->setFlash('success', 'Письмо успешно удалено');
        }
        $this->redirect(['mail-send/index', 'mailboxId' => $email->mailbox_id, 'status' => $status]);
    }


}