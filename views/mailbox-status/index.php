<?php
/** @noinspection PhpUnhandledExceptionInspection */

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailboxId int
 */

$this->title = 'Статусы писем';
?>
<div>
        <div style="margin-bottom: 10px;">
            <?= Html::a('Создать', ['create', 'mailboxId' => $mailboxId], ['class' => 'btn btn-success']); ?>
            <?= Html::a('Копировать общие статусы',
                ['copy', 'mailboxId' => $mailboxId],
                ['class' => 'btn btn-primary', 'style' => 'margin-left: 20px;']); ?>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
                'status',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-edit"></span>',
                                ['update', 'id' => $model->id, 'mailboxId' => $model->mailbox_id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Редактировать',
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-close"></span>',
                                ['delete', 'id' => $model->id, 'mailboxId' => $model->mailbox_id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Удалить',
                                    'data' => [
                                        'confirm' => 'Удалить статус?'
                                    ]
                                ]);
                        },
                    ],
                ]
        ]
    ]); ?>
</div>
