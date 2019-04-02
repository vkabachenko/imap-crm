<?php


namespace app\services\mail;


use app\models\Emails;
use yii\helpers\ArrayHelper;

class LastEmailsService
{
    private $from;

    public function __construct()
    {
        // current date 00:00:00
        $this->from = date('Y-m-d H:i:s', strtotime('today'));
    }

    public function getLastEmailsQuery($mailBoxId = null)
    {
        $query = Emails::find()
            ->with(['emailStatus', 'manager'])
            ->where(['>', 'created_at', $this->from])
            ->orderBy(['mailbox_id' => SORT_ASC, 'created_at' => SORT_DESC, 'imap_date' => SORT_DESC]);
        if(!is_null($mailBoxId)) {
            $query->andWhere(['mailbox_id' => $mailBoxId]);
        }
        return $query;
    }

    public function getCountLastEmails($mailBoxId = null)
    {
        $query = Emails::find()
            ->select(['mailbox_id', 'COUNT(*) AS cnt'])
            ->where(['>', 'created_at', $this->from])
            ->groupBy('mailbox_id');
        if(!is_null($mailBoxId)) {
            $query->andWhere(['mailbox_id' => $mailBoxId]);
        }
        $result = $query->createCommand()->queryAll();
        $result = ArrayHelper::map($result, 'mailbox_id', 'cnt');

        return $result;
    }

}