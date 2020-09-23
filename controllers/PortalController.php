<?php


namespace app\controllers;

use app\models\FindBidForm;
use app\services\portal\ClientService;
use yii\helpers\Json;
use yii\httpclient\Client;

class PortalController extends Controller
{
    public function actionIndex($phone)
    {
        $clientService = new ClientService($phone);
        $client = $clientService->getClientFromPortal();

        if ($client === null) {
            return $this->renderAjax('no-client', ['findBidForm' => new FindBidForm(), 'phone' => $phone]);
        } else {
            return $this->renderPartial('index', ['response' => $client]);
        }
    }

    public function actionFindBid($phone)
    {
        $findBidForm = new FindBidForm();
        if ($findBidForm->load(\Yii::$app->request->post())) {
            $httpClient = new Client();
            $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params['getBidAction'];

            $response = $httpClient->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->setData(['bid_1C_number' => $findBidForm->bid1Cnumber, 'token' => \Yii::$app->params['portalToken']])
                ->send();

            if ($response->content == 'null') {
                return 'Заявка не найдена или в заявке нет клиента';
            } else {
                return $this->renderPartial('client-bid', [
                    'phone' => $phone, 'response' => Json::decode($response->content)
                ]);
            }
        } else {
            throw new \DomainException('bid number not set');
        }
    }

    public function actionAddPhone($clientId, $phone)
    {
        $httpClient = new Client();
        $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params['addPhoneAction'];

        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['clientId' => $clientId, 'phone' => $phone, 'token' => \Yii::$app->params['portalToken']])
            ->send();

        if ($response->content == 'null') {
            throw new \DomainException('client not found');
        } else {
            return $this->renderPartial('index', ['response' => Json::decode($response->content)]);
        }

    }

}