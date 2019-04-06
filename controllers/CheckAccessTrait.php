<?php

namespace app\controllers;

use app\models\Emails;
use app\models\MailboxUser;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

trait CheckAccessTrait
{
    public function checkAdmin()
    {
        if (!\Yii::$app->user->identity->is_admin) {
            throw new UnauthorizedHttpException('This page is not accepted for this user');
        }
    }

    public function checkAccessToMailbox($mailboxId) {
        $found = MailboxUser::find()
            ->where(
                [
                    'user_id' => \Yii::$app->user->id,
                    'mailbox_id' => $mailboxId
                ]
            )
            ->exists();
        if (!$found) {
            throw new UnauthorizedHttpException('This page is not accepted for this user');
        }
    }

    public function checkAccessToMail($mailId)
    {
        $mailModel = Emails::findOne($mailId);
        if (is_null($mailModel)) {
            throw new NotFoundHttpException('mail not found');
        }
        $this->checkAccessToMailbox($mailModel->mailbox_id);
    }
}
