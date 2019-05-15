<?php


namespace app\controllers;


use app\models\EmailReply;
use app\models\EmailReplySearch;
use app\models\Emails;
use app\models\Mails;
use app\models\UploadForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

class MailSendController extends Controller
{

    public function actionIndex($mailboxId, $status = null)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);

        $searchModel = new EmailReplySearch(['mailbox_id' => $mailboxId, 'status' => $status]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        Url::remember();

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
        $this->redirect(Url::previous());
    }

    public function actionCreate($mailboxId, $isDraft = false)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);
        $uploadForm = new UploadForm();
        $content = "\n\n---------------------\n\n"
            . \Yii::$app->user->identity->mail_signature
            . "\n"
            . $mailbox->signature;
        $model = new EmailReply([
            'mailbox_id' => $mailboxId,
            'manager_id' => \Yii::$app->user->id,
            'from' => $mailbox->login,
            'content' => $content
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            if ($isDraft) {
                $model->status = 'draft';
                $model->save();
                \Yii::$app->session->setFlash('success', 'Сохранен черновик письма');
            } else {
                $model->status = null;
                $model->send();
                $model->save();
                $model->createXml();
                \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            }

            return $this->redirect(['mail-send/index', 'mailboxId' => $mailboxId]);
        }

        return $this->render('/mail/reply', [
            'model' => $model,
            'uploadForm' => $uploadForm,
            'createMail' => true
        ]);
    }


}