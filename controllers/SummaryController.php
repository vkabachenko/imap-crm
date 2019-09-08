<?php


namespace app\controllers;


use app\services\mail\SummaryService;
use yii\web\Response;

class SummaryController extends Controller
{
    public function actionCheck()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $summaryService = new SummaryService(\Yii::$app->user->id);

        $calls = $summaryService->getAllCalls();
        $callsLost = $summaryService->getLostCalls();
        $callsIn = $summaryService->getCallsIn();
        $callsInUser = $summaryService->getCallsInUser();

        $mails = $summaryService->getAllMails();
        $mailsLost = $summaryService->getLostMails();
        $mailsIn = $summaryService->getMailsIn();
        $mailsInUser = $summaryService->getMailsInUser();

        return [
            'calls' => $calls,
            'callsLost' => $callsLost,
            'callsIn' => $callsIn,
            'callsInUser' => $callsInUser,
            'mails' => $mails,
            'mailsLost' => $mailsLost,
            'mailsIn' => $mailsIn,
            'mailsInUser' => $mailsInUser,
        ];
    }

}