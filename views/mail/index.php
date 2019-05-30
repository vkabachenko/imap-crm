<?php
/** @noinspection PhpUnhandledExceptionInspection */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $counts array
 */

$this->title = 'Почтовые ящики';
?>
<div>
        <div class="row" style="margin-bottom: 10px;">
            <?php if(\Yii::$app->user->identity->is_admin): ?>
                <div class="col-md-4">
                    <?= Html::a('Создать', ['mail/create'], ['class' => 'btn btn-success']); ?>
                </div>
            <?php endif; ?>
            <div class="col-md-4">
                <?= Html::a('Все ящики', ['mail-all/index'], ['class' => 'btn btn-primary']); ?>
            </div>
        </div>

    <?php
        $fieldColumns = [
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
                    $html = Html::a('<span class="count-mails">'
                        . $count
                        . '</span>'
                        . ' <i class="fa fa-refresh"></i>',
                        Url::to(['mail/get-recent', 'mailboxId' => $model->id]),
                        ['class' => 'btn btn-primary mailbox-new-letters'
                        ]);
                    return $html;
                },
            ],
            'comment',
        ];
        $actionColumn = [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{status}{delete}{send}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-edit"></span>',
                                ['update', 'mailboxId' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Редактировать',
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-close"></span>',
                                ['delete', 'mailboxId' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Удалить',
                                    'data' => [
                                        'confirm' => 'Удалить безвозвратно почтовый ящик и все письма?'
                                    ],
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                        'status' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-plus-square-o"></span>',
                                ['mailbox-status/index', 'mailboxId' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Статус',
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                        'send' => function ($url, $model, $key) {
                            return Html::a('<span class="fa
                                fa-send-o"></span>',
                                ['mail-send/index', 'mailboxId' => $model->id],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Отправленные',
                                    'style' => 'margin-right: 10px;'
                                ]);
                        },
                    ],
                ]
        ];

        $columns = \Yii::$app->user->identity->is_admin ? array_merge($fieldColumns, $actionColumn) : $fieldColumns;
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => $columns
    ]); ?>
</div>

<?php
$script = <<<JS
$('.mailbox-new-letters').click(function(evt){
  evt.preventDefault();
  var self = $(this);
  self.css('opacity', 0.3);
  self.prop('disabled', true);
  $.ajax({
      url: self.attr('href'),
      method: 'GET'
  }).then(function(response) {
        self.css('opacity', 1);
        self.prop('disabled', false);
        self.find('.count-mails').text(response);
  });
});

JS;

$this->registerJs($script);
