<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\MailForwardForm */
/* @var $mailId int */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(['action' => ['mail-forward/send', 'mailId' => $mailId]]); ?>

        <?= $form->field($model, 'to') ?>

        <div class="form-group">
            <?= Html::submitButton('Переслать', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
