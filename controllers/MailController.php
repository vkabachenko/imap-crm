<?php

namespace app\controllers;

use app\models\Mails;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;


class MailController extends Controller
{
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
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /* @todo */
    public function actionMailbox($mailboxId)
    {
        return $this->render('mailbox', [
            'mailboxId' => $mailboxId
        ]);
    }

}