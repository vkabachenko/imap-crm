<?php


namespace app\controllers;


use app\models\EmailsReplyAllSearch;
use app\models\Mails;
use yii\helpers\Url;

class MailReplyAllController extends Controller
{
    public function actionIndex()
    {
        $mailboxes = array_keys(Mails::userMailboxesAsMap(\Yii::$app->user->id));
        $searchModel = new EmailsReplyAllSearch(['mailboxes' => $mailboxes]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}