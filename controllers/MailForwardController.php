<?php


namespace app\controllers;


use app\models\EmailReply;
use app\models\Emails;
use app\models\MailForwardForm;
use app\models\Mails;
use app\services\mail\DownloadService;
use app\services\mail\LockService;

class MailForwardController extends Controller
{
    /**
     * @var LockService
     */
    private $lockService;

    public function __construct($id, $module,
                                LockService $lockService,
                                $config = []
    )
    {
        $this->lockService = $lockService;
        parent::__construct($id, $module, $config = []);
    }

    public function actionIndex($mailId)
    {
        $this->checkAccessToMail($mailId);
        $mail = Emails::findOne($mailId);
        $mail->load(\Yii::$app->request->post());
        $mail->manager_id = \Yii::$app->user->id;
        $mail->is_read = true;
        $mail->save();

        $modelForm = new MailForwardForm();

        return $this->render('index', [
            'mailId' => $mailId,
            'model' => $modelForm,
        ]);
    }

    public function actionSend($mailId)
    {
        $this->checkAccessToMail($mailId);
        $mail = Emails::findOne($mailId);
        $mailbox = Mails::findOne($mail->mailbox_id);
        $modelForm = new MailForwardForm();

        if ($modelForm->load(\Yii::$app->request->post()) && $modelForm->validate()) {

            $model = new EmailReply([
                'mailbox_id' => $mail->mailbox_id,
                'reply_to_id' => $mail->id,
                'manager_id' => \Yii::$app->user->id,
                'from' => $mailbox->login,
                'to' => $modelForm->to,
                'subject' => 'Fwd: ' . $mail->imap_subject,
                'content' => $mail->getContentForForward(),
                'status' => null
            ]);
            $downloadService = new DownloadService($mail);
            $downloadService->copyUploadedFiles(\Yii::getAlias('@app/uploads/'));
            $model->send();
            $model->save();
            $model->createXml();

            \Yii::$app->session->setFlash('success', 'Письмо успешно переслано');
            $mail->setStatus('Обработан');
            $mail->setAnswerMethod('mail');
            $this->lockService->release($mail);
            return $this->redirect(['mail-send/index', 'mailboxId' => $model->mailbox_id]);
        }

        return $this->render('index', [
            'mailId' => $mailId,
            'model' => $modelForm,
        ]);
    }



}