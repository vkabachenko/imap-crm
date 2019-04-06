<?php

use yii\helpers\Html;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model \app\models\Mails */
/* @var $users array */

$this->title = 'Редактировать почтовый ящик';

?>
<div>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users
    ]) ?>

</div>
