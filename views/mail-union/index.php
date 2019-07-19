<?php

use app\models\EmailReply;
use yii\grid\GridView;
use app\models\EmployeesAR;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\LocalDateTime;
use app\models\EmailsUnionSearch;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\models\EmailsUnionSearch
 */

$this->title = 'Вся почта ';
?>

<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <?= \Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <?= \Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="row" style="margin: 10px 0;">
    <div class="col-md-3">
        <?= Html::a('Назад',
            ['mail/index'],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>
    <div class="col-md-3 col-md-offset-6">
        <?= Html::beginForm('','GET') ?>
        <?= Html::textInput('EmailsUnionSearch[content]', $searchModel['content'], [
            'class' => 'form-control',
            'placeholder' => 'Поиск в письме'
        ]) ?>
        <?= Html::submitInput('', ['style' => 'display: none;']) ?>
        <?= Html::endForm() ?>
    </div>
</div>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}&nbsp;{delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    if ($model['model'] === 'emails') {
                        $url = ['mail/view', 'id' => $model['id']];
                    } else {
                        $url = $model['status'] === 'draft'
                            ? ['mail/reply-update', 'id' => $model['id']]
                            : ['mail/reply-view', 'id' => $model['id']];
                    }
                    return Html::a('<span class="fa
                        fa-envelope-o"></span>',
                        $url,
                        [
                            'data-toggle' => 'tooltip',
                            'title' => 'Открыть письмо',
                        ]);
                },
                'delete' => function ($url, $model, $key) {
                    $url = $model['model'] === 'emails'
                        ? ['mail/delete-mail', 'id' => $model['id']]
                        : ['mail-send/delete', 'id' => $model['id']];
                    if (strval($model['status']) !== 'deleted') {
                        $confirm = $model['status'] === 'draft'
                            ? 'Удалить черновик?'
                            : 'Поместить письмо в корзину?';
                        $html=  Html::a('<span class="fa
                                fa-close"></span>',
                            $url,
                            [
                                'data-toggle' => 'tooltip',
                                'title' => 'Удалить',
                                'data' => [
                                    'confirm' => $confirm
                                ],
                            ]);
                    } else {
                        $html=  Html::a('<span class="fa
                                fa-undo"></span>',
                            $url,
                            [
                                'data-toggle' => 'tooltip',
                                'title' => 'Восстановить',
                                'data' => [
                                    'confirm' => 'Восстановить письмо из корзины?'
                                ],
                            ]);
                    }
                    return $html;
                },
            ],
        ],
        [
            'attribute' => 'model',
            'label' => 'Почта',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                $icon = $model['model'] === 'emails' ? 'fa fa-arrow-right' : 'fa fa-arrow-left';
                $html = Html::tag('i', '', ['class' => $icon]);
                return $html;
            },
            'filter' => EmailsUnionSearch::models()
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Дата письма',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                if ($model['model'] === 'emails') {
                    $content = Json::decode($model['content']);
                    $date = LocalDateTime::convertFromFull($content['headers']['date'], $model['created_at']);
                } else {
                    $date = LocalDateTime::convertFromUtc($model['created_at']);
                }
                return $date;
            },
        ],
        [
            'attribute' => 'from',
            'label' => 'От кого',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                $html = Html::tag('div', $model['from'], ['style'=> 'width: 80px; white-space: normal; word-wrap: break-word']);
                return $html;
            },
        ],
        [
            'attribute' => 'to',
            'label' => 'Кому',
            'format' => 'raw',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                $html = Html::tag('div', $model['to'], ['style'=> 'width: 80px; white-space: normal; word-wrap: break-word']);
                return $html;
            },
        ],
        [
            'attribute' => 'subject',
            'format' => 'raw',
            'label' => 'Тема',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                $html = Html::tag('div', $model['subject'], ['style'=> 'width: 200px; overflow: hidden;']);
                return $html;
            },
        ],
        [
            'attribute' => 'comment',
            'label' => 'Комментарий',
        ],
        [
            'attribute' => 'manager_id',
            'label' => 'Менеджер',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                if ($model['manager_id']) {
                    $manager = EmployeesAR::findOne($model['manager_id']);
                    return $manager->name;
                } else {
                    return null;
                }
            },
            'filter' => EmployeesAR::usersAsMapForGrid()
        ],
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'value' => function ($model) {
                /* @var $model \app\models\EmailsUnionSearch */
                $status = $model['status'] ? EmailReply::statuses()[$model['status']] : '';
                return $status;
            },
            'filter' => EmailReply::statuses()
        ],
    ],
]); ?>





