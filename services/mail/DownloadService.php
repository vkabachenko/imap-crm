<?php


namespace app\services\mail;

use app\models\Emails;

class DownloadService
{
    private $mailAttachmentPath;

    public function __construct(Emails $mail) {

        $this->mailAttachmentPath = \Yii::getAlias('@app/attachments')
            . '/'
            . strval($mail->mailbox_id)
            . '/'
            . strval($mail->imap_id)
            . '/';
    }

    public function getFileNames()
    {
        if (!file_exists($this->mailAttachmentPath)) {
            return [];
        } else {
            return array_diff(scandir($this->mailAttachmentPath), ['..', '.']);
        }
    }

    public function download($fileName)
    {
        return \Yii::$app->response->sendFile($this->mailAttachmentPath . $fileName, $fileName );
    }

}