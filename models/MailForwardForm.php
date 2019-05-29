<?php

namespace app\models;

use app\components\EmailsValidator;
use yii\base\Model;

class MailForwardForm extends Model
{
    public $to;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['to'], 'required'],
            ['to', 'string'],
            ['to', EmailsValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'to' => 'Адреса для пересылки через запятую',
        ];
    }

}
