<?php


namespace app\controllers;

use yii\helpers\Json;
use yii\httpclient\Client;

class PortalController extends Controller
{
    public function actionIndex($phone)
    {
        $httpClient = new Client();
        $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params['getClientAction'];

        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['phone' => $phone, 'token' => \Yii::$app->params['portalToken']])
            ->send();

        if ($response->content == 'null') {
            return 'Клиент не найден';
        } else {
            return $this->renderPartial('index', ['response' => Json::decode($response->content)]);
        }
    }

}