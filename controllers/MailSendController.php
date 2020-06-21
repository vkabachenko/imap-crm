<?php


namespace app\controllers;


use app\models\EmailReply;
use app\models\EmailReplySearch;
use app\models\Emails;
use app\models\Mails;
use app\models\UploadFileForm;
use app\services\mail\CopyService;
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
        $uploadForm = new UploadFileForm();
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

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($isDraft) {
                $model->status = 'draft';
                $model->save(false);
                \Yii::$app->session->setFlash('success', 'Сохранен черновик письма');
            } else {
                $model->sendAndSave();
            }

            return $this->redirect(['mail-send/index', 'mailboxId' => $mailboxId]);
        }

        return $this->render('/mail/reply', [
            'model' => $model,
            'uploadForm' => $uploadForm,
            'createMail' => true
        ]);
    }

    public function actionAgain($id)
    {
        $model = EmailReply::findOne($id);
        $this->checkAccessToMailbox($model->mailbox_id);

        $service = new CopyService($model);
        /* @var $copyModel \app\models\EmailReply */
        $copyModel = $service->getCopy();

        Url::remember(['mail/mailbox', 'mailboxId' => $model->mailbox_id]);

        $this->redirect(['mail/reply-update', 'id' => $copyModel->id]);
    }

    public function actionGroupDelete()
    {
        $this->checkAdmin();
        $toDelete = \Yii::$app->request->post('checked');
        EmailReply::updateAll(['status' => 'deleted'], ['id' => $toDelete]);
        return '';
    }


}