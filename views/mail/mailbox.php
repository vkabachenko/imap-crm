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
 */

$this->title = 'Почтовый ящик ' . $mailbox->name . ' входящая почта';
?>

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
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="fa
                        fa-envelope-o"></span>',
                        ['view', 'id' => $model->id],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Чтение почты',
                        ]);
                },
            ],
        ],
        'imap_date',
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
            'filter' => MailboxStatus::emailStatusAsMap($mailbox->id)
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
            'filter' => Emails::answerMethods()
        ],
        [
            'attribute' => 'manager_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $manager = $model->manager_id ? $model->manager->name : null;
                return $manager;
            },
            'filter' => EmployeesAR::usersAsMap()
        ],
        'comment'
    ],
]); ?>




