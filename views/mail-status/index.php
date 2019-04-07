<?php
/** @noinspection PhpUnhandledExceptionInspection */

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Общие статусы писем';
?>
<div>
        <div style="margin-bottom: 10px;">
            <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']); ?>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
                'status',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-edit"></span>',
                                ['update', 'id' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Редактировать',
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-close"></span>',
                                ['delete', 'id' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Удалить',
                                    'data' => [
                                        'confirm' => 'Удалить статус?'
                                    ]
                                ]);
                        },
                    ],
                ]
        ]
    ]); ?>
</div>
