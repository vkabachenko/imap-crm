<?php


namespace app\services\mail;


use app\models\EMailInterface;
use yii\helpers\Json;

class CopyService
{
    /* @var \app\models\EMailInterface */
    private $model;

    public function __construct(EMailInterface $model)
    {
        $this->model = $model;
    }

    public function getCopy()
    {
        /* @var $copy \app\models\EMailInterface */
        $copy = clone $this->model;
        $copy->isNewRecord = true;
        $copy->clearAttributes();
        if (!$copy->save()) {
            throw new \Exception(Json::encode($copy->getErrors()));
        }

        $downloadService = new DownloadService($this->model);
        $downloadService->copyUploadedFiles($copy->setAttachmentPath());

        return $copy;
    }

}