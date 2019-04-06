<?php

namespace app\controllers;

use app\models\Emails;
use app\models\EmailsSearch;
use app\models\EmployeesAR;
use app\models\Mails;
use app\services\mail\LastEmailsService;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\services\mail\ImapService;


class MailController extends Controller
{
    use CheckAccessTrait;

    /* @var LastEmailsService */
    private $lastEmailsService;

    public function __construct($id, $module, LastEmailsService $lastEmailsService, $config = [])
    {
        $this->lastEmailsService = $lastEmailsService;
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

    public function actionView($id)
    {
        $this->checkAccessToMail($id);

        $mail = Emails::findOne($id);

        if ($mail->load(\Yii::$app->request->post())) {
            $mail->manager_id = \Yii::$app->user->id;
            $mail->is_read = true;
            $mail->is_in_work = false;
            $mail->save();
            return $this->redirect(['mail/mailbox', 'mailboxId' => $mail->mailbox_id]);
        } else {
            $content = Json::decode($mail->imap_raw_content);
            $textPlain = $content['textPlain'];
            $textHtml = $content['textHtml'];
            $textEmail = empty($textHtml) ? nl2br($textPlain) : $textHtml;

            return $this->render('view', [
                'mail' => $mail,
                'textEmail' => $textEmail,
                'content' => $content
            ]);
        }
    }

    public function actionReply($id)
    {
        $this->checkAccessToMail($id);
        return $this->render('reply');
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
            return $this->redirect(['index', 'mailboxId' => $model->id]);
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
        $this->checkAccessToMailbox($mailboxId);

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
}