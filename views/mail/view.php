<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EmailStatus;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $mail \app\models\Emails */
/* @var $content array */
/* @var $textEmail string */

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

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($mail, 'status_id')
    ->dropDownList(EmailStatus::emailStatusAsMap(),['prompt' => 'Выбор']); ?>

<?= $form->field($mail, 'comment')->textarea(); ?>

<div class="row">
    <div class="form-group col-md-4">
        <?= Html::submitButton('OK',
        ['class' => 'btn btn-success']) ?>
    </div>
    <div class="form-group col-md-4">
        <?= Html::a('Отмена',
            Url::to(['mail/mailbox', 'mailboxId' => $mail->mailbox_id]),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="form-group col-md-4">
        <?= Html::a('Ответить',
            Url::to(['mail/reply', 'mailboxId' => $mail->mailbox_id]),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    </div>

<?php ActiveForm::end(); ?>

