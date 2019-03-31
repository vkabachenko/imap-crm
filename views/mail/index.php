<?php
/** @noinspection PhpUnhandledExceptionInspection */

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
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
                'value' => function ($model) {
                    $html = Html::a('10 <i class="fa fa-refresh"></i>',
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
