<?php


namespace app\controllers;


use app\models\EmailsAllSearch;
use app\models\Mails;
use yii\helpers\Url;

class MailAllController extends Controller
{
    public function actionIndex()
    {
        $mailboxes = array_keys(Mails::userMailboxesAsMap(\Yii::$app->user->id));
        $searchModel = new EmailsAllSearch(['mailboxes' => $mailboxes]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}