<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $mailbox \app\models\Mails
 */

$this->title = 'Почтовый ящик ' . $mailbox->name;

use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'columns' => [
        'created_at',
        'comment'
    ],
]); ?>

