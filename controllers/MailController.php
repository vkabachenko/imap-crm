<?php

namespace app\controllers;

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
use yii\web\Controller;
use yii\filters\AccessControl;
use app\services\mail\ImapService;
use yii\web\UploadedFile;


class MailController extends Controller
{
    use CheckAccessTrait;

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

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
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

    public function actionMailbox($mailboxId)
    {
        $this->checkAccessToMailbox($mailboxId);

        $mailbox = Mails::findOne($mailboxId);

        $searchModel = new EmailsSearch(['mailbox_id' => $mailboxId]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('mailbox', [
            'mailbox' => $mailbox,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionReleaseMail($mailId) {
        $this->checkAccessToMail($mailId);

        $mail = Emails::findOne($mailId);
        $this->lockService->release($mail);

        return $this->redirect(['mail/mailbox', 'mailboxId' => $mail->mailbox_id]);
    }


    public function actionView($id)
    {
        $this->checkAccessToMail($id);

        $mail = Emails::findOne($id);

        if ($mail->load(\Yii::$app->request->post())) {
            $mail->manager_id = \Yii::$app->user->id;
            $mail->is_read = true;
            $this->lockService->release($mail);
            return $this->redirect(['mail/mailbox', 'mailboxId' => $mail->mailbox_id]);
        } else {
            if ($this->lockService->isLocked($mail)) {
                return $this->render('in-work', [
                    'mail' => $mail,
                ]);
            } else {
                $this->lockService->lock($mail);

                $content = Json::decode($mail->imap_raw_content);
                $textPlain = $content['textPlain'];
                $textHtml = $content['textHtml'];
                $textEmail = empty($textHtml) ? nl2br($textPlain) : $textHtml;

                $downloadService = new DownloadService($mail);
                $attachmentFileNames = $downloadService->getFileNames();

                $replyEmails = EmailReply::find()
                    ->where(['reply_to_id' => $id])
                    ->orderBy('created_at DESC')
                    ->all();

                return $this->render('view', [
                    'mail' => $mail,
                    'textEmail' => $textEmail,
                    'content' => $content,
                    'attachmentFileNames' => $attachmentFileNames,
                    'replyEmails' => $replyEmails
                ]);
            }
        }
    }

    public function actionDownload($mailId, $fileName, $reply = false)
    {
        if ($reply) {
            $mail = EmailReply::findOne($mailId);
            $mailId = $mail->reply_to_id;
        } else {
            $mail = Emails::findOne($mailId);
        }

        $this->checkAccessToMail($mailId);

        $downloadService = new DownloadService($mail);
        return $downloadService->download($fileName);
    }

    public function actionReply($id)
    {
        $this->checkAccessToMail($id);

        $mail = Emails::findOne($id);
        $mailbox = Mails::findOne($mail->mailbox_id);
        $uploadForm = new UploadForm();
        $model = new EmailReply([
            'mailbox_id' => $mail->mailbox_id,
            'reply_to_id' => $mail->id,
            'manager_id' => \Yii::$app->user->id,
            'from' => $mailbox->login,
            'to' => $mail->imap_from
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            $model->send();
            $model->save();
            $this->lockService->release($mail);
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('reply', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionReplyView($id)
    {
        $mail = EmailReply::findOne($id);
        $this->checkAccessToMail($mail->reply_to_id);

        $downloadService = new DownloadService($mail);
        $attachmentFileNames = $downloadService->getFileNames();

        return $this->render('reply-view', [
            'model' => $mail,
            'attachmentFileNames' => $attachmentFileNames
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

}