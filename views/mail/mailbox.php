<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 * @var $searchModel \app\models\EmailsSearch
 */

$this->title = 'Почтовый ящик ' . $mailbox->name;

use yii\grid\GridView;
use app\models\EmailStatus;
use app\models\EmployeesAR;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => [
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
            'filter' => EmailStatus::emailStatusAsMap()
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




