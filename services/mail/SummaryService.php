<?php


namespace app\services\mail;


use app\models\Emails;

class SummaryService
{
    private $userId;
    private $dateBegin;
    private $dateNow;
    private $dateBeginStr;
    private $dateNowStr;
    private $dateBeginLost;
    private $dateBeginLostStr;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->dateNow = time();
        $this->dateBegin = strtotime("midnight", $this->dateNow);
        $this->dateBeginLost = strtotime("1 week ago", $this->dateBegin);
        $this->dateNowStr = date('Y-m-d H:i:s', $this->dateNow);
        $this->dateBeginStr = date('Y-m-d H:i:s', $this->dateBegin);
        $this->dateBeginLostStr = date('Y-m-d H:i:s', $this->dateBeginLost);
    }

    public function getAllCalls()
    {
        $count = (new \yii\db\Query())
            ->from('calls')
            ->where(['between', 'date', $this->dateBegin, $this->dateNow])
            ->andWhere(['type' => 0])
            ->count();

        return $count;
    }

    public function getLostCalls()
    {
        $count = (new \yii\db\Query())
            ->from('calls')
            ->where(['between', 'date', $this->dateBeginLost, $this->dateNow])
            ->andWhere(['type' => 0])
            ->andWhere(['file' => ''])
            ->count();

        return $count;
    }

    public function getCallsIn()
    {
        $count = (new \yii\db\Query())
            ->from('calls')
            ->where(['between', 'date', $this->dateBegin, $this->dateNow])
            ->andWhere(['type' => 0])
            ->andWhere(['>', 'file', ''])
            ->count();

        return $count;
    }

    public function getCallsInUser()
    {
        $count = (new \yii\db\Query())
            ->from('calls')
            ->innerJoin('sip', 'calls.sip = sip.num')
            ->innerJoin('sip_user', 'sip.id = sip_user.sip_id')
            ->where(['between', 'calls.date', $this->dateBegin, $this->dateNow])
            ->andWhere(['calls.type' => 0])
            ->andWhere(['>', 'calls.file', ''])
            ->andWhere(['sip_user.user_id' => $this->userId])
            ->count();

        return $count;
    }

    public function getAllMails()
    {
        $count = Emails::find()
            ->joinWith('mailbox.mailboxUser', false)
            ->where(['between', 'created_at', $this->dateBeginStr, $this->dateNowStr])
            ->andWhere(['mailbox_user.user_id' => $this->userId])
            ->count();

        return $count;
    }

    public function getLostMails()
    {
        $count = Emails::find()
            ->joinWith('mailbox.mailboxUser', false)
            ->where(['between', 'created_at', $this->dateBeginLostStr, $this->dateNowStr])
            ->andWhere(['mailbox_user.user_id' => $this->userId])
            ->andWhere(['emails.status_id' => null])
            ->count();

        return $count;
    }

    public function getMailsIn()
    {
        $count = Emails::find()
            ->joinWith('mailbox.mailboxUser', false)
            ->where(['between', 'created_at', $this->dateBeginStr, $this->dateNowStr])
            ->andWhere(['mailbox_user.user_id' => $this->userId])
            ->andWhere(['IS NOT', 'emails.status_id', null])
            ->count();

        return $count;
    }

    public function getMailsInUser()
    {
        $count = Emails::find()
            ->joinWith('mailbox.mailboxUser', false)
            ->where(['between', 'created_at', $this->dateBeginStr, $this->dateNowStr])
            ->andWhere(['mailbox_user.user_id' => $this->userId])
            ->andWhere(['IS NOT', 'emails.status_id', null])
            ->andWhere(['emails.manager_id' => $this->userId])
            ->count();

        return $count;
    }

}