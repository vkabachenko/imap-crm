<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\MailboxStatus;
use yii\helpers\Url;
use app\models\Emails;
use app\helpers\LocalDateTime;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $mail \app\models\Emails */
/* @var $attachmentFileNames array */
/* @var $replyEmails \app\models\EmailReply[] */
/* @var $threadEmail \app\models\EmailReply */
/* @var $isLocked boolean */

$this->title = 'Полученное письмо';

?>

<div class="row">
    <div class="col-md-2"><strong>Дата</strong></div>
    <div class="col-md-10"><?= LocalDateTime::convertFromFull($mail->getFullTime(), $mail->imap_date) ?></div>
</div>
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
    <?= $mail->content ?>
</div>

<?php if(!empty($attachmentFileNames)): ?>
    <div>
        <strong style="margin-left: 10px;">Приложения:</strong>
        <?php foreach ($attachmentFileNames as $fileName): ?>
            <div>
                <?= Html::a($fileName,
                    ['mail/download', 'mailId' => $mail->id, 'fileName' => $fileName]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if(!empty($threadEmail)): ?>
    <div>
        <div>
            <strong>Исходное письмо:</strong>
        </div>
        <div>
            <?= Html::a('От ' . $threadEmail->created_at, ['mail/reply-view', 'id' => $threadEmail->id]) ?>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($replyEmails)): ?>
    <div>
        <div>
            <strong>Отправленные письма:</strong>
        </div>
        <?php foreach ($replyEmails as $replyEmail): ?>
            <div>
                <?= Html::a('От ' . $replyEmail->created_at,
                    ['mail/reply-view', 'id' => $replyEmail->id]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!$isLocked && is_null($mail->is_deleted)): ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($mail, 'status_id')
        ->dropDownList(MailboxStatus::emailStatusAsMap($mail->mailbox_id),['prompt' => 'Выбор']); ?>

    <?= $form->field($mail, 'answer_method')
        ->dropDownList(Emails::answerMethods(),['prompt' => 'Выбор']); ?>

    <?= $form->field($mail, 'comment')->textarea(); ?>

    <div class="row">
        <div class="form-group col-md-3">
            <?= Html::a('Назад',
                Url::previous(),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>
        <div class="form-group col-md-3">
            <?= Html::submitButton('Сохранить',
            ['class' => 'btn btn-success']) ?>
        </div>
        <div class="form-group col-md-3">
            <?= Html::a('Ответить',
                Url::to(['mail/reply', 'id' => $mail->id]),
                [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'method' => 'post'
                    ]
                ]
            ) ?>
        </div>
        <div class="form-group col-md-3">
            <?= Html::a('Переслать',
                Url::to(['mail-forward/index', 'mailId' => $mail->id]),
                [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post'
                    ]
                ]
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php else: ?>
    <?php if($isLocked): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                С письмом работает <?= $mail->lockUser->name ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::a('Назад',
            Url::previous(),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
<?php endif; ?>

