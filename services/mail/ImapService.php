<?php


namespace app\services\mail;

use app\models\Emails;
use app\models\Mails;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;
use yii\helpers\Json;

class ImapService
{
    /* @var $mailEngine Mailbox */
    private $mailEngine;

    private $mailBoxId;


    public function __construct($mailBoxId)
    {
        $this->mailBoxId = $mailBoxId;
        /* @var $mailBox Mails */
        $mailBox = Mails::findOne($mailBoxId);
        $attacmentsPath = \Yii::getAlias('@runtime');
        $this->mailEngine = new Mailbox($mailBox->server, $mailBox->login, $mailBox->pwd, $attacmentsPath);
    }


    public function getEmails($sinceDate)
    {
        $mailsIds = $this->mailEngine->searchMailbox('SINCE ' . $sinceDate);
        if(!$mailsIds) {
            return null;
        }
        $mailsIds = array_filter($mailsIds, [$this, 'isNotSaved']);

        $mailsList = [];
        foreach ($mailsIds as $mailId) {
            $mailsList[] = $this->mailEngine->getMail($mailId);
        }
        return $mailsList;
    }

    public function isNotSaved($imapId)
    {
        $found = Emails::find()->where(['mailbox_id' => $this->mailBoxId, 'imap_id' => $imapId])->exists();
        return !$found;
    }

    public function saveEmail(IncomingMail $mail)
    {
        $model = new Emails([
            'mailbox_id' => $this->mailBoxId,
            'imap_raw_content' => Json::encode($mail),
            'imap_id' => strval($mail->id),
            'imap_date' => strval($mail->date),
            'imap_from' => strval($mail->fromAddress),
            'imap_to' => strval($mail->toString),
            'imap_subject' => strval($mail->subject)
        ]);

        $model->save();
    }
}