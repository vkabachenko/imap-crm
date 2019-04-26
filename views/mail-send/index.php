<?php

use yii\grid\GridView;
use app\models\EmployeesAR;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailReplySearch
 */

$this->title = 'Почтовый ящик ' . $mailbox->name . ' исходящая почта';

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="fa
                        fa-envelope-o"></span>',
                        ['mail/reply-view', 'id' => $model->id],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Открыть письмо',
                        ]);
                },
            ],
        ],
        'created_at',
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
            'filter' => EmployeesAR::usersAsMap()
        ],
        'comment',
        [
            'attribute' => 'reply_to_id',
            'label' => '',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EMailReply */
                $html = Html::a('Входящее письмо', ['mail/view', 'id' => $model->reply_to_id]);
                return $html;
            },
        ],
    ],
]); ?>