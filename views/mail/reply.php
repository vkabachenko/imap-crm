<?php

/* @var $model \app\models\EmailReply */
/* @var $uploadForm \app\models\UploadForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ответ на письмо';
?>

<div>
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'from'); ?>
        <?= $form->field($model, 'to'); ?>
        <?= $form->field($model, 'subject'); ?>
        <?= $form->field($model, 'content')->textarea(); ?>
        <?= $form->field($model, 'comment')->textarea(); ?>

        <?= $form->field($uploadForm, 'files[]')->fileInput(['multiple' => true]); ?>


    <div class="form-group">
            <?= Html::submitButton('Отправить',
                ['class' => 'btn btn-success']) ?>
        </div>


    <?php ActiveForm::end(); ?>
</div>

