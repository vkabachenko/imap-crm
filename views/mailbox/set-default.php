<?php

use yii\helpers\Html;
use app\models\Mails;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\EmployeesAR */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Почта по умолчанию';

?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'default_mailbox_id')
        ->dropDownList(Mails::userMailboxesAsMap($model->id),['prompt' => 'Выбор']); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
