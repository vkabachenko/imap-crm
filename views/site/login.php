<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h3 class="form-title"><?php echo $this->title; ?></h3>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"input-icon\"> <i class=\"fa fa-user\"></i>{input}</div>\n{error}",
            'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
        ],
    ]); ?>

        <?= $form->field($model, 'username', [
      'inputOptions' => [
          'placeholder' => $model->getAttributeLabel('Ваш логин'),
      ],
  ])->textInput(['autofocus' => true])->textInput()->hint('Пожалуйста, введите имя')->label('Имя'); ?>

        <?= $form->field($model, 'password', [
      'inputOptions' => [
          'placeholder' => $model->getAttributeLabel('Ваш пароль'),
      ],
  ])->passwordInput() ?>


        <div class="form-actions">
                <?= Html::submitButton('Войти', ['class' => 'btn green pull-right', 'name' => 'login-button']) ?>
        </div>
        <br /> <br />
    <?php ActiveForm::end(); ?>


<form action="/site/setxml" method="post" target="_blank" enctype="multipart/form-data">
<div class="form-group">
<label class="control-label">Файл<span class="required">
* </span>
</label>
<input type="file" class="form-control" name="file" value="">
</div>

<input type="submit" value="Ok">
</form>


