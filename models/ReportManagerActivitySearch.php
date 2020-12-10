<?php

namespace app\models;


use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\httpclient\Client;

class ReportManagerActivitySearch extends Model
{
    public $dateBegin;
    public $dateEnd;
    public $userId;

    private function setDates()
    {
        if (!empty($this->dateEnd)) {
            $this->dateEnd = \DateTime::createFromFormat('d-m-Y', $this->dateEnd);
            $this->dateEnd = $this->dateEnd->getTimestamp();
        } else {
            $this->dateEnd = strtotime('-1 day');
        }
        $this->dateEnd = strtotime("tomorrow", $this->dateEnd) - 1;

        if (!empty($this->dateBegin)) {
            $this->dateBegin = \DateTime::createFromFormat('d-m-Y', $this->dateBegin);
            $this->dateBegin = $this->dateBegin->getTimestamp();
        } else {
            $this->dateBegin = $this->dateEnd;
        }
        $this->dateBegin = strtotime("today", $this->dateBegin);
    }

    public function rules()
    {
        return [
            [[
                'dateBegin',
                'dateEnd',
                'userId'
            ],
                'safe'],
        ];
    }

    public function search($params)
    {
        $this->load($params);

        $this->setDates();

        $inCalls = $this->getCalls(0);
        $outCalls = $this->getCalls(1);
        $inMails = $this->getInMails();
        $outMails = $this->getOutMails();
        $bidStatuses = $this->getBidStatusesInfo('getBidStatuses');
        $bidsCreated = $this->getBidStatusesInfo('getBidsCreated');

        $combined = [
            'calls_in' => $inCalls,
            'calls_out' => $outCalls,
            'mails_in' => $inMails,
            'mails_out' => $outMails,
            'bid_statuses' => $bidStatuses,
            'bids_created' => $bidsCreated
        ];

        $rows = $this->gatherData($combined);

        if ($this->userId) {
            $rows = array_filter($rows, function($row) {return $row['userId'] == $this->userId;});
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $rows,
            'sort' => [
                'attributes' => [
                    'userId',  'calls_in', 'calls_out', 'mails_in', 'mails_out', 'bid_statuses', 'bids_created',
                    'day' => [
                        'asc' => ['date' => SORT_ASC],
                        'desc' => ['date' => SORT_DESC]
                    ],
                    ],
                'defaultOrder' => ['day' => SORT_DESC]
            ]
        ]);

        return $dataProvider;
    }


    private function getCalls($type)
    {
        $calls = (new \yii\db\Query())
            ->from('calls')
            ->select(['cnt' => 'COUNT(calls.id)', 'day' => 'FROM_UNIXTIME(calls.date, "%Y-%m-%d")', 'userId' => 'employees.id'])
            ->innerJoin('sip', 'calls.sip = sip.num')
            ->innerJoin('sip_user', 'sip.id = sip_user.sip_id')
            ->innerJoin('employees', 'sip_user.user_id = employees.id')
            ->where(['between', 'calls.date', $this->dateBegin, $this->dateEnd])
            ->andWhere(['<>', 'sip_user.user_id', 1])
            ->andWhere(['calls.type' => $type])
            ->andWhere(['>', 'calls.file', ''])
            ->groupBy(['day', 'employees.id'])
            ->all();

        return $calls;
    }

    private function getInMails()
    {
        $mails = (new \yii\db\Query())
            ->from('emails')
            ->select(['cnt' => 'COUNT(emails.id)', 'day' => 'DATE(emails.updated_at)', 'userId' => 'emails.manager_id'])
            ->where(['between', 'emails.updated_at', date('Y-m-d H:i:s', $this->dateBegin), date('Y-m-d H:i:s', $this->dateEnd)])
            ->andWhere(['IS NOT', 'emails.manager_id', null])
            ->andWhere(['emails.is_read' => 1])
            ->groupBy(['day', 'userId'])
            ->all();

        return $mails;
    }

    private function getOutMails()
    {
        $mails = (new \yii\db\Query())
            ->from('email_reply')
            ->select(['cnt' => 'COUNT(email_reply.id)', 'day' => 'DATE(email_reply.created_at)', 'userId' => 'email_reply.manager_id'])
            ->where(['between', 'email_reply.created_at', date('Y-m-d H:i:s', $this->dateBegin), date('Y-m-d H:i:s', $this->dateEnd)])
            ->andWhere(['IS NOT', 'email_reply.manager_id', null])
            ->groupBy(['day', 'userId'])
            ->all();

        return $mails;
    }

    private function getBidStatusesInfo($action)
    {
        $httpClient = new Client();
        $url = \Yii::$app->params['portalUrl'] . \Yii::$app->params[$action];

        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['DateBegin' => $this->dateBegin, 'DateEnd' => $this->dateEnd, 'token' => \Yii::$app->params['portalToken']])
            ->send();

        if ($response->content == 'null') {
            $items = [];
        } else {
            $items = Json::decode($response->content);
        }

        $correspondence = EmployeeCorrespondence::find()
            ->select(['employee_id', 'user_imported'])
            ->indexBy('user_imported')
            ->column();

        $rows = [];
        foreach ($items as $item) {
            if (isset($correspondence[$item['author']])) {
                $rows[] = ['cnt' => $item['cnt'], 'day' => $item['day'], 'userId' => $correspondence[$item['author']]];
            }
        }

        return $rows;
    }

    private function gatherData($combined)
    {
        $days = $this->getDays();
        $users = array_keys(EmployeesAR::usersAsMap());

        $rows = $this->getGatheredInitial($days, $users);

        foreach ($rows as $index => &$row) {
            foreach ($combined as $item => $values) {
                $row[$item] = '';
                foreach ($values as $value) {
                    $key = sprintf('%s-%s', $value['day'], $value['userId']);
                    if ($key === $index) {
                        $row[$item] = $value['cnt'];
                    }
                }
            }
        }

        return $rows;
    }

    private function getDays()
    {
        $days = [];
        $date = $this->dateBegin;
        while ($date < $this->dateEnd) {
            $days[] = date('Y-m-d', $date);
            $date = strtotime('+1 day', $date);
        }
        return $days;
    }

    private function getGatheredInitial($days, $users)
    {
        $gathered = [];
        foreach ($days as $day) {
            foreach ($users as $user) {
                $key = sprintf('%s-%s', $day, $user);
                $gathered[$key] = ['day' => $day, 'userId' => $user];
            }
        }

        return $gathered;
    }

}