<?php

namespace app\services\portal;

use app\models\RecentCalls;
use yii\helpers\Json;
use yii\httpclient\Client;

class ClientService
{
    private $phone;

    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    public function getClient()
    {
        /* @var $recentCall \app\models\RecentCalls */
        $recentCall = RecentCalls::getByPhoneFrom($this->phone);
        return $recentCall ? $recentCall->client : $this->getClientFromPortal();
    }

    public function getClientFromPortal()
    {
        $httpClient = new Client();
        $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params['getClientAction'];

        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['phone' => $this->phone, 'token' => \Yii::$app->params['portalToken']])
            ->send();

        if ($response->content == 'null') {
            return null;
        } else {
            return Json::decode($response->content);
        }
    }

}