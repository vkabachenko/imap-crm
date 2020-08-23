<?php

namespace app\commands;

use app\models\Mails;
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
        $this->fetch($mailboxId, $dateBegin, function($mail) { echo strval($mail->id) . "\n"; });
        echo 'Done' . "\n";
    }

    public function actionFetchAll()
    {
        $now = date('d-M-Y');
        $mailboxes = Mails::find()->all();
        foreach ($mailboxes as $mailbox) {
            \Yii::info('fetching mailbox ' .  $mailbox->id);
            $this->fetch($mailbox->id, $now, function($mail) { \Yii::info($mail->id); });
        }
    }

    private function fetch($mailboxId, $dateBegin, callable $logFunction)
    {
        try {
            $service = new ImapService($mailboxId);
            $emails = $service->getEmails($dateBegin);
            foreach ($emails as $mail) {
                $logFunction($mail);
                $service->saveEmail($mail);
            }
            \Yii::$container->set('app\services\path\PathInterface', 'app\services\path\XmlMailPath');
            $service->createXml($emails);
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }
    }
}
