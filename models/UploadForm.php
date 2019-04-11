<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    private $uploadsPath;
    /**
    * @var UploadedFile[]
    */
    public $files;

    public function __construct()
    {
        parent::__construct();
        $this->uploadsPath = \Yii::getAlias('@app/uploads/');
    }

    public function rules()
    {
        return [
            [
                ['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'files' => 'Прикрепленные файлы',
        ];
    }

    public function upload()
    {
        if (empty($this->files)) {
            return true;
        }
        if ($this->validate()) {
            array_map('unlink', glob($this->uploadsPath . '*'));
            foreach ($this->files as $file) {
                $file->saveAs($this->uploadsPath . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}