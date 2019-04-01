<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 */

$this->title = 'Почтовый ящик ' . $mailbox->name;

use yii\grid\GridView;
use yii\helpers\Html;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'columns' => [
        'created_at',
        [
            'attribute' => 'status_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $status = $model->status_id ? $model->emailStatus->status : null;
                return $status;
            }
        ],
        [
            'attribute' => 'is_read',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $check = $model->is_read
                    ? '<i class="fa fa-check"></i>' : '';
                return $check;
            }
        ],
        [
            'attribute' => 'manager_id',
            'value' => function ($model) {
                /* @var $model \app\models\EMails */
                $manager = $model->manager_id ? $model->manager->name : null;
                return $manager;
            }
        ],
        'comment'
    ],
]); ?>

