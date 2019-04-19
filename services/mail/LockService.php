<?php


namespace app\services\mail;


use app\models\Emails;

class LockService
{
    private $lockTime;
    private $userId;

    public function __construct()
    {
        $this->lockTime = \Yii::$app->params['maxTimeForEmailProcessing'];
        $this->userId = \Yii::$app->user->id;
    }


    public function isLocked(Emails $mail)
    {
        if (empty($mail->lock_time)) {
            return false;
        }
        if (is_null($mail->lock_user_id) || $mail->lock_user_id == $this->userId) {
            return false;
        }
        $interval = new \DateInterval('PT' . $this->lockTime . 'S');
        $lockTime = new \DateTime($mail->lock_time);
        $lockTime->add($interval);
        $now = new \DateTime();

        return $now < $lockTime;
    }

    public function lock(Emails $mail)
    {
        if (!$this->isLocked($mail)) {
            $mail->lock_time = date('Y-m-d H:i:s');
            $mail->lock_user_id = $this->userId;
            $mail->save();
        }
    }

    public function release(Emails $mail)
    {
        $mail->lock_time = null;
        $mail->lock_user_id = null;
        $mail->save();
    }

}