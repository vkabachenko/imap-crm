<?php

/* @var $model \app\models\EmailReply */
/* @var $uploadForm \app\models\UploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
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


    <div class="row">

        <div class="form-group  col-md-4">
                <?= Html::submitButton('Отправить',
                    ['class' => 'btn btn-success']) ?>
        </div>

        <div class="form-group col-md-4">
            <?= Html::a('Отмена',
                Url::to(['mail/release-mail', 'mailId' => $model->reply_to_id]),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>
</div>
