<?php

namespace app\components;

use yii\validators\Validator;
use yii\validators\EmailValidator;

class EmailsValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $emailValidator = new EmailValidator();
        $error = null;
        $emails = preg_split("/,[\s]*/", $model->$attribute);

        foreach ($emails as $email) {
            if (!$emailValidator->validate($email, $error)) {
                $this->addError($model, $email, $error);
            }
        }
    }

}