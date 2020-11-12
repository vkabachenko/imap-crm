<?php

/* @var $dataProvider \yii\data\ArrayDataProvider */

use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = 'Рекламные кампании';

$columns = [
    [
        'header' => 'Дата',
        'value' => function ($model) {
            return date('d-m-Y H:i', $model['date']);
        }
    ],
    [
        'header' => 'Тел исходящий',
        'value' => function ($model) {
            return $model['tel_from'];
        }
    ],
    [
        'header' => 'Тел входящий',
        'value' => function ($model) {
            return $model['tel_to'];
        }
    ],
    [
        'header' => 'Кампания',
        'value' => function ($model) {
            return $model['campaign'];
        }
    ],
    [
        'header' => 'Поисковый сайт',
        'value' => function ($model) {
            return $model['search_engine'];
        }
    ],
    [
        'header' => 'Поисковый запрос',
        'value' => function ($model) {
            return $model['search_query'];
        }
    ],
    [
        'header' => 'UTM запрос',
        'value' => function ($model) {
            return $model['utm_term'];
        }
    ],
];

?>

<div>
    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'dropdownOptions' => [
            'label' => 'Экспорт',
            'class' => 'btn btn-outline-secondary'
        ]
    ]) ?>
<hr/>

   <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    ]) ?>


</div>
