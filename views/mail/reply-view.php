<?php

/* @var $model \app\models\EmailReply */
/* @var $attachmentFileNames array */

$this->title = 'Ответ на письмо - просмотр';

use yii\helpers\Html; ?>

<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-4">
        <?= Html::a('Назад',
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="col-md-4">
        <?= Html::a('Отправить повторно',
            ['mail-send/again', 'id' => $model->id],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

</div>

<?php if ($model->reply_to_id): ?>
    <div>
        <strong style="margin-right: 10px;">Исходное письмо:</strong>
        <?= Html::a('От ' . $model->replyTo->imap_date, ['mail/view', 'id' => $model->reply_to_id]) ?>
    </div>
<?php endif; ?>

<div>
    <strong style="margin-right: 10px;">Дата:</strong>
    <?= $model->created_at ?>
</div>
<div>
    <strong style="margin-right: 10px;">От кого:</strong>
    <?= $model->from ?>
</div>
<div>
    <strong style="margin-right: 10px;">Менеджер:</strong>
    <?= $model->manager->name ?>
</div>
<div>
    <strong style="margin-right: 10px;">Кому:</strong>
    <?= $model->to ?>
</div>
<div>
    <strong style="margin-right: 10px;">Тема:</strong>
    <?= $model->subject ?>
</div>
<div>
    <strong style="margin-right: 10px;">Cодержание:</strong>
</div>
<div style="margin: 10px; font-family: 'Courier New';">
    <?= nl2br($model->content) ?>
</div>
<div>
    <strong style="margin-right: 10px;">Комментарий:</strong>
    <?= $model->comment ?>
</div>

<?php if(!empty($attachmentFileNames)): ?>
    <div>
        <strong style="margin-right: 10px;">Приложения:</strong>
        <?php foreach ($attachmentFileNames as $fileName): ?>
            <?= Html::a($fileName,
                ['mail/download', 'mailId' => $model->id, 'fileName' => $fileName, 'reply' => true],
                ['style' => 'margin-right: 10px;']);
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
