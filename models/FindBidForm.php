<?php


namespace app\models;


use yii\base\Model;

class FindBidForm extends Model
{
    public $bid1Cnumber;

    public function rules()
    {
        return [
            [['bid1Cnumber'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bid1Cnumber' => 'Номер заявки',
        ];
    }

}