<?php

use yii\grid\GridView;
use app\models\MailboxStatus;
use app\models\EmployeesAR;
use yii\helpers\Html;
use app\models\Emails;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailsSearch
 * @var $isDeleted integer|null
 */

$this->title = 'Почтовый ящик ' . $mailbox->name . ' входящая почта' . ($isDeleted ? ' - удаленные' : '');
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
    <div class="col-md-3">
        <?= Html::a('Назад',
            ['mail/index'],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="col-md-4">
        <?= Html::a('Исходящие',
            ['mail-send/index', 'mailboxId' => $mailbox->id],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="col-md-4">
        <?php if (is_null($isDeleted)): ?>
            <?= Html::a('Удаленные',
                ['mail/mailbox', 'mailboxId' => $mailbox->id, 'isDeleted' => true],
                ['class' => 'btn btn-primary']
                ) ?>
        <?php else: ?>
            <?= Html::a('Входящие',
                ['mail/mailbox', 'mailboxId' => $mailbox->id],
                ['class' => 'btn btn-primary']
                ) ?>
        <?php endif; ?>
    </div>
</div>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid)
    {
        if(boolval($model->is_read) === false) {
            return ['class' => 'email-not-read'];
        }
    },
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="fa
                        fa-envelope-o"></span>',
                        ['view', 'id' => $model->id],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Чтение почты',
                            'style' => 'margin-right: 10px;'
                        ]);
                },
                'delete' => function ($url, $model, $key) {
                    if (is_null($model->is_deleted)) {
                        $html=  Html::a('<span class="fa
                                fa-close"></span>',
                            ['delete-mail', 'id' => $model->id],
                            [
                                'data-toggle' => 'tooltip',
                                'title' => 'Удалить',
                                'data' => [
                                    'confirm' => 'Поместить письмо в корзину?'
                                ],
                            ]);
                    } else {
                        $html=  Html::a('<span class="fa
                                fa-undo"></span>',
                            ['delete-mail', 'id' => $model->id],
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
            'attribute' => 'imap_date',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                return \app\helpers\LocalDateTime::convertFromFull($model->getFullTime(), $model->imap_date);
            },
        ],
        [
            'attribute' => 'imap_from',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $html = Html::tag('div', $model->imap_from, ['style'=> 'width: 80px; white-space: normal; word-wrap: break-word']);
                return $html;
            },
        ],
        'imap_to',
        [
            'attribute' => 'imap_subject',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $html = Html::tag('div', $model->imap_subject, ['style'=> 'width: 200px; overflow: hidden;']);
                return $html;
            },
        ],
        [
            'attribute' => 'status_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $status = $model->status_id ? $model->emailStatus->status : null;
                return $status;
            },
            'filter' => MailboxStatus::emailStatusAsMapForGrid($mailbox->id)
        ],
        [
            'attribute' => 'is_read',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $check = $model->is_read
                    ? '<i class="fa fa-check"></i>' : '';
                return $check;
            },
            'filter' => [1 => 'Прочтенные', 0 => 'Не прочтенные'],
        ],
        [
            'attribute' => 'answer_method',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $method = $model->answer_method ? Emails::answerMethods()[$model->answer_method] : null;
                return $method;
            },
            'filter' => Emails::answerMethodsForGrid()
        ],
        [
            'attribute' => 'manager_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $manager = $model->manager_id ? $model->manager->name : null;
                return $manager;
            },
            'filter' => EmployeesAR::usersAsMapForGrid()
        ],
        'comment'
    ],
]); ?>




