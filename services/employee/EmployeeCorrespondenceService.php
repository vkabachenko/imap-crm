<?php


namespace app\services\employee;


use app\models\EmployeeCorrespondence;
use app\models\EmployeesAR;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;

class EmployeeCorrespondenceService
{
    private $importedUsers;

    public function __construct()
    {
        $this->importedUsers = $this->getEmployeesFromPortal();
    }

    public function getEmployeesFromPortal()
    {
        $httpClient = new Client();
        $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params['getImportedAuthors'];

        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['token' => \Yii::$app->params['portalToken']])
            ->send();

        if ($response->content == 'null') {
            return [];
        } else {
            return Json::decode($response->content);
        }
    }

    public function getExistingEmployeesCorrespondence()
    {
        $employeesCorrespondence = EmployeeCorrespondence::find()
            ->all();

        return $employeesCorrespondence;
    }

    public function getInputEmployeesCorrespondence()
    {
        $inputEmployeesCorrespondence = $this->getExistingEmployeesCorrespondence();
        $existingUsers = ArrayHelper::getColumn($inputEmployeesCorrespondence, 'user_imported');
        $diff = array_diff($this->importedUsers, $existingUsers);
        foreach ($diff as $user) {
            $inputEmployeesCorrespondence[] = new EmployeeCorrespondence(['user_imported' => $user]);
        }

        ArrayHelper::multisort($inputEmployeesCorrespondence, 'user_imported');

        return $inputEmployeesCorrespondence;
    }

}