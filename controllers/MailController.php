<?php

namespace app\controllers;

use app\helpers\ConvertLinks;
use app\models\EmailReply;
use app\models\Emails;
use app\models\EmailsSearch;
use app\models\EmployeesAR;
use app\models\Mails;
use app\models\UploadForm;
use app\services\mail\DownloadService;
use app\services\mail\LastEmailsService;
use app\services\mail\LockService;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\services\mail\ImapService;
use yii\helpers\Url;
use yii\web\UploadedFile;


class MailController extends Controller
{
    /* @var LastEmailsService */
    private $lastEmailsService;
    /**
     * @var LockService
     */
    private $lockService;

    public function __construct($id, $module,
                                LastEmailsService $lastEmailsService,
                                LockService $lockService,
                                $config = []
                       )
    {
        $this->lastEmailsService = $lastEmailsService;
        $this->lockService = $lockService;
        parent::__construct($id, $module, $config = []);
    }

    public function actionIndex()
    {
        $query = Mails::find()
            ->joinWith('mailboxUser', false, 'INNER JOIN')
            ->where(['mailbox_user.user_id' => \Yii::$app->user->id])
            ->orderBy('name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $counts = $this->lastEmailsService->getCountLastEmails();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'counts' => $counts
        ]);
    }

    public function actionMailbox($mailboxId, $isDeleted = null)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);

        $searchModel = new EmailsSearch(['mailbox_id' => $mailboxId, 'is_deleted' => $isDeleted]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('mailbox', [
            'mailbox' => $mailbox,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'isDeleted' => $isDeleted
        ]);
    }

    public function actionReleaseMail($mailId) {
        $this->checkAccessToMail($mailId);

        $mail = Emails::findOne($mailId);
        $this->lockService->release($mail);

        return $this->redirect(Url::previous());
    }


    public function actionView($id)
    {
        $this->checkAccessToMail($id);

        $mail = Emails::findOne($id);

        if ($mail->load(\Yii::$app->request->post())) {
            $this->lockService->release($mail);
            $mail->createXml();
            return $this->redirect(Url::previous());
        } else {
            $isLocked = $this->lockService->isLocked($mail);
            $this->lockService->lock($mail);

            $downloadService = new DownloadService($mail);
            $attachmentFileNames = $downloadService->getFileNames();

            $threadEmail = EmailReply::getThreadMail($mail);

            $replyEmails = EmailReply::find()
                ->where(['reply_to_id' => $id, 'status' => null])
                ->orderBy('created_at DESC')
                ->all();

            return $this->render('view', [
                'mail' => $mail,
                'attachmentFileNames' => $attachmentFileNames,
                'replyEmails' => $replyEmails,
                'threadEmail' => $threadEmail,
                'isLocked' => $isLocked
            ]);
        }
    }

    public function actionDownload($mailId, $fileName, $reply = false)
    {
        if ($reply) {
            $mail = EmailReply::findOne($mailId);
        } else {
            $mail = Emails::findOne($mailId);
        }

        $this->checkAccessToMailbox($mail->mailbox_id);

        $downloadService = new DownloadService($mail);
        return $downloadService->download($fileName);
    }

    public function actionReply($id, $isDraft = false)
    {
        $this->checkAccessToMail($id);

        $mail = Emails::findOne($id);
        $mail->load(\Yii::$app->request->post());
        $mail->manager_id = \Yii::$app->user->id;
        $mail->is_read = true;
        $mail->save();

        $mailbox = Mails::findOne($mail->mailbox_id);
        $uploadForm = new UploadForm();

        $model = new EmailReply([
            'mailbox_id' => $mail->mailbox_id,
            'reply_to_id' => $mail->id,
            'manager_id' => \Yii::$app->user->id,
            'from' => $mailbox->login,
            'to' => $mail->imap_from,
            'subject' => $mail->imap_subject,
            'content' => $mail->getContentForReply()
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            if ($isDraft) {
                $model->status = 'draft';
                $model->save();
                \Yii::$app->session->setFlash('success', 'Сохранен черновик ответа');
            } else {
                $model->status = null;
                $model->send();
                $model->save();
                $model->createXml();
                \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            }

            $this->lockService->release($mail);
            return $this->redirect(['mail-send/index', 'mailboxId' => $model->mailbox_id]);
        }

        return $this->render('reply', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionReplyView($id)
    {
        $mail = EmailReply::findOne($id);
        if ($mail->reply_to_id) {
            $this->checkAccessToMail($mail->reply_to_id);
        } else {
            $this->checkAccessToMailbox($mail->mailbox_id);
        }

        $downloadService = new DownloadService($mail);
        $attachmentFileNames = $downloadService->getFileNames();

        return $this->render('reply-view', [
            'model' => $mail,
            'attachmentFileNames' => $attachmentFileNames
        ]);
    }

    public function actionReplyUpdate($id, $isDraft = true)
    {
        $model = EmailReply::findOne($id);
        if ($model->reply_to_id) {
            $this->checkAccessToMail($model->reply_to_id);
        } else {
            $this->checkAccessToMailbox($model->mailbox_id);
        }

        $downloadService = new DownloadService($model);
        $attachmentFileNames = $downloadService->getFileNames();

        $uploadForm = new UploadForm();

        if ($model->load(\Yii::$app->request->post())) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            if ($isDraft) {
                $model->status = 'draft';
                $model->save();
                \Yii::$app->session->setFlash('success', 'Сохранен черновик ответа');
            } else {
                $model->status = null;
                $model->send();
                $model->save();
                $model->createXml();
                \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            }

            return $this->redirect(Url::previous());
        }

        return $this->render('reply-update', [
            'model' => $model,
            'attachmentFileNames' => $attachmentFileNames,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionGetRecent($mailboxId)
    {
        $this->checkAccessToMailbox($mailboxId);
        $imapService = new ImapService($mailboxId);
        $imapService->fetchRecentEmails();

        $result = $this->lastEmailsService->getCountLastEmails($mailboxId);

        return isset($result[$mailboxId]) ? strval($result[$mailboxId]) : '0';
    }

    public function actionCreate()
    {
        $this->checkAdmin();

        $users = EmployeesAR::notAdminsAsMap();

        $model = new Mails();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mail/index']);
        }

        return $this->render('create',
            [
                'model' => $model,
                'users' => $users
            ]
        );
    }

    public function actionUpdate($mailboxId)
    {
        $this->checkAdmin();

        $users = EmployeesAR::notAdminsAsMap();
        $model = Mails::findOne($mailboxId);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'mailboxId' => $model->id]);
        }

        return $this->render('update',
            [
                'model' => $model,
                'users' => $users
            ]
        );
    }

    public function actionDelete($mailboxId)
    {
        $this->checkAdmin();
        $model = Mails::findOne($mailboxId);
        $model->delete();
        $this->redirect(['mail/index']);
    }

    public function actionDeleteMail($id)
    {
        $this->checkAccessToMail($id);
        $email = Emails::findOne($id);
        $isDeleted = $email->is_deleted;
        if ($this->lockService->isLocked($email)) {
            \Yii::$app->session->setFlash('error', 'Письмо используется другим пользователем');
        } else {
            if (is_null($isDeleted)) {
                $email->is_deleted = true;
                $email->save();
                \Yii::$app->session->setFlash('success', 'Письмо успешно удалено');
            } else {
                $email->is_deleted = null;
                $email->save();
                \Yii::$app->session->setFlash('success', 'Письмо успешно восстановлено');
            }

        }
        //$this->redirect(['mail/mailbox', 'mailboxId' => $email->mailbox_id, 'isDeleted' => $isDeleted]);
        $this->redirect(Url::previous());
    }

}