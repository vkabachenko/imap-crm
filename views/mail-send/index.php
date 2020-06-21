<?php

use yii\grid\GridView;
use app\models\EmployeesAR;
use yii\helpers\Html;
use app\models\EmailReply;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailReplySearch
 * @var $status string|null
 */

$this->title = 'Почтовый ящик ' . $mailbox->name . ' исходящая почта ' . ($status ? EmailReply::statuses()[$status] : '');

\app\assets\GroupDeleteAsset::register($this);

?>

<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <?= \Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <?= \Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="row" style="margin: 10px 0;">
    <div class="col-md-2">
        <?= Html::a('Написать',
            ['mail-send/create', 'mailboxId' => $mailbox->id],
            ['class' => 'btn btn-success']
        ) ?>
    </div>
    <div class="col-md-2" style="display: none">
        <?= Html::a('Удалить выделенные',
            ['mail-send/group-delete'],
            ['class' => 'btn btn-danger group-delete']
        ) ?>
    </div>
    <div class="col-md-2">
        <?= Html::a('Входящие',
            ['mail/mailbox', 'mailboxId' => $mailbox->id],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <?php if ($status !== 'deleted'): ?>
        <div class="col-md-2">
            <?= Html::a('Удаленные',
                ['mail-send/index', 'mailboxId' => $mailbox->id, 'status' => 'deleted'],
                ['class' => 'btn btn-primary']
                ) ?>
        </div>
    <?php endif; ?>
    <?php if ($status !== 'draft'): ?>
        <div class="col-md-2">
            <?= Html::a('Черновики',
                ['mail-send/index', 'mailboxId' => $mailbox->id, 'status' => 'draft'],
                ['class' => 'btn btn-primary']
                ) ?>
        </div>
    <?php endif; ?>
    <?php if (!is_null($status)): ?>
        <div class="col-md-2">
            <?= Html::a('Исходящие',
                ['mail-send/index', 'mailboxId' => $mailbox->id],
                ['class' => 'btn btn-primary']
                ) ?>
        </div>
    <?php endif; ?>
</div>

<?php

if (\Yii::$app->user->identity->is_admin && is_null($status)) {
    $checkedColumn = [
        [
            'header' => Html::checkbox('', false, ['class' => 'all-emails-check']),
            'format' => 'raw',
            'value' => function ($model) {
                return Html::checkbox('', false, ['class' => 'email-check']);
            }
        ]
    ];
} else {
    $checkedColumn = [];
}

$columns = [
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}{delete}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                /* @var $model \app\models\EMailReply */
                $url = $model->status === 'draft'
                    ? ['mail/reply-update', 'id' => $model->id]
                    : ['mail/reply-view', 'id' => $model->id];

                return Html::a('<span class="fa
                        fa-envelope-o"></span>',
                    $url,
                    [
                        'data-toggle' => 'tooltip',
                        'title' => 'Открыть письмо',
                    ]);
            },
            'delete' => function ($url, $model, $key) {
                /* @var $model \app\models\EMailReply */
                if (strval($model->status) !== 'deleted') {
                    $confirm = $model->status === 'draft'
                        ? 'Удалить черновик?'
                        : 'Поместить письмо в корзину?';
                    $html=  Html::a('<span class="fa
                                fa-close"></span>',
                        ['mail-send/delete', 'id' => $model->id],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => $confirm
                            ],
                        ]);
                } else {
                    $html=  Html::a('<span class="fa
                                fa-undo"></span>',
                        ['mail-send/delete', 'id' => $model->id],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Восстановить',
                            'data' => [
                                'confirm' => 'Восстановить письмо из корзины?'
                            ],
                        ]);
                }
                return $html;
            },
        ],
    ],
    [
        'attribute' => 'created_at',
        'value' => function ($model) {
            return $model->created_at;
        },
    ],
    [
        'attribute' => 'from',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model \app\models\EMailReply */
            $html = Html::tag('div', $model->from, ['style'=> 'width: 80px; white-space: normal; word-wrap: break-word']);
            return $html;
        },
    ],
    [
        'attribute' => 'to',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model \app\models\EMailReply */
            $html = Html::tag('div', $model->to, ['style'=> 'width: 80px; white-space: normal; word-wrap: break-word']);
            return $html;
        },
    ],
    [
        'attribute' => 'subject',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model \app\models\EMailReply */
            $html = Html::tag('div', $model->subject, ['style'=> 'width: 200px; overflow: hidden;']);
            return $html;
        },
    ],
    [
        'attribute' => 'manager_id',
        'value' => function ($model) {
            /* @var $model \app\models\EMailReply */
            $manager = $model->manager_id ? $model->manager->name : null;
            return $manager;
        },
        'filter' => EmployeesAR::usersAsMapForGrid()
    ],
    'comment',
    [
        'attribute' => 'reply_to_id',
        'label' => '',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model \app\models\EMailReply */
            $html = $model->reply_to_id
                ? Html::a('Входящее письмо', ['mail/view', 'id' => $model->reply_to_id])
                : '';
            return $html;
        },
    ],
];

$columns = array_merge($checkedColumn, $columns);

?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => $columns,
]); ?>