<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\MailForwardForm */
/* @var $mailId int */
/* @var $form yii\widgets\ActiveForm */
?>


<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <?= \Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<div>

    <?php $form = ActiveForm::begin(['action' => ['mail-forward/send', 'mailId' => $mailId]]); ?>

        <?= $form->field($model, 'to') ?>

        <div class="form-group">
            <?= Html::submitButton('Переслать', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
