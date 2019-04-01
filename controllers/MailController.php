<?php

namespace app\controllers;

use app\models\Mails;
use app\services\mail\LastEmailsService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;


class MailController extends Controller
{
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
        $mailbox = Mails::findOne($mailboxId);

        $query = $this->lastEmailsService->getLastEmailsQuery($mailboxId);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('mailbox', [
            'mailbox' => $mailbox,
            'dataProvider' => $dataProvider,
        ]);
    }

}