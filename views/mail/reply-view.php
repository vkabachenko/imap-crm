<?php

/* @var $model \app\models\EmailReply */

$this->title = 'Ответ на письмо - просмотр';
?>
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