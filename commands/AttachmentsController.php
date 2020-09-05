<?php


namespace app\commands;

use app\models\Emails;
use yii\console\Controller;
use app\services\mail\DownloadService;

class AttachmentsController extends Controller
{
    const MAX_ALLOWED_SIZE = 100; //bytes

    public function actionRemove()
    {
        $dateLimit = date("Y-m-d H:i:s", strtotime('-2 month'));

        $emails = Emails::find()
            ->select(['mailbox_id', 'imap_id'])
            ->where(['<', 'imap_date', $dateLimit])
            ->all();

        foreach ($emails as $email) {
            $downloadService = new DownloadService($email);
            $files = $downloadService->getPaths();
            foreach ($files as $file) {
                if (filesize($file) > self::MAX_ALLOWED_SIZE) {
                    file_put_contents($file, 'Истек срок хранения файла на сервере');
                }
            }
        }
    }

}