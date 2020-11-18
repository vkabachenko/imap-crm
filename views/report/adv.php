<?php

/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \app\models\ReportAdvSearch */
/* @var $dateBegin int */
/* @var $dateEnd int */

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\date\DatePicker;

$this->title = 'Рекламные кампании';

$columns = [
    [
        'attribute' => 'day',
        'label' => 'Дата',
        'filter' => false
    ],
    [
        'attribute' => 'time',
        'label' => 'Время',
        'filter' => false
    ],
    [
        'attribute' => 'tel_from',
        'label' => 'Тел исходящий',
    ],
    [
        'attribute' => 'tel_to',
        'label' => 'Тел входящий',
    ],
    [
        'attribute' => 'campaign',
        'label' => 'Кампания',
    ],
    [
        'attribute' => 'search_engine',
        'label' => 'Поисковый сайт',
    ],
    [
        'attribute' => 'search_query',
        'label' => 'Поисковый запрос',
    ],
    [
        'attribute' => 'utm_term',
        'label' => 'UTM запрос',
    ],
];

?>

<div class="row form-group">

     <div class="col-md-8">
        <div>
            <label class="control-label">Выберите интервал дат</label>
            <?= DatePicker::widget([
                'name' => 'ReportAdvSearch[dateBegin]',
                'value' => $dateBegin,
                'name2' => 'ReportAdvSearch[dateEnd]',
                'value2' => $dateEnd,
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
            ]) ?>
        </div>
     </div>

    <div class="col-md-4" style="padding-top: 25px;">
        <?= \yii\bootstrap\Html::a(
            'Применить',
        ['report/adv'],
        ['class' => 'btn btn-date-filter btn-success']
        ) ?>
    </div>
</div>

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
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]) ?>


</div>

<?php
$script = <<<JS
$('.btn-date-filter').click(function(evt) {
    evt.preventDefault();
    var dateBegin = $('input[name="ReportAdvSearch[dateBegin]"]').val();
    var dateEnd = $('input[name="ReportAdvSearch[dateEnd]"]').val();
    var url = $(this).attr('href');
    window.location.href = url + '?ReportAdvSearch[dateBegin]=' + dateBegin + '&ReportAdvSearch[dateEnd]=' + dateEnd;
})

JS;

$this->registerJs($script);

