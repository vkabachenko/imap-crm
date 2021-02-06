<?php

use yii\helpers\Html;

/* @var $star string */
/* @var $id int */

?>

<?= Html::beginForm(['site/star', 'id' => $id], 'POST', ['id' => 'form-star']) ?>

<div class="form-group">
    <label class="control-label" for="star-id">Ваша оценка звонка</label>
    <input type="text" id="star-id" class="form-control" name="star" value="<?= $star ?>">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-success">Отправить</button>
</div>

<?= Html::endForm(); ?>
