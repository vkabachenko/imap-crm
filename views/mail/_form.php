<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\Mails */
/* @var $users array */
/* @var $form yii\widgets\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'server') ?>
    <?= $form->field($model, 'login') ?>
    <?= $form->field($model, 'pwd') ?>
    <?= $form->field($model, 'comment') ?>
    <?= $form->field($model, 'signature') ?>
    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::className()) ?>
    <?= $form->field($model, 'users')->checkboxList($users) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
