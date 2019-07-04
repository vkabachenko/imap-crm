<?php

use yii\grid\GridView;
use app\models\EmailReply;
use app\models\EmployeesAR;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailsReplyAllSearch
 */

$this->title = 'Исходящая почта всех почтовых ящиков';
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
    <div class="col-md-4">
        <?= Html::a('Назад',
            ['mail/index'],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="col-md-offset-4 col-md-4">
        <?= Html::beginForm('','GET') ?>
        <?= Html::textInput('EmailsReplyAllSearch[content]', $searchModel['content'], [
            'class' => 'form-control',
            'placeholder' => 'Поиск в письме'
        ]) ?>
        <?= Html::submitInput('', ['style' => 'display: none;']) ?>
        <?= Html::endForm() ?>
    </div>
</div>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => [
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
            'attribute' => 'mailbox_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMailReply */
                return $model->mailbox->name;
            },
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return \app\helpers\LocalDateTime::convertFromUtc($model->created_at);
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
            'attribute' => 'status',
            'value' => function ($model) {
                /* @var $model \app\models\EMailReply */
                $status = $model->status ? EmailReply::statuses()[$model->status] : '';
                return $status;
            },
            'filter' => EmailReply::statuses()
        ],
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
    ],
]); ?>




