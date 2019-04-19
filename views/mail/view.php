<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\MailboxStatus;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $mail \app\models\Emails */
/* @var $content array */
/* @var $textEmail string */
/* @var $attachmentFileNames array */
/* @var $replyEmails \app\models\EmailReply */
/* @var $isLocked boolean */

$this->title = 'Полученное письмо';

?>

<div class="row">
    <div class="col-md-2"><strong>От кого</strong></div>
    <div class="col-md-10"><?= $mail->imap_from ?></div>
</div>
<div class="row">
    <div class="col-md-2"><strong>Кому</strong></div>
    <div class="col-md-10"><?= $mail->imap_to ?></div>
</div>
<div class="row">
    <div class="col-md-2"><strong>Тема</strong></div>
    <div class="col-md-10"><?= $mail->imap_subject ?></div>
</div>
<div class="row" style="margin: 10px; font-family: 'Courier New', Courier, monospace">
    <?= $textEmail ?>
</div>

<?php if(!empty($attachmentFileNames)): ?>
    <div>
        <strong style="margin-right: 10px;">Приложения:</strong>
        <?php foreach ($attachmentFileNames as $fileName): ?>
            <?= Html::a($fileName,
                ['mail/download', 'mailId' => $mail->id, 'fileName' => $fileName],
                ['style' => 'margin-right: 10px;']);
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if(!empty($replyEmails)): ?>
    <div>
        <div>
            <strong>Отправленные письма:</strong>
        </div>
        <?php foreach ($replyEmails as $replyEmail): ?>
            <div>
                <?= Html::a('от ' . $replyEmail->created_at,
                    ['mail/reply-view', 'id' => $replyEmail->id]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!$isLocked): ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($mail, 'status_id')
        ->dropDownList(MailboxStatus::emailStatusAsMap($mail->mailbox_id),['prompt' => 'Выбор']); ?>

    <?= $form->field($mail, 'comment')->textarea(); ?>

    <div class="row">
        <div class="form-group col-md-4">
            <?= Html::submitButton('OK',
            ['class' => 'btn btn-success']) ?>
        </div>
        <div class="form-group col-md-4">
            <?= Html::a('Отмена',
                Url::to(['mail/release-mail', 'mailId' => $mail->id]),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>
        <div class="form-group col-md-4">
            <?= Html::a('Ответить',
                Url::to(['mail/reply', 'id' => $mail->id]),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php else: ?>

    <div>
        <div class="form-group">
            <?= Html::a('Назад',
                Url::to(['mail/mailbox', 'mailboxId' => $mail->mailbox_id]),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>
    </div>

<?php endif; ?>

