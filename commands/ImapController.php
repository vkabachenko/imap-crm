<?php

namespace app\commands;

use yii\console\Controller;
use app\services\mail\ImapService;


class ImapController extends Controller
{

    /**
     * @param $mailboxId
     * @param $dateBegin
     *
     * call: php yii imap/fetch 1 01-Jan-2019
     */
    public function actionFetch($mailboxId, $dateBegin)
    {
        $service = new ImapService($mailboxId);
        $emails = $service->getEmails($dateBegin);
        foreach ($emails as $mail) {
            echo strval($mail->id) . "\n";
            $service->saveEmail($mail);
        }
        $service->createXml($emails);
        echo 'Done' . "\n";

    }
}
