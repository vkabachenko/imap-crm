<?php


namespace app\services\mail;

use app\models\EMailInterface;

class DownloadService
{
    private $mailAttachmentPath;

    public function __construct(EMailInterface $mail) {
        $this->mailAttachmentPath = $mail->setAttachmentPath();
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