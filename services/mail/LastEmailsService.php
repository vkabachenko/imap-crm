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

    public function getCountLastEmails($mailBoxId = null)
    {
        $query = Emails::find()
            ->select(['mailbox_id', 'COUNT(*) AS cnt'])
            ->where(['>', 'created_at', $this->from])
            ->andWhere(['is_read' => false])
            ->groupBy('mailbox_id');
        if(!is_null($mailBoxId)) {
            $query->andWhere(['mailbox_id' => $mailBoxId]);
        }
        $result = $query->createCommand()->queryAll();
        $result = ArrayHelper::map($result, 'mailbox_id', 'cnt');

        return $result;
    }

}