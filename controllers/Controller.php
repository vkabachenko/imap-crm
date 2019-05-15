<?php


namespace app\controllers;


use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
    use CheckAccessTrait;

    public function beforeAction($action)
    {
        \Yii::$container->set('app\services\path\PathInterface', 'app\services\path\XmlMailPath');
        return parent::beforeAction($action);
    }

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