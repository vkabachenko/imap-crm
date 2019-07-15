<?php

namespace app\controllers;

use app\models\UploadFileForm;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\helpers\Json;

class FileUploadController extends \yii\web\Controller
{
    public function actionUpload()
    {
        $model = new UploadFileForm();

        $model->file = UploadedFile::getInstance($model, 'file');

        if ($model->file) {
            $filePath = $model->directory . $model->file->name;
            if ($model->file->saveAs($filePath)) {
                $path = $model->url . $model->file->name;
                return Json::encode([
                    'files' => [
                        [
                            'name' => $model->file->name,
                            'size' => $model->file->size,
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => '/file-upload/delete?name=' . $model->file->name,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }

        return '';
    }

    public function actionDelete($name)
    {
        $model = new UploadFileForm();
        if (is_file($model->directory . $name)) {
            unlink($model->directory . $name);
        }

        $files = FileHelper::findFiles($model->directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = $model->url . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => '/file-upload/delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

}