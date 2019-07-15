<?php
namespace app\models;

use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadFileForm extends Model
{
    /**
    * @var UploadedFile
    */
    public $file;
    public $directory;
    public $url;
    private $uploadsPath;

    public function __construct($config = [])
    {
        $path = DIRECTORY_SEPARATOR . \Yii::$app->session->id . DIRECTORY_SEPARATOR;
        $this->directory = \Yii::getAlias('@webroot/temp') . $path;
        $this->url = '/temp' . $path;
        if (!is_dir($this->directory)) {
            FileHelper::createDirectory($this->directory);
        }
        $this->uploadsPath = \Yii::getAlias('@app/uploads/');

        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [
                ['file'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Прикрепленный файл',
        ];
    }

    public function upload()
    {
        FileHelper::copyDirectory($this->directory, $this->uploadsPath);
        FileHelper::removeDirectory($this->directory);
    }


}