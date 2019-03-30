<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\widgets\LinkPager;
$thisPageId='order_status';
$thisDbTable='order_status';
$thisPagelmit=10;
$this->title = 'Статусы заказов';
$thisRule = explode(",",Yii::$app->user->identity->rule);

$this->titleBut = '<a href="'.Url::toRoute([$thisPageId, 'add' => 'true']).'" class="btn btn-xs green">Добавить <i class="fa fa-plus"></i></a>';

if(Yii::$app->request->get('add')){



?><div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Название','name') ?>
<?= Html::textInput('name','', ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Передавать в 1с?','name') ?>
<input name="t" type="checkbox" value="1" checked>
</div>

<?= Html::submitButton('Сохранить', ['class' => 'btn blue btn-block', 'name' => 'save']) ?>

</form>
</div>

<?php
   }elseif(Yii::$app->request->get('eid')){

$edit = $model->GetRow();
?>
<div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Название','name') ?>
<?= Html::textInput('name',$edit['name'], ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Передавать в 1с?','name') ?>
<input name="t" type="checkbox" value="1"<?php if($edit['t']==1){echo ' checked';} ?>>
</div>

<?= Html::submitButton('Сохранить', ['class' => 'btn blue btn-block', 'name' => 'save']) ?>

</form>
</div>
<?php
   }else {
$GetRows = $model->GetRows();
$pages=$GetRows['p'];
$rows=$GetRows['rows'];

?>

  <table class="table table-bordered" id="sample_6">
<thead>
<tr>
<th>#</th>
<th>Название</th>
<th>-</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $value) {
?>
<tr>
<td><?php  echo $value['id'];  ?></td>
<td><?php  echo $value['name'];  ?></td>
<td>
<a href="<?php Echo Url::Toroute([$thisPageId, 'eid' => $value['id']]); ?>" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Редактировать</a>
<a href="<?php Echo Url::Toroute([$thisPageId, 'did' => $value['id']]); ?>" class="btn default btn-xs black"><i class="fa fa-trash-o"></i> Удалить</a>
</td>
</tr>

<?php
}
?>
</tbody>
</table>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<?php
   }

?>