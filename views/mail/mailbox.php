<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailsSearch
 */

$this->title = 'Почтовый ящик ' . $mailbox->name;

use yii\grid\GridView;
use app\models\MailboxStatus;
use app\models\EmployeesAR;
use yii\helpers\Html;

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
        'imap_from',
        'imap_to',
        'imap_subject',
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




