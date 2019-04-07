<?php

use yii\helpers\Html;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model \app\models\EmailStatus */


$this->title = 'Новый статус';

?>
<div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
