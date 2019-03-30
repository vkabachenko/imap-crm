<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\widgets\LinkPager;
$thisPageId='employees';
$thisDbTable='employees';
$thisPagelmit=10;
$this->title = 'Сотрудники';
$thisRule = explode(",",Yii::$app->user->identity->rule);

$rule_arr = $model->GetRuleArr();


$this->titleBut = '<a href="'.Url::toRoute([$thisPageId, 'add' => 'true']).'" class="btn btn-xs green">Добавить <i class="fa fa-plus"></i></a>';

//print_r($this);
if(Yii::$app->request->get('add')){



?><div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Email','email') ?>
<?= Html::textInput('email','', ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Пароль','pwd') ?>
<?= Html::passwordInput('pwd','', ['class' => 'form-control']) ?>
</div>

<div class="form-group">
<?= Html::label('Имя','name') ?>
<?= Html::textInput('name','', ['class' => 'form-control']) ?>
</div>

<div class="form-group">
<?= Html::label('Телефон','tel') ?>
<?= Html::textInput('tel','', ['class' => 'form-control tel']) ?>
</div>

<div class="form-group">
<label class="control-label">Права<span class="required">
* </span>
</label>
										<div class="checkbox-list">
<?php
$expArr = explode(",",$f['status']);
for($i=0;$i<sizeof($rule_arr['id']);$i++){
//if(!eregi('-',$rule_arr['name'][$i])){$rule_arr['name'][$i]='<strong>'.$rule_arr['name'][$i].'</strong>'; }
echo '
											<label>
											<input type="checkbox" name="rul[]" value="'.$rule_arr['id'][$i].'"> '.$rule_arr['name'][$i].' </label>
';

	}
?>
										</div>

</div>

<?= Html::submitButton('Сохранить', ['class' => 'btn blue btn-block', 'name' => 'save']) ?>

</form>
</div>

<?php
   }elseif(Yii::$app->request->get('eid')){
$edit = $model->GetRow();
$thisRul = explode(",",$edit['rule']);
?>
<div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Email','email') ?>
<?= Html::textInput('email',$edit['email'], ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Пароль','pwd') ?>
<?= Html::passwordInput('pwd','', ['class' => 'form-control']) ?>
</div>


<div class="form-group">
<?= Html::label('Имя','name') ?>
<?= Html::textInput('name',$edit['name'], ['class' => 'form-control']) ?>
</div>

<div class="form-group">
<?= Html::label('Телефон','tel') ?>
<?= Html::textInput('tel',$edit['tel'], ['class' => 'form-control tel']) ?>
</div>

<?php  if($edit['id']!=1){  ?>
<div class="form-group">
<label class="control-label">Права<span class="required">
* </span>
</label>
										<div class="checkbox-list">
<?php
$expArr = explode(",",$f['status']);
for($i=0;$i<sizeof($rule_arr['id']);$i++){
//if(!eregi('-',$rule_arr['name'][$i])){$rule_arr['name'][$i]='<strong>'.$rule_arr['name'][$i].'</strong>'; }
echo '
											<label>
											<input type="checkbox" name="rul[]" value="'.$rule_arr['id'][$i].'"'; if(in_array($rule_arr['id'][$i],$thisRul)){echo ' checked';} echo '> '.$rule_arr['name'][$i].' </label>
';

	}
?>
										</div>

</div>
<?php } ?>

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
<th>Имя</th>
<th>Телефон</th>
<th>Email</th>
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
<td><?php  echo $value['tel'];  ?></td>
<td><?php  echo $value['email'];  ?></td>
<td>

<a href="<?php Echo Url::Toroute([$thisPageId, 'eid' => $value['id']]); ?>" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Редактировать</a>
<?php  if($value['id']!=1){  ?><a href="<?php Echo Url::Toroute([$thisPageId, 'did' => $value['id']]); ?>" class="btn default btn-xs black delclass"><i class="fa fa-trash-o"></i> Удалить</a> <?php } ?>
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