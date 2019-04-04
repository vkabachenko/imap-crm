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
            'comment'
        ],
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
