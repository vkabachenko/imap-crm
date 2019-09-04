<?php

use yii\helpers\Html;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model \app\models\SipAR */
/* @var $users array */

$this->title = 'Новый сип';

?>
<div>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users
    ]) ?>

</div>
