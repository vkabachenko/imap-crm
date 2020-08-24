<?php

/* @var $response array */
/* @var $phone string */
/* @var $this \yii\web\View */

use yii\bootstrap\Html;
?>

<?= $this->render('index', ['response' => $response]) ?>

<div>
    <?= Html::a('Добавить телефон',
        ['portal/add-phone', 'clientId' => $response['id'], 'phone' => $phone],
        ['class' =>'btn btn-success btn-add-phone'])
    ?>
</div>

