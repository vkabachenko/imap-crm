<?php


namespace app\controllers;


use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
    use CheckAccessTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

}