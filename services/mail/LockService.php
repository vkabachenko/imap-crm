<?php


namespace app\services\mail;


use app\models\Emails;

class LockService
{
    private $lockTime;

    public function __construct()
    {
        $this->lockTime = \Yii::$app->params['maxTimeForEmailProcessing'];
    }


    public function isLocked(Emails $mail)
    {
        if (empty($mail->lock_time)) {
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
        $mail->lock_time = date('Y-m-d H:i:s');
        $mail->save();
    }

    public function release(Emails $mail)
    {
        $mail->lock_time = null;
        $mail->save();
    }

}