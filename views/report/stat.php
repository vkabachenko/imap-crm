<?php

/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \app\models\ReportManagerActivitySearch */
/* @var $dateBegin int */
/* @var $dateEnd int */

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\bootstrap\Html;
use app\models\EmployeesAR;

$this->title = 'Статистика менеджеров';

$columns = [
    [
        'attribute' => 'day',
        'label' => 'Дата',
        'value' => function($model) { return date('d.m.Y', strtotime($model['day'])); },
        'filter' => false
    ],
    [
        'attribute' => 'userId',
        'label' => 'Менеджер',
        'value' => function($model) { return EmployeesAR::findOne($model['userId'])->name; },
        'filter' => Html::activeDropDownList($searchModel, 'userId', EmployeesAR::usersAsMap(), ['prompt' => ''])
    ],
    [
        'attribute' => 'calls_in',
        'label' => 'Звонки входящие',
        'filter' => false
    ],
    [
        'attribute' => 'calls_out',
        'label' => 'Звонки исходящие',
        'filter' => false
    ],
    [
        'attribute' => 'mails_in',
        'label' => 'Письма входящие',
        'filter' => false
    ],
    [
        'attribute' => 'mails_out',
        'label' => 'Письма исходящие',
        'filter' => false
    ],
    [
        'attribute' => 'bid_statuses',
        'label' => 'Статусы заявок',
        'filter' => false
    ],

];

?>

<div class="row form-group">

     <div class="col-md-8">
        <div>
            <label class="control-label">Выберите интервал дат</label>
            <?= DatePicker::widget([
                'name' => 'ReportManagerActivitySearch[dateBegin]',
                'value' => $dateBegin,
                'name2' => 'ReportManagerActivitySearch[dateEnd]',
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
        ['report/stat'],
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
    var dateBegin = $('input[name="ReportManagerActivitySearch[dateBegin]"]').val();
    var dateEnd = $('input[name="ReportManagerActivitySearch[dateEnd]"]').val();
    var url = $(this).attr('href');
    window.location.href = url + '?ReportManagerActivitySearch[dateBegin]=' + dateBegin + '&ReportManagerActivitySearch[dateEnd]=' + dateEnd;
})

JS;

$this->registerJs($script);

