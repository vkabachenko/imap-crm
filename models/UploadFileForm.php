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
        $this->directory = self::getUploadPath();
        $this->url = self::getUploadUrl();
        if (!is_dir($this->directory)) {
            FileHelper::createDirectory($this->directory);
        }
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

    public static function clear()
    {
        $directory = self::getUploadPath();
        if (is_dir($directory)) {
            FileHelper::removeDirectory($directory);
        }
    }

    public static function getUploadPath()
    {
        $path = DIRECTORY_SEPARATOR . \Yii::$app->session->id . DIRECTORY_SEPARATOR;
        return \Yii::getAlias('@webroot/temp') . $path;
    }

    public static function getUploadUrl()
    {
        $path = DIRECTORY_SEPARATOR . \Yii::$app->session->id . DIRECTORY_SEPARATOR;
        return '/temp' . $path;
    }


}