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

    private $attachmentsPath;


    public function __construct($mailBoxId)
    {
        $this->mailBoxId = $mailBoxId;
        /* @var $mailBox Mails */
        $mailBox = Mails::findOne($mailBoxId);
        $this->attachmentsPath = $this->getAttachmentPath($mailBoxId);
        $this->mailEngine = new Mailbox($mailBox->server, $mailBox->login, $mailBox->pwd, $this->attachmentsPath);
    }


    public function getEmails($sinceDate)
    {
        $mailsIds = $this->mailEngine->searchMailbox('SINCE ' . $sinceDate);
        if (!$mailsIds) {
            return [];
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
            'imap_to' => mb_substr(strval($mail->toString), 0, 254, 'UTF-8'),
            'imap_subject' => mb_substr(strval($mail->subject), 0, 254, 'UTF-8')
        ]);

        $this->saveAttachments($mail);
        $model->save();
    }

    public function fetchRecentEmails()
    {
        $maxReceiveDate = Emails::find()->where(['mailbox_id' => $this->mailBoxId])->max('created_at');

        if (is_null($maxReceiveDate)) {
            $mailbox = Mails::findOne($this->mailBoxId);
            $maxReceiveDate = $mailbox->start_date;
        }

        $unixDate = strtotime($maxReceiveDate);
        $sinceDate = date('d-M-Y', $unixDate);

        $emails = $this->getEmails($sinceDate);
        foreach ($emails as $email) {
            $this->saveEmail($email);
        }

    }

    private function getAttachmentPath($mailBoxId)
    {
        $basePath = \Yii::getAlias('@app/attachments');
        $attachmentPath = $basePath . '/' . strval($mailBoxId) . '/';

        if (!file_exists($attachmentPath)) {
            mkdir($attachmentPath, 0777);
        }

        return $attachmentPath;
    }

    private function saveAttachments(IncomingMail $mail)
    {
        $attachments = $mail->getAttachments();

        if (!empty($attachments)) {
            $mailAttachmentPath = $this->attachmentsPath . '/' . strval($mail->id) . '/';
            if (!file_exists($mailAttachmentPath)) {
                mkdir($mailAttachmentPath, 0777);
            }

            foreach ($attachments as $attachment) {
                /* @var $attachment \PhpImap\IncomingMailAttachment */
                rename($attachment->filePath, $mailAttachmentPath . $attachment->name);
            }
        }
    }
}