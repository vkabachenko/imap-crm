<?php
/** @noinspection PhpUnhandledExceptionInspection */

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $counts array
 */

$this->title = 'Почтовые ящики';
?>
<div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                   /* @var $model \app\models\Mails */
                   return Html::a($model->name, ['mail/mailbox', 'mailboxId' => $model->id]);
                }
            ],
            [
                'header' => 'Новых писем',
                'format' => 'raw',
                'value' => function ($model) use ($counts) {
                    $count = isset($counts[$model->id]) ? $counts[$model->id] : 0;
                    $html = Html::a($count . ' <i class="fa fa-refresh"></i>',
                        '#',
                        ['class' => 'btn btn-primary mailbox-new-letters',
                         'data-mailboxId' => $model->id
                        ]);
                    return $html;
                },
            ],
            'comment'
        ],
    ]); ?>
</div>
